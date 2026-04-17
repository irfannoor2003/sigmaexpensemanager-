<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\WalletTransaction;
use App\Models\WalletRequest;
use App\Models\SystemConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class ExpenseController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Recent expenses (debits)
        $recent = Expense::where('user_id', $user->id)
        // 1. Keep the original name 'expense_date'
        // 2. Add 'created_at' to the list
        ->select('title', 'expense_date', 'amount', 'status', 'created_at', \DB::raw("'debit' as type"))
        ->latest()
        ->take(5)
        ->get();

        $totalLogs = Expense::where('user_id', $user->id)->count();

        $recentCount = Expense::where('user_id', $user->id)
    ->whereMonth('expense_date', now()->month)
    ->count();

        // Approved self-requested top-ups
        $approvedRequests = WalletTransaction::where('user_id', $user->id)
            ->where('type', 'credit')
            ->where('status', 'approved')
            ->where('created_by', $user->id)
            ->get()
            ->take(2);

        // HR/Manager manual credits
        $hrManualCredits = WalletTransaction::where('user_id', $user->id)
            ->where('type', 'credit')
            ->where('status', 'approved')
            ->where(function($q) use ($user) {
                $q->where('created_by', '!=', $user->id)
                  ->orWhereNull('created_by'); // include HR manual credits
            })
            ->get()
            ->take(5);

        // Total inflow this month
        $totalInflow = WalletTransaction::where('user_id', $user->id)
            ->where('type', 'credit')
            ->where('status', 'approved')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');

        // Total spent this month
        $totalSpentMonth = Expense::where('user_id', $user->id)
            ->whereMonth('expense_date', now()->month)
            ->sum('amount');

        // Rejected requests
        $rejectedRequests = WalletRequest::where('user_id', $user->id)
            ->where('status', 'rejected')
            ->get();

        // Merge approved and rejected top-ups for table
        $topUpLogs = WalletTransaction::where('user_id', $user->id)
            ->where('type', 'credit')
            ->whereIn('status', ['approved', 'rejected'])
            ->get()

            ->map(function($item) {
                $item->status_label = $item->status === 'approved' ? 'CLEARED' : 'REJECTED';
                return $item;
            })
            ->sortByDesc('created_at');

        // Prepare chart data for last 6 months
        $months = [];
$chartData = [];

$langMonths = __('app.months');

for ($i = 5; $i >= 0; $i--) {
    $month = Carbon::now()->subMonths($i);

    $months[] = $langMonths[$month->month]; // 👈 THIS FIX

    $spent = Expense::where('user_id', $user->id)
        ->whereMonth('expense_date', $month->month)
        ->whereYear('expense_date', $month->year)
        ->sum('amount');

    $chartData[] = $spent;
}

        return view('manager.dashboard', compact(
            'recent', 'approvedRequests', 'hrManualCredits', 'totalInflow', 'totalSpentMonth',
            'rejectedRequests', 'months', 'chartData', 'topUpLogs','totalLogs','recentCount',
        ));
    }


   public function store(Request $request)
{
    $request->validate([
        'category_id'  => 'required|exists:expense_categories,id',
        'title'        => 'required|string|max:255',
        'amount'       => 'required|numeric|min:1',
        'expense_date' => 'required|date',
        'image'        => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        'remarks'      => 'nullable|string',
    ]);

    $user = auth()->user();
    $expenseDate = Carbon::parse($request->expense_date);
    $now = now();

    /**
     * 1. STRICT MONTH CHECK:
     * Block anything older than the start of the previous month (e.g., if April, block February)
     */
    $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
    if ($expenseDate->lt($startOfLastMonth)) {
        return back()->withInput()->with('error', '🚫 Records older than ' . $startOfLastMonth->format('F') . ' are locked for security.');
    }

    /**
     * 2. DEADLINE LOGIC:
     * Check if HR has closed the window for the previous month's bills.
     */
    if ($expenseDate->lt($now->copy()->startOfMonth())) {
        $config = SystemConfig::where('key', 'grace_period_deadline')
            ->where('month_year', $now->format('m-Y'))
            ->first();

        $deadline = $config
            ? Carbon::parse($config->value)
            : $now->copy()->startOfMonth()->addDays(2)->endOfDay();

        if ($now->gt($deadline)) {
            return back()->withInput()->with('error', '⚠️ Submission window for ' . $expenseDate->format('F') . ' is closed.');
        }
    }

    // 3. BALANCE CHECK
  if ($user->wallet < $request->amount) {
    return redirect()
        ->route('manager.dashboard')
        ->with('error', '⚠️ Insufficient balance! Please request funds.')
        ->with('openRequestModal', true)
        ->with('requestedAmount', $request->amount)
        ->with('shortage', $request->amount - $user->wallet);
}

    try {
        DB::transaction(function () use ($request, $user, $expenseDate) {
            $path = $request->file('image')->store('receipts', 'public');

            Expense::create([
                'user_id'      => $user->id,
                'category_id'  => $request->category_id,
                'title'        => $request->title,
                'amount'       => $request->amount,
                'expense_date' => $request->expense_date,
                'image'        => $path,
                'description'  => $request->remarks,
                'status'       => 'approved',
            ]);

            WalletTransaction::create([
                'user_id'    => $user->id,
                'amount'     => $request->amount,
                'type'       => 'debit',
                'remarks'    => 'Expense: ' . $request->title,
                'status'     => 'approved',
                'created_by' => $user->id
            ]);

            $user->decrement('wallet', $request->amount);
        });

        return redirect()->route('manager.dashboard')->with('success', '✅ Expense logged successfully!');

    } catch (\Exception $e) {
        return back()->with('error', 'Error: ' . $e->getMessage());
    }
}

    public function requestFunds(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'remarks' => 'nullable|string|max:255',
        ]);

        WalletRequest::create([
            'user_id' => auth()->id(),
            'amount' => $request->amount,
            'reason' => $request->remarks,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Top-up request submitted successfully!');
    }

    public function viewRequests()
    {
        $requests = WalletRequest::with('user')->orderBy('created_at', 'desc')->get();
        $approved = $requests->where('status', 'approved');
        $rejected = $requests->where('status', 'rejected');
        $pending  = $requests->where('status', 'pending');

        return view('manager.wallet-requests', compact('requests', 'approved', 'rejected', 'pending'));
    }

public function create()
{
    $user = auth()->user();

    // Get deadline for CURRENT month's wind-up of PREVIOUS month records
    $config = SystemConfig::where('key', 'grace_period_deadline')
        ->where('month_year', now()->format('m-Y'))
        ->first();

    // Default to the 3rd of the current month if not set
    $deadline = $config ? Carbon::parse($config->value) : now()->startOfMonth()->addDays(2)->endOfDay();

    $categories = \App\Models\ExpenseCategory::all();

    return view('manager.create-expense', compact('user', 'deadline', 'categories'));
}


public function expenseHistory()
{
    $expenses = Expense::where('user_id', auth()->id())
        // Filter by the current month and year
        ->whereMonth('expense_date', now()->month)
        ->whereYear('expense_date', now()->year)
        ->latest('expense_date')
        ->paginate(10);

    return view('manager.history.expenses', compact('expenses'));
}

public function topupHistory()
{
    $topups = WalletTransaction::where('user_id', auth()->id())
        ->where('type', 'credit')
        // Filter by the current month and year
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->latest()
        ->paginate(10);

    return view('manager.history.topups', compact('topups'));
}
public function expenseOverview(Request $request)
{
    $user = auth()->user();

    $selectedMonth = $request->get('month', now()->format('Y-m'));
    $date = Carbon::parse($selectedMonth);

    // 🔥 FULL DATA (for stats + chart)
    $allExpenses = Expense::where('user_id', $user->id)
        ->whereMonth('expense_date', $date->month)
        ->whereYear('expense_date', $date->year)
        ->orderBy('expense_date', 'asc')
        ->get();

    // 📄 PAGINATED DATA (for table only)
    $expenses = Expense::where('user_id', $user->id)
        ->whereMonth('expense_date', $date->month)
        ->whereYear('expense_date', $date->year)
        ->latest('expense_date')
        ->paginate(10);

    // 💰 TOTAL SPEND (MONTH)
    $totalSpend = $allExpenses->sum('amount');

    // 📊 TOTAL LOGS
    $totalLogs = $allExpenses->count();

    // 📈 DAILY AVERAGE
    $daysInMonth = $date->daysInMonth;
    $averagePerDay = $daysInMonth > 0 ? $totalSpend / $daysInMonth : 0;

    return view('manager.my-expenses', compact(
        'expenses',
        'allExpenses',
        'totalSpend',
        'totalLogs',
        'averagePerDay'
    ));
}
public function edit(Expense $expense)
{
    // Security: only owner can edit
    if ($expense->user_id !== auth()->id()) {
        abort(403);
    }

    $categories = \App\Models\ExpenseCategory::all();

    return view('manager.edit-expense', compact('expense', 'categories'));
}
public function update(Request $request, Expense $expense)
{
    if ($expense->user_id !== auth()->id()) {
        abort(403);
    }

    $request->validate([
        'category_id'  => 'required|exists:expense_categories,id',
        'title'        => 'required|string|max:255',
        'amount'       => 'required|numeric|min:1',
        'expense_date' => 'required|date',
        'image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        'remarks'      => 'nullable|string',
    ]);

    DB::transaction(function () use ($request, $expense) {

        $oldAmount = $expense->amount;
        $user = auth()->user();

        // Handle image update
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('receipts', 'public');
            $expense->image = $path;
        }

        // Update expense
        $expense->update([
            'category_id'  => $request->category_id,
            'title'        => $request->title,
            'amount'       => $request->amount,
            'expense_date' => $request->expense_date,
            'description'  => $request->remarks,
        ]);

        /**
         * 🔥 FIX WALLET DIFFERENCE (IMPORTANT)
         * If user changes amount, adjust wallet properly
         */
        $difference = $request->amount - $oldAmount;

        if ($difference != 0) {
            WalletTransaction::create([
                'user_id'    => $user->id,
                'amount'     => abs($difference),
                'type'       => $difference > 0 ? 'debit' : 'credit',
                'remarks'    => 'Expense Adjustment: ' . $expense->title,
                'status'     => 'approved',
                'created_by' => $user->id
            ]);

            if ($difference > 0) {
                $user->decrement('wallet', $difference);
            } else {
                $user->increment('wallet', abs($difference));
            }
        }
    });

    return redirect()
        ->route('manager.my-expenses')
        ->with('success', '✅ Expense updated successfully!');
}
}

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
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = $month->format('M');

            $spent = Expense::where('user_id', $user->id)
                ->whereMonth('expense_date', $month->month)
                ->whereYear('expense_date', $month->year)
                ->sum('amount');

            $chartData[] = $spent;
        }

        return view('manager.dashboard', compact(
            'recent', 'approvedRequests', 'hrManualCredits', 'totalInflow', 'totalSpentMonth',
            'rejectedRequests', 'months', 'chartData', 'topUpLogs'
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
        return back()->withInput()->with('error', '⚠️ Insufficient wallet balance!');
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
    $date = \Carbon\Carbon::parse($selectedMonth);

    $expenses = Expense::where('user_id', $user->id)
        ->whereMonth('expense_date', $date->month)
        ->whereYear('expense_date', $date->year)
        ->latest('expense_date')
        ->paginate(10); // Use paginate instead of get()

    return view('manager.my-expenses', compact('expenses'));
}
}

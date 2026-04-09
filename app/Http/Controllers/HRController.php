<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Expense;
use App\Models\WalletTransaction;
use App\Models\WalletRequest;
use App\Models\SystemConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\ExpensesExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class HRController extends Controller
{
    /**
     * HR Dashboard: Overview of Requests, Live Logs, and Financial Stats
     */
   public function index(Request $request)
{
    $managers = User::where('role', 'expense_manager')->get();

    // Pending Money Requests
    $moneyRequests = WalletRequest::where('status', 'pending')->with('user')->latest()->get();

    // Live Logs (recent expenses)
    $liveLogs = Expense::with(['user', 'category'])->latest()->take(5)->get();

    // Totals for the Top Cards (Month-to-Date)
    $totalSpentMonth = Expense::where('status', 'approved')
        ->whereMonth('expense_date', now()->month)
        ->whereYear('expense_date', now()->year)
        ->sum('amount');

    $totalAddedMonth = WalletTransaction::where('type', 'credit')
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->sum('amount');

    // Totals for the Year
    $totalSpentYear = Expense::where('status', 'approved')
        ->whereYear('expense_date', now()->year)
        ->sum('amount');

    $totalAddedYear = WalletTransaction::where('type', 'credit')
        ->whereYear('created_at', now()->year)
        ->sum('amount');

    // ✅ Spending Graph Filter Logic
    $filter = $request->query('filter', '7days'); // default last 7 days
    $startDate = match($filter) {
        'lastMonth' => now()->subMonth(),
        'last3Months' => now()->subMonths(3),
        'lastYear' => now()->subYear(),
        default => now()->subDays(7),
    };

    $creditsData = WalletTransaction::where('type', 'credit')
        ->where('created_at', '>=', $startDate)
        ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
        ->groupBy('date')
        ->orderBy('date')
        ->pluck('total', 'date');

    $spentData = Expense::where('status', 'approved')
        ->where('expense_date', '>=', $startDate)
        ->selectRaw('DATE(expense_date) as date, SUM(amount) as total')
        ->groupBy('date')
        ->orderBy('date')
        ->pluck('total', 'date');

    // Deadline for HR
    $deadline = SystemConfig::where('key', 'grace_period_deadline')
        ->where('month_year', now()->format('m-Y'))
        ->value('value');

    return view('hr.dashboard', compact(
        'managers',
        'moneyRequests',
        'liveLogs',
        'totalSpentMonth',
        'totalAddedMonth',
        'totalSpentYear',
        'totalAddedYear',
        'creditsData',
        'spentData',
        'deadline',
        'filter' // pass current filter to Blade
    ));
}

    /**
     * Show Manual Credit Page
     */
    public function showCreditPage()
    {
        $users = User::where('role', 'expense_manager')->get();
        return view('hr.credit', compact('users'));
    }

    /**
     * Store Manual Credit (HR directly funding a wallet)
     */
    public function storeCredit(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'amount' => 'required|numeric|min:1',
        'remarks' => 'nullable|string'
    ]);

    DB::transaction(function () use ($request) {
        $user = User::find($request->user_id);

        $user->increment('wallet', $request->amount);

        WalletTransaction::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'type' => 'credit',
            'status' => 'approved',
            'remarks' => "HR Credit: " . ($request->remarks ?? 'Manual funding'),
            'created_by' => auth()->id(),
        ]);
    });

    return back()->with('success', 'Wallet funded successfully!');
}

    /**
     * Approve a Manager's Fund Request
     */
 public function approveRequest(WalletRequest $walletRequest)
{
    DB::transaction(function () use ($walletRequest) {

        $walletRequest->user->increment('wallet', $walletRequest->amount);

        $walletRequest->update(['status' => 'approved']);

        WalletTransaction::create([
            'user_id' => $walletRequest->user_id,
            'amount' => $walletRequest->amount,
            'type' => 'credit',
            'status' => 'approved',
            'remarks' => "Approved Request: " . $walletRequest->reason,
            'created_by' => auth()->id(),
        ]);
    });

    return back()->with('success', 'Funds transferred to ' . $walletRequest->user->name);
}

    /**
     * Reject a Manager's Fund Request
     */
    public function rejectRequest(WalletRequest $walletRequest)
    {
        $walletRequest->update(['status' => 'rejected']);
        return back()->with('error', 'Funding request declined.');
    }

    /**
     * Manage individual expense status (Manual intervention if needed)
     */
    public function updateExpenseStatus(Request $request, Expense $expense)
    {
        $status = $request->status; // approved, rejected

        DB::transaction(function () use ($expense, $status, $request) {
            // If HR manually rejects an auto-approved bill, refund the manager
            if ($status === 'rejected' && $expense->status !== 'rejected') {
                $expense->user->increment('wallet', $expense->amount);
            }

            $expense->update([
                'status' => $status,
                'hr_remarks' => $request->remarks
            ]);
        });

        return back()->with('success', 'Expense ' . ucfirst($status));
    }

    /**
     * Extend Deadline (The 3rd of the Month Bypass)
     */
  public function extendDeadline(Request $request)
{
    // Changed 'deadline_date' to 'deadline' to match the form input name
    $request->validate(['deadline' => 'required|date']);

    SystemConfig::updateOrCreate(
        ['key' => 'grace_period_deadline', 'month_year' => now()->format('m-Y')],
        ['value' => Carbon::parse($request->deadline)->toDateTimeString()]
    );

    return back()->with('success', '✅ Submission deadline updated successfully!');
}

    /**
     * Export to Excel
     */
    public function exportExcel(Request $request)
{
    return Excel::download(
        new ExpensesExport(
            $request->month,
            $request->status,
            $request->user_id,
            $request->search
        ),
        'expenses-' . ($request->month ?? now()->format('Y-m')) . '.xlsx'
    );
}

public function expenseHistory(Request $request)
{
    $query = Expense::with(['user', 'category']);

    // ✅ Handle month input (YYYY-MM)
    if ($request->filled('month')) {
        [$year, $month] = explode('-', $request->month);

        $query->whereYear('expense_date', $year)
              ->whereMonth('expense_date', $month);
    } else {
        // Default = current month
        $query->whereMonth('expense_date', now()->month)
              ->whereYear('expense_date', now()->year);
    }

    // ✅ Optional: filter by status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // ✅ Optional: filter by user (manager)
    if ($request->filled('user_id')) {
        $query->where('user_id', $request->user_id);
    }

    // ✅ Optional: search by title
    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }

    $expenses = $query->latest('expense_date')->paginate(10)->withQueryString();

    return view('hr.history.expenses', compact('expenses'));
}

public function financialHistory(Request $request)
{
    $selectedYear = $request->input('year', now()->year);

    // Get Monthly Spent Totals
    $monthlyStats = Expense::selectRaw("
            MONTH(expense_date) as month,
            SUM(CASE WHEN status = 'approved' THEN amount ELSE 0 END) as total_spent
        ")
        ->whereYear('expense_date', $selectedYear)
        ->groupBy('month')
        ->orderBy('month', 'desc')
        ->get();

    // Get Monthly Credited Totals
    $monthlyCredits = WalletTransaction::selectRaw("
            MONTH(created_at) as month,
            SUM(amount) as total_credited
        ")
        ->where('type', 'credit')
        ->whereYear('created_at', $selectedYear)
        ->groupBy('month')
        ->get()
        ->keyBy('month');

    return view('hr.history.financial', compact('monthlyStats', 'monthlyCredits', 'selectedYear'));
}
}

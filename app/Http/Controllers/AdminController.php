<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Expense;
use Carbon\Carbon;
use App\Models\WalletTransaction; // Use this instead
use App\Models\WalletRequest;
class AdminController extends Controller
{
    // Show create user page
    public function createUserPage()
    {
        return view('admin.create-user');
    }

    // Store new user
    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|in:admin,hr,expense_manager',
            'pin'  => 'required|array|size:5',
            'pin.*'=> 'required|digits:1',
        ]);

        $pin = implode('', $request->pin);

        $user = User::create([
           'name' => $request->name,
    'name_ur' => $request->name_ur,
            'role' => $request->role,
            'pin'  => Hash::make($pin),
        ]);

        return redirect()->route('admin.users')->with('success', 'User initialized successfully!');
    }

    // Show edit user page
    public function editUser(User $user)
    {
        return view('admin.edit-user', compact('user'));
    }

    // Update user
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
'name_ur' => 'nullable|string|max:255',
            'role' => 'required|in:admin,hr,expense_manager',
            'pin'  => 'nullable|array|size:5',
            'pin.*'=> 'nullable|digits:1',
        ]);

        $user->name = $request->name;
        $user->name_ur = $request->name_ur;
        $user->role = $request->role;

        $pinArray = $request->pin ?? [];

// Remove empty values
$filteredPin = array_filter($pinArray, fn($v) => $v !== null && $v !== '');

if (count($filteredPin) === 5) {
    $user->pin = Hash::make(implode('', $pinArray));
}

        $user->save();

        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }

    // Delete user
    public function deleteUser(User $user)
    {
        $user->delete();
        return back()->with('success', 'User deleted successfully!');
    }

    // List all users
    public function listUsers()
{
    $users = User::paginate(10);
    return view('admin.users', compact('users'));
}

   public function dashboard(Request $request)
{
    // Capture the timeframe from the dropdown (default to 6)
    $monthsCount = (int) $request->get('timeframe', 3);

    // Standard counts
    $adminCount = User::where('role', 'admin')->count();
    $hrCount = User::where('role', 'hr')->count();
    $managerCount = User::where('role', 'expense_manager')->count();
    $users = User::latest()->take(5)->get();

    // Fetch expenses with user relation
    $expenses = Expense::with('user')->get();

    $recentExpenses = $expenses->sortByDesc('created_at')->take(5); // <-- Collection

    // --- Dynamic Cash Flow Data based on timeframe ---
    $monthlyLabels = [];
    $monthlyData = [];
    Carbon::setLocale(app()->getLocale());

    for ($i = $monthsCount - 1; $i >= 0; $i--) {
        $date = Carbon::now()->subMonths($i);
        $monthlyLabels[] = $date->translatedFormat('M');

        $monthlyData[] = $expenses->filter(function ($expense) use ($date) {
            $expenseDate = Carbon::parse($expense->expense_date);
            return $expenseDate->year == $date->year && $expenseDate->month == $date->month;
        })->sum('amount');
    }

    return view('admin.dashboard', compact(
        'users',
        'adminCount',
        'hrCount',
        'managerCount',
        'expenses',
        'recentExpenses',
        'monthlyLabels',
        'monthlyData'
    ));
}

public function history(Request $request)
{
    $selectedMonth = $request->get('month', Carbon::now()->format('Y-m'));
    $date = Carbon::parse($selectedMonth);

    // 1. Get ALL expenses for the month to calculate stats and charts
    $allMonthlyExpenses = Expense::with('user', 'category')
        ->whereYear('expense_date', $date->year)
        ->whereMonth('expense_date', $date->month)
        ->get();

    // 2. Get PAGINATED expenses for the table
    $expenses = Expense::with('user', 'category')
        ->whereYear('expense_date', $date->year)
        ->whereMonth('expense_date', $date->month)
        ->orderBy('expense_date', 'desc')
        ->paginate(5);

    return view('admin.expenses.history', compact(
        'expenses',
        'allMonthlyExpenses',
        'selectedMonth'
    ));
}
public function analytics(Request $request)
{
    $year = $request->get('year', date('Y'));

    // 1. Monthly Credits (Money added to wallets by HR/Admin)
    // We filter by type = 'credit' (or whatever string you use for additions)
    $monthlyCredits = WalletTransaction::whereYear('created_at', $year)
        ->where('type', 'credit')
        ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
        ->groupBy('month')
        ->pluck('total', 'month')
        ->all();

    // 2. Monthly Debits (Spending/Expenses)
    $monthlyDebits = Expense::whereYear('expense_date', $year)
        ->selectRaw('MONTH(expense_date) as month, SUM(amount) as total')
        ->groupBy('month')
        ->pluck('total', 'month')
        ->all();

    $months = [
    __('app.Jan'),
    __('app.Feb'),
    __('app.Mar'),
    __('app.Apr'),
    __('app.May'),
    __('app.Jun'),
    __('app.Jul'),
    __('app.Aug'),
    __('app.Sep'),
    __('app.Oct'),
    __('app.Nov'),
    __('app.Dec'),
];
    $creditsData = [];
    $debitsData = [];

    for ($i = 1; $i <= 12; $i++) {
        $creditsData[] = (float)($monthlyCredits[$i] ?? 0);
        $debitsData[] = (float)($monthlyDebits[$i] ?? 0);
    }

    $totalYearlyCredit = array_sum($creditsData);
    $totalYearlyDebit = array_sum($debitsData);

    return view('admin.analytics', compact(
        'creditsData',
        'debitsData',
        'months',
        'year',
        'totalYearlyCredit',
        'totalYearlyDebit'
    ));
}
public function listExpenses(Request $request)
{
    $selectedMonth = $request->get('month', now()->format('Y-m'));
    $date = Carbon::parse($selectedMonth);

    $allExpenses = Expense::with('user', 'category')
        ->whereYear('expense_date', $date->year)
        ->whereMonth('expense_date', $date->month)
        ->get();

    $expenses = Expense::with('user', 'category')
        ->whereYear('expense_date', $date->year)
        ->whereMonth('expense_date', $date->month)
        ->orderBy('expense_date', 'desc')
        ->paginate(10);

    return view('admin.expenses', compact('expenses', 'allExpenses', 'selectedMonth'));

}
}

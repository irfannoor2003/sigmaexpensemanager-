<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HRController; // ✅ Added this import
use App\Http\Controllers\ExpenseController; // ✅ Added this import
use App\Models\Expense;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


Route::get('/lang/{locale}', function ($locale) {

    if (!in_array($locale, ['en', 'ur'])) {
        abort(400);
    }

    Session::put('locale', $locale);

    return redirect()->back();
})->name('lang.switch');

Route::get('/', function () {

    if (Auth::check()) {

        // 🔥 Redirect based on role
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if (auth()->user()->role === 'hr') {
            return redirect()->route('hr.dashboard');
        }

        if (auth()->user()->role === 'expense_manager') {
            return redirect()->route('manager.dashboard');
        }
    }

    return redirect()->route('login');

})->name('home');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN DASHBOARD
|--------------------------------------------------------------------------
*/
Route::middleware(['custom.auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function() {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'listUsers'])->name('users');
    Route::get('/create-user', [AdminController::class, 'createUserPage'])->name('create-user');
    Route::post('/create-user', [AdminController::class, 'createUser'])->name('store-user');
    Route::get('/edit-user/{user}', [AdminController::class, 'editUser'])->name('edit-user');
    Route::put('/update-user/{user}', [AdminController::class, 'updateUser'])->name('update-user');
    Route::delete('/delete-user/{user}', [AdminController::class, 'deleteUser'])->name('delete-user');
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics');

   Route::get('/expenses', [AdminController::class, 'listExpenses'])->name('expenses');
});

/*
|--------------------------------------------------------------------------
| HR DASHBOARD & CONTROLS (The Banker Flow)
|--------------------------------------------------------------------------
*/
Route::middleware(['custom.auth', 'role:hr'])->prefix('hr')->name('hr.')->group(function() {
    Route::get('/dashboard', [HRController::class, 'index'])->name('dashboard');
    Route::get('/credit', [HRController::class, 'showCreditPage'])->name('credit');
    Route::post('/credit', [HRController::class, 'storeCredit'])->name('store-credit');

    // Fund Request Approvals
    Route::post('/request/{walletRequest}/approve', [HRController::class, 'approveRequest'])->name('approve-request');
    Route::post('/request/{walletRequest}/reject', [HRController::class, 'rejectRequest'])->name('reject-request');
    Route::get('/history/expenses', [HRController::class, 'expenseHistory'])->name('expenses.history');
    Route::get('/financial-history', [HRController::class, 'financialHistory'])->name('financial-history');

    // General Controls
    Route::post('/expense/{expense}/status', [HRController::class, 'updateExpenseStatus'])->name('update-status');
    Route::post('/extend-deadline', [HRController::class, 'extendDeadline'])->name('extend-deadline');
    Route::get('/export-expenses', [HRController::class, 'exportExcel'])->name('export');
});

/*
|--------------------------------------------------------------------------
| EXPENSE MANAGER DASHBOARD
|--------------------------------------------------------------------------
*/
Route::middleware(['custom.auth', 'role:expense_manager'])->prefix('manager')->name('manager.')->group(function() {

    // Main Dashboard
    Route::get('/dashboard', [ExpenseController::class, 'index'])->name('dashboard');

    // Expense Submission
    Route::get('/create-expense', [ExpenseController::class, 'create'])->name('create-expense');
    Route::post('/expense', [ExpenseController::class, 'store'])
        ->middleware('check.deadline')
        ->name('expense');

    // Fund Requests (Top-ups)
    Route::post('/request-funds', [ExpenseController::class, 'requestFunds'])->name('request-funds');

    // History & Tracking (Cleaned up)
    Route::get('/expense-history', [ExpenseController::class, 'expenseHistory'])->name('expenses.history');
    Route::get('/topup-history', [ExpenseController::class, 'topupHistory'])->name('topup.history');

    // Suggestion: Use the controller for this to handle the filtering logic properly
    Route::get('/my-expenses', [ExpenseController::class, 'expenseOverview'])->name('my-expenses');

});

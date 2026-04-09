<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
{
    if (auth()->check()) {

        return redirect()->route(match(auth()->user()->role) {
            'admin' => 'admin.dashboard',
            'hr' => 'hr.dashboard',
            'expense_manager' => 'manager.dashboard',
            default => 'login',
        });
    }

    return view('auth.login');
}

    public function login(Request $request)
    {
        // Validate 'pin' array
        $request->validate([
            'pin'   => 'required|array|size:5',
            'pin.*' => 'required|digits:1',
        ]);

        $pin = implode('', $request->pin);

        // Find user with hashed PIN
        $user = User::all()->first(function($u) use ($pin) {
            return Hash::check($pin, $u->pin);
        });

        if (!$user) {
            return back()->with('error', 'Invalid PIN.');
        }

        // Login user
        auth()->login($user);
        session(['user_id' => $user->id]);

        // Show redirecting toast
        session()->flash('info', 'Redirecting...');

        // Redirect to role-based dashboard with success flash for dashboard page
        $dashboardRoute = match($user->role) {
            'admin' => 'admin.dashboard',
            'hr' => 'hr.dashboard',
            'expense_manager' => 'manager.dashboard',
            default => 'login',
        };

        return redirect()->route($dashboardRoute)->with('success', 'Successfully logged in!');
    }

    public function logout()
    {
        auth()->logout();
        session()->flush();
        return redirect()->route('login');
    }
}

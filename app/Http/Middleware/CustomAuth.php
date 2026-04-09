<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CustomAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('user_id')) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $user = User::find(session('user_id'));

        if (!$user) {
            session()->forget('user_id');
            return redirect()->route('login')->with('error', 'User not found.');
        }

        Auth::setUser($user);

        return $next($request);
    }
}

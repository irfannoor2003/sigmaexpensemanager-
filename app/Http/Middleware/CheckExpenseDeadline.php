<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckExpenseDeadline
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
   public function handle($request, $next)
{
    // Skip check if user is not a manager
    if (auth()->user()->role !== 'expense_manager') return $next($request);

    $today = now();
    $deadline = 3; // Default 3rd of the month

    // Check if HR extended the deadline in the DB
    $extension = \App\Models\SystemConfig::where('key', 'grace_period_deadline')
                ->where('month_year', $today->format('m-Y'))
                ->first();

    $currentDeadline = $extension ? \Carbon\Carbon::parse($extension->value)->day : $deadline;

    // Check if today is past the deadline AND user is trying to add a previous month expense
    if ($today->day > $currentDeadline) {
        // Here you can check the 'expense_date' from the request
        if ($request->has('expense_date')) {
            $eDate = \Carbon\Carbon::parse($request->expense_date);
            if ($eDate->month < $today->month || $eDate->year < $today->year) {
                return back()->with('error', 'The deadline to add bills for the previous month has passed.');
            }
        }
    }

    return $next($request);
}
}

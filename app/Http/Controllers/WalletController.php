<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function addCredit(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01'
        ]);

        $user = User::find($request->user_id);
        $user->wallet += $request->amount;
        $user->save();

        return back()->with('success', 'Credit added successfully');
    }
}

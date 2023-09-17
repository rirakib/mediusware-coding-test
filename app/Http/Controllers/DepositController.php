<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Auth;

class DepositController extends Controller
{
    //
    public function index()
    {
        $deposits = Transaction::whereTransactionType(Transaction::DEPOSIT)
                    ->whereUserId(auth()->id())
                    ->latest()->get();
        return view('deposit',compact('deposits'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required | gt:0',
            'date' => 'required'
        ]);

        $user = Auth::user();

        $transaction = new Transaction();
        $transaction->user_id = auth()->user()->id;
        $transaction->transaction_type = Transaction::DEPOSIT;
        $transaction->amount = $request->amount;
        $transaction->fee = 0;
        $transaction->date = $request->date;
        $transaction->save();

        $user = Auth::user();
        $user->balance += $transaction->amount;
        $user->save();
        return back()->with('success','deposit successfull');

    }
}

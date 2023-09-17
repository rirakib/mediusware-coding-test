<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Auth;

class WithdrawController extends Controller
{
    public function index()
    {
        $withdraws = Transaction::whereTransactionType(Transaction::WITHDRAW)
                     ->whereUserId(auth()->user()->id)
                     ->latest()->get();

        return view('withdraw',compact('withdraws'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required',
            'amount' => 'required | gt:0'
        ]);

        $isAvailable = Auth::user()->balance >= $request->amount ? true : false;

        if(!$isAvailable){
            return back();
        }

        $fee = 0;
        $checkFeeCharge = $this->withdrawFeeCheck(Auth::user(),$request->amount,$request->date);



        if($checkFeeCharge['fee'] >  0){

            if($checkFeeCharge['hasFirstWithdraw'] || $checkFeeCharge['maximumAmountWithdraw'] || $checkFeeCharge['perMonthWithdraw'] )
            {

                if($checkFeeCharge['hasFirstWithdraw']){

                    $chargableAmount = $request->amount - Transaction::FIRST_PER_TRANSACTION_FREE_AMOUNT;
                    $fee = $this->calculationCharge($chargableAmount,$checkFeeCharge['fee']);

                }

                if($checkFeeCharge['perMonthWithdraw']){

                    $fee = $this->calculationCharge($request->amount,$checkFeeCharge['fee']);

                }

                if($checkFeeCharge['maximumAmountWithdraw']){
                    $fee = $this->calculationCharge($request->amount,$checkFeeCharge['fee']);
                }



            }else{

                $fee = $this->calculationCharge($request->amount,$checkFeeCharge['fee']);
            }
        }

        $transaction = new Transaction();
        $transaction->user_id = auth()->user()->id;
        $transaction->transaction_type = Transaction::WITHDRAW;
        $transaction->amount = $request->amount;
        $transaction->fee = $fee;
        $transaction->date = $request->date;
        $transaction->save();

        $user = Auth::user();
        $user->balance -= ($transaction->amount + $transaction->fee);
        $user->save();
        return back()->with('success','withdraw successfull');


    }

    protected function withdrawFeeCheck($user,$amount,$date){

        //individual account
        $fee = Transaction::INDIVIDUAL_PERCENTAGE;


        if($user->user_type == 'bussiness')
        {
            $fee = Transaction::BUSSINESS_PERCENTAGE;
        }


        //day check
        if(date('l',strtotime($date)) == Transaction::FREE_WITHDRAW_DAY){
            $fee = 0;
            return [
                'fee' => $fee,
                'hasFirstWithdraw' => false,
                'maximumAmountWithdraw' => false,
                'perMonthWithdraw' => false
            ];
        }


        if($amount < Transaction::FIRST_PER_TRANSACTION_FREE_AMOUNT)
        {
            return [
                'fee' => $fee,
                'hasFirstWithdraw' => false,
                'maximumAmountWithdraw' => false,
                'perMonthWithdraw' => false
            ];
        }else{

            $perTransactionOfferCheck = Transaction::whereTransactionType(Transaction::WITHDRAW)
                              ->whereUserId(Auth()->user()->id)
                              ->where('amount','>=',Transaction::FIRST_PER_TRANSACTION_FREE_AMOUNT)
                              ->get();


            if($perTransactionOfferCheck->count() === 0){

                return [
                    'fee' => $fee,
                    'hasFirstWithdraw' => true,
                    'maximumAmountWithdraw' => false,
                    'perMonthWithdraw' => false
                ];

            }else{


                $perMonthCheckTransaction = Transaction::whereTransactionType(Transaction::WITHDRAW)
                              ->whereUserId(Auth()->user()->id)
                              ->where('amount','>=',Transaction::PER_MONTH_MAXIMUM_WITHDRAW_FREE)
                              ->whereMonth('date',date('m',strtotime($date)))
                              ->get();

                if($amount >= Transaction::PER_MONTH_MAXIMUM_WITHDRAW_FREE){

                    if($perMonthCheckTransaction->count() === 0){
                        return [
                            'fee' => 0,
                            'hasFirstWithdraw' => false,
                            'maximumAmountWithdraw' => false,
                            'perMonthWithdraw' => false
                        ];
                    }else{
                        return [
                            'fee' => $fee,
                            'hasFirstWithdraw' => false,
                            'maximumAmountWithdraw' => false,
                            'perMonthWithdraw' => true
                        ];
                    }

                }else{

                    $totalWithDrawAmount = Transaction::whereTransactionType(Transaction::WITHDRAW)
                                           ->whereUserId(auth()->user()->id)->sum('amount');

                    if($totalWithDrawAmount >= Transaction::AFTER_MAXIMUM_WITHDRAW_FEE_DECREASE)
                    {
                        return [
                            'fee' => Transaction::INDIVIDUAL_PERCENTAGE,
                            'hasFirstWithdraw' => true,
                            'maximumAmountWithdraw' => true,
                            'perMonthWithdraw' => false
                        ];

                    }else{
                        return [
                            'fee' => $fee,
                            'hasFirstWithdraw' => false,
                            'maximumAmountWithdraw' => false,
                            'perMonthWithdraw' => false
                        ];
                    }


                }

            }



        }
    }

    protected function calculationCharge($amount,$fee)
    {
        return ($fee / 100) * $amount;
    }
}

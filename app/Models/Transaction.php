<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    const DEPOSIT = 'deposit';
    const WITHDRAW = 'withdraw';

    const INDIVIDUAL_PERCENTAGE = 0.015;
    const BUSSINESS_PERCENTAGE = 0.025;
    const FIRST_PER_TRANSACTION_FREE_AMOUNT = 1000;
    const FREE_WITHDRAW_DAY = 'Friday';
    const AFTER_MAXIMUM_WITHDRAW_FEE_DECREASE = 50000;
    const PER_MONTH_MAXIMUM_WITHDRAW_FREE = 5000;

    protected $guarded = ['id'];

}

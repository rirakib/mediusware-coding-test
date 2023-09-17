<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\WithdrawController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('auth')->group(function(){
    
    Route::get('/',[DashboardController::class,'index'])->name('dashboard');

    //depoist
    Route::get('/deposit',[DepositController::class,'index'])->name('deposit.index');
    Route::post('/deposit',[DepositController::class,'store'])->name('deposit.store');

    //withdraw
    Route::get('/withdraw',[WithdrawController::class,'index'])->name('withdraw.index');
    Route::post('/withdraw',[WithdrawController::class,'store'])->name('withdraw.store');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

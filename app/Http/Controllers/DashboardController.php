<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data['balance'] = auth()->user()->balance;
        return view('dashboard');
    }
}

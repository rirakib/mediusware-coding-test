@extends('layout.master')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="card p-3">
                <h1>{{Auth()->user()->deposit->sum('amount')}}</h1>
                <strong>Total Deposit</strong>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3">
                <h1>{{Auth()->user()->withdraw->sum('amount')}}</h1>
                <strong>Total Withdraw</strong>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3">
                <h1>{{Auth()->user()->balance}}</h1>
                <strong>Current Balance</strong>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3">
                <h1>{{Auth()->user()->withdraw->sum('fee')}}</h1>
                <strong>Fee</strong>
            </div>
        </div>
    </div>
</div>


@endsection

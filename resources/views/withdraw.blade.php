@extends('layout.master')
@section('content')
<h3>Withdraw balance</h3>
    <div class="container mt-5 mb-5">
        <form action="{{ route('withdraw.store') }}" method="post">
            <div class="row">
                @csrf
                <div class="col-md-6 form-group">
                    <label for="date">Date</label>
                    <input type="date" step="any" class="form-control" name="date" required>
                </div>
                <div class="col-md-6 form-group">
                    <label for="amount">Amount</label>
                    <input type="number" step="any" class="form-control" name="amount" required>
                </div>
                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-success">Deposit</button>
                </div>
            </div>
        </form>


    </div>
@endsection

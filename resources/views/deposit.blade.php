@extends('layout.master')
@section('content')
    <div class="container mt-5 mb-5">
        <form action="{{ route('deposit.store') }}" method="post">
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

        <div class="row mt-5">
            <div class="col-md-12">
                <div class="responsive-table">
                    <table class="table table-bordered">
                        <thead>
                            <th>Sl</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Action</th>
                        </thead>
                    <tbody>
                        @forelse ($deposits as $k=>$deposit)
                            <tr>
                                <td>{{$k+1}}</td>
                                <td>{{$deposit->amount}}</td>
                                <td>{{$deposit->date}}</td>
                                <td><a href="">edit</a><a href="">delete</a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">no deposit created yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('content')
    <div class="bankView">
        <h2>{{ __('Overview') }}</h2>
        <button type="button" class="btn-secondary" onclick="window.location='{{ url("/bankaccounts") }}'">{{ __('Back') }}</button>
        <h2>{{ __('Balance') }}: {{$acc->balance}} &euro;</h2>
        @foreach($tikkos as $p)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('Payment from') }}: {{$p->user_name}}</h5>
                    <p class="card-text">{{ __('Amount') }}: {{$p->amount}}<br>
                    <p class="card-text">{{ __('Currency') }}: {{$p->tikko_currency}}<br>
                    <p class="card-text">{{ __('Note') }}: {{$p->note}}<br>
                    <p class="card-text">{{ __('Date') }}: {{$p->date}}<br>
                </div>
            </div><br>
        @endforeach
    </div>
@endsection

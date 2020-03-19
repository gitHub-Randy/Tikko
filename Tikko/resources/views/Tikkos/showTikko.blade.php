@extends('layouts.app')
@section('content')
    <div class="bankView">
        <h2>{{ __('bank.title') }}</h2>
        <button type="button" class="btn-success" onclick="window.location='{{ url("/bankaccounts") }}'">{{ __('Back') }}</button>
            <div class="card">
                <label for="title">{{ __('Title') }}</label>
                <input id="title" name="title" value="{{$tikko->name}}" readonly>
                <div class="card-body">
                    <label for="description">{{ __('Note') }}</label>
                    <input id="description" name="description" value="{{$tikko->description}}" readonly>

                    <label for="bankAccount">{{ __('Bankaccount') }}</label>
                    <input id="bankAccount" name="bankRekening" value="{{$bankAccount->account_number}}" readonly>

                    <label for="currency">{{ __('Currency') }}</label>
                    <input id="currency" name="currency" value="{{$tikko->currency}}"  readonly>

                    <label for="amount">{{ __('Amount') }}</label>
                    <input id="amount" name="amount" value="{{$tikko->amount}}" readonly>

                    <label for="tikko_date">{{ __('Date') }}</label>
                    <input id="tikko_date" name="tikko_date" value="{{$tikko->tikko_date}}" readonly>

                    @foreach($payers as $payer)
                        <div>
                            <label for="payerName">{{ __('PayerName') }}</label>
                            <input id="payerName" name="payerName" value="{{$payer->name}}" readonly>
                            @foreach($payments as $p)
                                @if($p->payer_id == $payer->id)
                                    <label for="payed">{{ __('hasPayed') }}</label>
                                    <input id="payed" name="payed" value="{{$p->payed}}" readonly>
                                @endif
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>

    </div>
@endsection

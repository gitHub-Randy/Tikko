@extends('layouts.app')
@section('content')
    <div class="bankView">
        <h2>{{ __('Prepare Payment') }}</h2>
        <button type="button" class="btn-success" onclick="window.location='{{ url("/pay")}}'">{{ __('Back') }}</button>
        <form action="{{ route('prepare') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <label for="title">{{ __('Title') }}</label>
                <input id="title" name="title" value="{{$tikko_data->t_name}}" readonly>
                <div class="card-body">
                    <label for="description">{{ __('Description') }}</label>
                    <input id="description" name="description" value="{{$tikko_data->t_desc}}" readonly>

                    <label for="amount">{{ __('Amount') }}</label>
                    <input id="amount" name="amount" value="{{$tikko_data->t_amount}}" readonly>

                    <label for="currency">{{ __('Currency') }}</label>
                    <input id="currency" name="currency" value="{{$tikko_data->t_curr}}"  readonly>

                    <label for="receiver">{{ __('Receiver') }}</label>
                    <input id="receiver" name="receiver" value="{{$tikko_data->u_name}}" readonly>

                </div>
            </div>


            <label for="valuta">{{ __('Use a different Currency') }}</label>
            <select class="form-control" name="valuta">
                <option value="EUR" selected>{{ __('Euro') }}</option>
                <option value="GBP">{{ __('Britisch Pound') }}</option>
                <option value="USD">{{ __('United States Dollar') }}</option>
            </select><br>


            <label for="note">leave a note</label>
            <input type="text" name="note"  class="form-control">
            <br>
            <input type="hidden" id="tikkoId" name="tikkoId" value={{$tikko_data->t_id}}>

            <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
        </form>

    </div>




@endsection

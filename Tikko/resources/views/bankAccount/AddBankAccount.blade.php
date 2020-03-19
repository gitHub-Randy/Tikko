@extends('layouts.app')

@section('content')

    <div class="bankView">
        <h2>{{ __('New bank account')  }}</h2>
        <button type="button" class="btn-secondary" onclick="window.location='{{ url("/bankaccounts") }}'"> {{ __('Back') }}</button>

        <div class="card uper">
            <div class="card-header">
                {{ __('Edit') }}
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div><br />
                @endif
                <form method="post" action="{{ route('bankaccounts.store') }}">
                    <div class="form-group">
                        @csrf
                        <label for="number">{{ __('Bank number') }}:</label>
                        <input type="text" class="form-control" name="account_number"/>
                    </div>

                    <button type="submit" class="btn btn-primary">{{ __('Add') }}</button>
                </form>
            </div>
        </div>
    </div>

@endsection

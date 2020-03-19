@extends('layouts.app')

@section('content')
    <div class="bankView">
        <h2>{{ __('Bank Accounts') }}</h2>
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
                <form method="post" action="{{ route('bankaccounts.update', $account->account_id) }}">
                    @method('PATCH')
                    @csrf
                    <div class="form-group">
                        <label for="account_number">{{ __('Bank number') }}:</label>
                        <input type="text" class="form-control" name="account_number" value={{ $account->account_number }} />
                    </div>
                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                </form>
            </div>
        </div>
    </div>

@endsection

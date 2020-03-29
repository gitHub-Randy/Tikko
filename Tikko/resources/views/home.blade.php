@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('You have successfully logged in!') }}
                        <br><br>
                        <button type="button"
                                onclick="window.location='{{ url("/bankaccounts") }}'">{{ __('Go to bank accounts') }}</button>
                        <br/><br/>
                        <h2>{{ __('Settings') }}</h2>
                        {{ __('Change the language') }}
                            <br/>
                        @if (App::isLocale('en'))
                            <button><a class="dropdown-item" href="lang/nl" id="nl">Nederlands</a></button>
                        @else
                            <button><a class="dropdown-item" href="lang/en" id="en">English</a></button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

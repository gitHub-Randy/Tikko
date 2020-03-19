@extends('layouts.app')
@section('content')
<div class="bankView">
    <div class="head">
        <h2>{{ __('Bank Accounts') }}</h2>
        <button type="button"   id="addNewAccount" onclick="window.location='{{ route('bankaccounts.create') }}'">
            {{--        <i class="material-icons">add_circle_outline</i>--}}
            <i class="fa fa-plus-circle" id="plusButton"></i>
        </button>
    </div>

    <div class="accounts">
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">{{__('Balance')}}</th>
                <th scope="col">{{ __('Bank number') }}</th>
                <th scope="col">{{ __('Edit') }}</th>
                <th scope="col">{{ __('Details') }}</th>
                <th scope="col">{{ __('Delete') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($accounts as $a)
                <tr>
                    <th scope="row">{{$a->balance}} &euro;</th>
                    <td>{{$a->account_number}}</td>
                    <td><a href="{{ route('bankaccounts.edit',$a->account_id)}}" class="btn btn-primary">{{ __('Edit') }}</a></td>
                    <td><a href="{{ route('bankaccounts.show',$a->account_id)}}" class="btn btn-primary">{{ __('Details') }}</a></td>
                    <td> <form action="{{ route('bankaccounts.destroy', $a->account_id)}}" method="post">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit">{{ __('Delete') }}</button>
                        </form>
                    </td>
                </tr>

            @endforeach

            </tbody>
        </table>


    </div>

</div>

@endsection

@extends('layouts.app')
@section('content')
<div class="bankView">
    <h2>{{ __('Made Tikkos') }}</h2>
    <button type="button"   id="addNewTikko" onclick="window.location='{{ route('tikkos.create') }}'">
        {{--        <i class="material-icons">add_circle_outline</i>--}}
        <i class="fa fa-plus-circle" id="plusButton"></i>
    </button>
    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col">{{ __('Amount') }}</th>
            <th scope="col">{{ __('Currency') }}</th>
            <th scope="col">{{ __('Date') }}</th>
            <th scope="col">{{ __('Note') }}</th>
            <th scope="col">{{ __('Details') }}</th>
            <th scope="col">{{ __('Delete') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($tikkos as $t)
            <tr>
                <th scope="row">{{$t->amount}}</th>
                <td>{{$t->tikko_currency}}</td>
                <td>{{$t->tikko_date}}</td>
                <td>{{$t->description}}</td>

                <td><a href="{{ route('tikkos.show',$t->tikko_id)}}" class="btn btn-primary">{{ __('Details') }}</a></td>
                <td> <form action="{{ route('tikkos.destroy', $t->tikko_id)}}" method="post">
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


@endsection

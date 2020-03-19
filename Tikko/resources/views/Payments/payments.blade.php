@extends('layouts.app')
@section('content')
    <div class="bankView">
        <h2>{{ __('Made Tikkos') }}</h2>

        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">{{ __('Amount') }}</th>
                <th scope="col">{{ __('Currency') }}</th>
                <th scope="col">{{ __('Date') }}</th>
                <th scope="col">{{ __('Note') }}</th>
                <th scope="col">{{ __('Sender') }}</th>
                <th scope="col">{{ __('Pay Tikko') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($tikkos as $t)
                <tr>
                    <th scope="row">{{$t->t_amount}}</th>
                    <td>{{$t->t_curr}}</td>
                    <td>{{$t->t_date}}</td>
                    <td>{{$t->t_desc}}</td>


                    <td>{{$t->u_name}}</td>

                    <td> <form action="{{ route('pay',"data" )}}" method="post">
                            @csrf
                            <input type="hidden" name="t_id" id="t_id" value={{$t->t_id}}>
                            <input type="hidden" name="t_name" id="t_name" value={{$t->t_name}}>

                            <input type="hidden" name="t_desc" id="t_desc" value={{$t->t_desc}}>
                            <input type="hidden" name="t_curr" id="t_curr" value={{$t->t_curr}}>
                            <input type="hidden" name="t_amount" id="t_amount" value={{$t->t_amount}}>
                            <input type="hidden" name="u_name" id="u_name" value={{$t->u_name}}>
                            <input type="hidden" name="p_tId" id="p_tId" value={{$t->p_tId}}>
                            <input type="hidden" name="p_pId" id="p_pId" value={{$t->p_pId}}>
                            <input type="hidden" name="t_date" id="t_date" value={{$t->t_date}}>

                            <button class="btn btn-success" type="submit">{{ __('Pay') }}</button>
                        </form>
                    </td>
                </tr>

            @endforeach

            </tbody>
        </table>

    </div>


@endsection

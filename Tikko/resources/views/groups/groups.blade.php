@extends('layouts.app')
@section('content')
    <div class="bankView">
        <h2>{{ __('Groups') }}</h2>
        <button type="button"   id="addNewGroup" onclick="window.location='{{ route('groups.create') }}'">
            {{--        <i class="material-icons">add_circle_outline</i>--}}
            <i class="fa fa-plus-circle" id="plusButton"></i>
        </button>
        @foreach($groups as $group)
            <div class="card">
                <label for="name">{{ __('Name') }}</label>
                <input id="name" name="name" value="{{$group->name}}" readonly>
                <div class="card-body">
                    <button type="button" class="btn-primary" onclick="window.location='{{ route('groups.edit',$group->id) }}'">Details</button>
                    <form action="{{ route('groups.destroy', $group->id)}}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" type="submit">{{ __('Delete') }}</button>
                    </form>

                </div>
            </div>
        @endforeach


    </div>
@endsection

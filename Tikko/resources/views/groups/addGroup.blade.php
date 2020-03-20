@extends('layouts.app')
@section('content')
    <div class="bankView">
        <h2>{{ __('New group') }}</h2>
        <button type="button" class="btn-success"
                onclick="window.location='{{ url("/bankaccounts") }}'">{{ __('Back') }}</button>
        <form action="{{ route('groups.store') }}" method="post">
            @csrf

            <label for="groupName">{{__("Group name")}}</label>
            <input id="groupName" type="text" class="form-control" name="groupName"/>
            <div>
                <label for="groupSelect">{{__("Select group members")}}</label>
                <select multiple class="form-control" id="groupSelect">
                    @foreach($users as $user)
                        <option>{{$user->name}}</option>
                    @endforeach
                </select>
                <button type="button" onclick="populateSelectedList()"
                        class="btn-primary">{{__('Add to group')}}</button>
            </div>
            <div>
                <label for="groupList">{{__('Select to delete')}}</label>
                <select multiple class="form-control" id="groupList">
                </select>
                <button type="button" onclick="populateUnSelectedList()"
                        class="btn-primary">{{__('Delete from group')}}</button>
            </div>
            {{--            <ul  id="groupList" >--}}
            {{--            </ul>--}}
            <button type="submit" class="btn btn-success float-right" name="submit"
                    value="TikkoOne">{{ __('Make group') }}</button>
        </form>


        <script>
            function populateSelectedList() {
                let groupSelect = document.getElementById('groupSelect').selectedOptions;
                let groupList = document.getElementById('groupList');
                // for each selected person
                for (let i = 0; i < groupSelect.length; i++) {
                    if (!nameDoesExist(groupSelect[i].text, groupList.children)) {
                        let option = document.createElement('OPTION');
                        option.setAttribute('id', groupSelect[i].text);
                        let name = "receiver_" + groupList.getElementsByTagName('OPTION').length;
                        option.innerHTML = `<input type="hidden" name=${name} value=${groupSelect[i].text}> ${groupSelect[i].text}`;
                        groupList.appendChild(option);
                    } else {
                        return alert("selected person already selected")
                    }

                }
            }


            function nameDoesExist(name, selection) {
                let options = selection;
                for (let i = 0; i < options.length; i++) {
                    if (name === options[i].id) {
                        return true;
                    }
                }
                return false;
            }


            function populateUnSelectedList() {
                let unReceiverSelection = document.getElementById('groupList').selectedOptions;
                let unSelection = document.getElementById('groupList');
                //for each unselected person
                for (let i = 0; i < unReceiverSelection.length; i++) {
                    unSelection.removeChild(unReceiverSelection[i]);
                }

            }
        </script>


    </div>



@endsection

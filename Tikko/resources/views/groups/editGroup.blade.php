@extends('layouts.app')
@section('content')
    <div class="bankView">
        <h2>{{ __('Edit group') }}</h2>
        <button type="button" class="btn-success" onclick="window.location='{{ url("/bankaccounts") }}'">{{ __('Back') }}</button>
        <form action="{{ route('groups.update',$group->id) }}" method="post">
            @method('PATCH')
            @csrf
            <label for="groupName">{{__('Group name')}} </label>
            <input id="groupName" type="text" class="form-control" name="groupName" value={{$group->name}} />

            <label for="groupSelect">{{__('Select group members')}}: </label>
            <select multiple class="form-control" id="groupSelect"   >
                @foreach($users as $user)
                    <option id="{{"selection_$user->name"}}">{{$user->name}}</option>
                @endforeach
            </select>
            <button type="button" onclick="populateSelectedList()" class="btn-primary">{{__('Add to group')}}</button>
            <button type="button" onclick="populateUnSelectedList()" class="btn-primary">{{__('Delete from group')}}</button>

            <br>
            <label for="groupList">{{__('Group members')}}</label>
                <ul  id="groupList" >
                @for($i = 0; $i<count($groupMembers);$i++)
                    @if($groupMembers[$i]->user_id == $users[$i]->id)
                            <li id="{{$users[$i]->name}}">
                                <input type="hidden" value="{{$users[$i]->name}}" name="{{"receiver_$i"}}">
                                {{$users[$i]->name}}
                            </li>
                        @endif
                @endfor
            </ul>
            <button type="submit" class="btn btn-primary" name="submit" value="{{$group->id}}">{{ __('Edit') }}</button>
        </form>






        <script>
            function populateSelectedList() {
                let groupSelect = document.getElementById('groupSelect').selectedOptions;
                let groupList = document.getElementById('groupList');
                // for each selected person
                for (let i = 0; i < groupSelect.length; i++) {
                    if (!nameDoesExist(groupSelect[i].text, groupList.children)) {
                        let li = document.createElement('LI');
                        li.setAttribute('id', groupSelect[i].text);
                        let name = "receiver_"+groupList.getElementsByTagName('LI').length;
                        li.innerHTML = `<input type="hidden" name=${name} value=${groupSelect[i].text}> ${groupSelect[i].text}`;
                        groupList.appendChild(li);
                    } else {
                        return alert("selected person already selected")
                    }

                }
            }


            function nameDoesExist(name, selection){
                let options = selection;
                for(let i = 0; i< options.length;i++){
                    console.log(name);
                    console.log(options[i].id);
                    if(name === options[i].id){
                        return true;
                    }
                }
                return false;
            }


            function populateUnSelectedList(){
                let unReceiverSelection = document.getElementById('groupSelect').selectedOptions;
                console.log(unReceiverSelection);

                let unSelection = document.getElementById('groupList');
                console.log(unSelection);
                for(let i = 0; i< unReceiverSelection.length;i++){
                    let idName = unReceiverSelection[i].id;
                    let sliced = idName.replace('selection_','');
                    console.log(sliced);
                    unSelection.removeChild(document.getElementById(sliced));
                }

            }
        </script>


    </div>



@endsection

@extends('layouts.app')
@section('content')
<div class="bankView">
    <h2>{{ __('bank.title') }}</h2>
    <button type="button" class="btn-success" onclick="window.location='{{ url("/bankaccounts") }}'">{{ __('Back') }}</button>
    <form action="{{ route('tikkos.store') }}" method="post">
        @csrf
        <div class="card">
            <label for="title">{{ __('Title') }}</label>
            <input id="title" name="title" value="{{$request->title}}" readonly>
            <div class="card-body">
                <label for="description">{{ __('Note') }}</label>
                <input id="description" name="description" value="{{$request->description}}" readonly>

                <label for="bankAccount">{{ __('Bankaccount') }}</label>
                <input id="bankAccount" name="bankRekening" value="{{$request->bankRekening}}" readonly>

                <label for="currency">{{ __('Currency') }}</label>
                <input id="currency" name="currency" value="{{$request->valuta}}"  readonly>

                <label for="amount">{{ __('Amount') }}</label>
                <input id="amount" name="amount" value="{{$request->amount}}" readonly>

                <label for="tikko_date">{{ __('Date') }}</label>
                <input id="tikko_date" name="tikko_date" value="{{$request->date}}" readonly>

            </div>
        </div>

        <label for="receiverSelection">select receivers: </label>
        <select multiple class="form-control" id="receiverSelection"   >
            @foreach($receivers as $receiver)
                <option>{{$receiver->name}}</option>

            @endforeach
        </select>
        <button type="button" onclick="populateSelectedList()" class="btn-primary">Add to ReceiverList</button>
        <button type="button" onclick="populateUnSelectedList()" class="btn-primary">delete from ReceiverList</button>

{{--        <label for="unReceiverSelection">selected Receivers: </label>--}}
{{--        <select multiple class="form-control" id="unReceiverSelection" name="receivers"  >--}}
{{--        </select>--}}

        <label for="unReceiverSelection">selected Receivers: </label>


        <select multiple class="form-control" id="unReceiverSelection">
        </select>
        <button type="submit" class="btn btn-primary" name="submit" value={{$receiverCategory}}>{{ __('Send Tikko') }}</button>
    </form>






<script>
    function populateSelectedList() {
        let receiverSelection = document.getElementById('receiverSelection').selectedOptions;
        let unReceiverSelection = document.getElementById('unReceiverSelection');
        // for each selected person
        for (let i = 0; i < receiverSelection.length; i++) {
            if (!nameDoesExist(receiverSelection[i].text, unReceiverSelection.children)) {
                let option = document.createElement('OPTION');
                option.setAttribute('id', receiverSelection[i].text);
                let name = "receiver_"+unReceiverSelection.getElementsByTagName('OPTION').length;
                option.innerHTML = `<input type="hidden" name=${name} value=${receiverSelection[i].text}> ${receiverSelection[i].text}`;
                unReceiverSelection.appendChild(option);
            } else {
                return alert("selected person already selected")
            }

        }
    }


        function nameDoesExist(name, selection){
            let options = selection;
            for(let i = 0; i< options.length;i++){
                if(name === options[i].id){
                return true;
                }
            }
            return false;
            }


    function populateUnSelectedList(){
        let unReceiverSelection = document.getElementById('unReceiverSelection').selectedOptions;
        let unSelection = document.getElementById('unReceiverSelection');
        //for each unselected person
        for(let i = 0; i< unReceiverSelection.length;i++){
            unSelection.removeChild(unReceiverSelection[i]);
        }

    }
</script>


</div>



@endsection

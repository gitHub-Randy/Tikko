<?php

namespace App\Http\Controllers;

use App\BankAccount;
use App\Group;
use App\GroupMember;
use App\Payment;
use App\Tikko;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class TikkoController extends Controller
{
    public function index(){


        $id = Auth::id();

        $tikkos = Tikko::select('tikkos.id AS tikko_id', 'tikkos.name AS tikko_name', 'tikkos.currency AS tikko_currency',  'payments.tikko_id AS payment_tikkoId', 'payments.payer_id AS payment_payerId', 'payments.payed AS isPayed', 'tikkos.amount AS amount', 'payments.updated_at AS date')
            ->where('user_id', $id)
            ->join('payments', 'tikkos.id','payments.tikko_id' )
            ->where('payments.payed', 0)
            ->orderBy('tikko_date', 'DESC')
            ->get();

        foreach ($tikkos as $t){
            if(app()->getLocale() == 'nl'){
                $t->amount =  number_format( $t->amount, 2, ',', '.');
                $t->tikko_date = date("d-m-Y", strtotime($t->tikko_date));

            }else{
                $t->amount = number_format( $t->amount, 2, '.', ',');
                $t->tikko_date = date("m/d/Y", strtotime($t->tikko_date));

            }

//        $tikkos = Tikko::where('user_id', $id)->orderBy('tikko_date', 'DESC')->get();
//        foreach ($tikkos as $t){
//            if(app()->getLocale() == 'nl'){
//                $t->amount =  number_format( $t->amount, 2, ',', '.');
//                $t->tikko_date = date("d-m-Y", strtotime($t->tikko_date));
//
//            }else{
//                $t->amount = number_format( $t->amount, 2, '.', ',');
//                $t->tikko_date = date("m/d/Y", strtotime($t->tikko_date));
//
//            }
        }
        return view('Tikkos.tikkos',compact('tikkos'));

    }

    function create(){
        $user_id = Auth::id();
        $bankAccounts = BankAccount::where('user_id', $user_id)->get();
        foreach ($bankAccounts as $acc) {
            $acc->account_number = Crypt::decrypt($acc->account_number);
        }
        return view('tikkos.addTikko', compact('bankAccounts'));
    }



    public function confirm(Request $request){
        if(app()->getLocale() == 'nl'){
            $validatedData = $request->validate([
                'date' => 'required|date_format:d-m-Y',
                'title' => 'required',
                'amount' => 'required|regex: /^(\d+(?:[\,]\d{2})?)$/',
                'description' => 'required'

            ]);

        }else{
            $validatedData = $request->validate([
                'date' => 'required|date_format:m/d/Y',
                'title' => 'required',
                'amount' => 'required|regex: /^(\d+(?:[\.]\d{2})?)$/',
                'description' => 'required'
            ]);
        }

        $request->amount = str_replace(array(".", ","), array(",", "."), $request->amount);
        $receiverCategory = null;
        if(app()->getLocale() == 'nl'){
            $request->amount =  number_format( $request->amount, 2, ',', '.');
        }else{
            $request->amount = number_format( $request->amount, 2, '.', ',');
        }
      if($request->submit == 'TikkoOne'){

          $receivers = User::all();
          $receiverCategory = "person";
        return view('tikkos.confirmTikko', compact('request','receivers', 'receiverCategory'));
      }else{
          $receivers = Group::all();
          $receiverCategory = "group";


          return view('tikkos.confirmTikko', compact('request','receivers','receiverCategory'));
      }


    }

    public function store(Request $request)
    {
        // get all bankaccounts with the user id of the current user
        // foreach account: decrypt it and compare the account id with the account id from the request and store it
        $accs = BankAccount::where('user_id',Auth::id())->get();
        $acc_id = null;
        foreach ($accs as $a){
            if(Crypt::decrypt($a->account_number) == $request->bankRekening){
                $acc_id = $a->account_id;
            }
        }

        // make a new tikko and store it in the db
        $request->amount = str_replace(array(","), array( "."), $request->amount);
        $request->tikko_date = date("Y-m-d", strtotime($request->tikko_date));

        $newTikko = new Tikko(
            [
                'user_id' => Auth::id(),
                'name' => $request->title,
                'description' => $request->description,
                'amount' => $request->amount,
                'currency' => $request->currency,
                'account_id' => $acc_id,
                'tikko_date' => $request->tikko_date
            ]
        );
        $newTikko->save();
        /*
        if user wants to send tikko to one or more users:
        filter all receiver names from the request
        get  the tikko id of the last added tikko
        get the user id's of the receiver using the receiver names of the request
        make a new Payment with the tikkoId,payerId(userId) and set payed to 0(false)
        store the payment in the database;
        */
        if ($request->submit == "person"){
            for ($i = 0; $i<count($request->all());$i++) {

                $key = "receiver_$i";
                $tikkoId = Tikko::select('id')->orderBy('id', 'DESC')->first();
                $payerId = User::select('id')->where('name', $request->$key)->get();

                if ($request->has($key)) {
                    $payment = new Payment([
                        'tikko_id' => $tikkoId->id,
                        'payer_id' => $payerId[0]->id,
                        'payed' => 0

                    ]);
                    $payment->save();
                }
            }
        }
        /*
        if user wants to send tikko to a group
        get groupId
        get users of group
        for each user make a new payment and save in db

        */
        else{
            for ($g = 0; $g<count($request->all());$g++) {

                $key = "receiver_$g";
                if ($request->has($key)) {
                    $group = Group::where('name', $request->$key)->first();
                    $group_users = GroupMember::where('group_id',$group->id)->get();

                    $tikkoId = Tikko::select('id')->orderBy('id', 'DESC')->first();

                    foreach ($group_users as $gu){
                        $payment = new Payment([
                            'tikko_id' => $tikkoId->id,
                            'payer_id' => $gu->user_id,
                            'payed' => 0

                        ]);
                        $payment->save();
                    }
                }else{
                    return $this->index();
                }
            }
        }
         return redirect()->action('TikkoController@index');

    }



    //details
    public function show($id)
    {
        $user = Auth::id();
        $tikko = Tikko::where('id', '=', $id)->first();
        if(app()->getLocale() == 'nl'){
            $tikko->amount =  number_format( $tikko->amount, 2, ',', '.');
        }else{
            $tikko->amount = number_format( $tikko->amount, 2, '.', ',');
        }
        $bankAccount = BankAccount::where('account_id','=',$tikko->account_id)->first();
        $bankAccount->account_number = Crypt::decrypt($bankAccount->account_number);
        $payments = Payment::where('tikko_id', '=', $tikko->id)->get();
        $payers = [];
        foreach ($payments as  $p){
            array_push($payers, User::where('id',$p->payer_id)->first());
        }
        return view('tikkos.showTikko', compact('user','tikko','bankAccount','payments','payers'));

    }

    public function destroy($id)
    {
        $tikko = Tikko::where('id', $id)->first();
        $payments = Payment::where('tikko_id' ,'=',$tikko->id);

        $isTikkoPaid = false;
        foreach ($payments as $p){
            if($p->payed == '1') {
                $isTikkoPaid = true;
            }
        }
        if($isTikkoPaid == false) {
            foreach ($payments as $p){
                $payment = Payment::where('tikko_id', $p->tikko_id)->where('payer_id', $p->payer_id)->get();

                $payment->delete();
            }
            $tikko->delete();
        }else{
            alert("voor deze tikko zijn al betaligne nbinnegkomen");
        }



        return $this->index();

    }
}

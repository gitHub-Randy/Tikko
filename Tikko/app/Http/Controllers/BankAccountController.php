<?php

namespace App\Http\Controllers;

use App\BankAccount;
use App\Tikko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use NumberFormatter;

class BankAccountController extends Controller
{

    public function index(){
        $userId = Auth::id();
        $account = BankAccount::select('account_id')->where('user_id', $userId)->get();
        $BankAccount = BankAccount::findmany($account)->where('user_id', '=', $userId);


        foreach ($BankAccount as $b) {
            $b->account_number = Crypt::decrypt($b->account_number);
            if(app()->getLocale() == 'nl'){
               $b->balance =  number_format($b->balance, 2, ',', '.');

            }else{
                $b->balance = number_format($b->balance, 2, '.', ',');
            }
        }

        $acc = json_decode($BankAccount);
        return view('bankAccount.bankaccounts')->with("accounts", $acc);

    }

    function create(){
        return view('bankAccount.AddBankAccount');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'account_number' => 'required',
            ]
        );
        $acc = new BankAccount(
            [
                'account_number' => Crypt::encrypt($request->get('account_number')),
                'user_id' => Auth::id(),
                'balance' => number_format(100.00, 2),

            ]
        );
        $acc->save();
        return redirect()->action('BankAccountController@index');
    }

    public function edit($id)
    {
        $account = BankAccount::find($id);

        $account->account_number = Crypt::decrypt($account->account_number);


        return view('bankAccount.editBankAccount', compact('account'));
    }

    public function update(Request $request, $id)
    {

        $request->validate(
            [
                'account_number' => 'required',
            ]
        );

        $account = BankAccount::find($id);
        $account->account_number = Crypt::encrypt($request->get('account_number'));

        $account->save();

        return redirect()->action('BankAccountController@index');
    }


    public function show($id)
    {
        $acc = BankAccount::where('account_id', $id)->first();


        $tikkos = Tikko::select('tikkos.id AS tikko_id', 'tikkos.name AS tikko_name', 'tikkos.currency AS tikko_currency', 'users.name AS user_name', 'payments.tikko_id AS payment_tikkoId', 'payments.payer_id AS payment_payerId', 'payments.payed AS isPayed', 'tikkos.amount AS amount', 'payments.updated_at AS date', 'note.note AS note')
        ->where('tikkos.account_id', $acc->account_id)
        ->join('payments', 'tikkos.id','payments.tikko_id' )
            ->join('users', 'users.id',  'payments.payer_id')
            ->join('note', 'note.tikko_id', 'tikkos.id')
            ->where('tikkos.account_id', $acc->account_id)
            ->where('payments.payed', 1)
           ->get();
        if(app()->getLocale() == 'nl'){
            $acc->balance =  number_format($acc->balance, 2, ',', '.');
            foreach ($tikkos as $t){
                $t->amount =  number_format($t->amount, 2, ',', '.');
                $t->date = date("d-m-Y", strtotime($t->date));

            }
        }else{
            $acc->balance = number_format($acc->balance, 2, '.', ',');
            foreach ($tikkos as $t){
                $t->amount =  number_format($t->amount, 2, '.', ',');
                $t->date = date("m/d/Y", strtotime($t->date));

            }
        }

        return view('bankAccount.viewBankAccount', compact('tikkos', 'acc'));
    }

    public function destroy($id)
    {

        $account = BankAccount::find($id);

        if ($account != null) {
            $account->delete();

        }
        return $this->index();
    }



}

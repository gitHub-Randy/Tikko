<?php

namespace App\Http\Controllers;

use App\Note;
use App\Payment;
use App\Tikko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mollie\Api\Exceptions\ApiException;
use Mollie\Api\MollieApiClient;
use Mollie\Laravel\Facades\Mollie;
use Swap\Builder;


class PaymentController extends Controller
{

    public function index(){
        $user_id = Auth::id();
        $tikkos = Tikko::select('tikkos.id AS t_id', 'tikkos.name AS t_name','tikkos.description AS t_desc', 'tikkos.currency AS t_curr', 'tikkos.amount AS t_amount', 'users.name AS u_name', 'payments.tikko_id AS p_tId', 'payments.payer_id AS p_pId', 'tikkos.tikko_date AS t_date')
            ->join('payments', 'payments.tikko_id', '=', 'tikkos.id')
            ->join('users', 'users.id', '=', 'tikkos.user_id')
            ->where('payments.payer_id', $user_id)
            ->where('payments.payed', 0)
            ->get();
        foreach ($tikkos as $t){
            if(app()->getLocale() == 'nl'){
                $t->t_amount =  number_format( $t->t_amount, 2, ',', '.');
            }else{
                $t->t_amount = number_format( $t->t_amount, 2, '.', ',');
            }
        }


        return view('Payments.payments',compact('tikkos'));
    }


    public function pay(Request $request){
        $tikko_data = $request;

        return view('Payments.preparePayment', compact('tikko_data'));



    }
// TODO: dynamic curency
    public function prepare(Request $request){
        $mollie = new MollieApiClient();
        $request->amount = number_format( (float)$request->amount, 2, '.', ',');
        $localString = "";
        if(app()->getLocale() == 'nl'){
            $localString = "nl_NL";
        }else{
            $localString = "en_US";
        }
        try {
            $mollie->setApiKey("test_DyhVSrAnyxUJa96yPU7nvrxWTSS3WE");
        } catch (ApiException $e) {
        }
        try {
            $payment = $mollie->payments->create([
                "amount" => [
                    "currency" => $request->currency,
                    "value" => $request->amount
                ],
                "description" => $request->description,
                "redirectUrl" => route('tikkos.index'),
                "webhookUrl" => route('webhook'),
                "locale" => $localString,
                "metadata" => [
                    "user"=>Auth::id(),
                    "tikko_id" => $request->tikkoId,
                    "note" => $request->note
                ]
            ]);

            $p = $mollie->payments->get($payment->id);
            Payment::where('payer_id', Auth::id())->where('tikko_id', $request->tikkoId)->update(['payment_id' => $p->id]);

            return redirect($p->getCheckoutUrl(), 303);
        } catch (ApiException $e) {
            return dd($e);
        }


    }


    public function MollieHook(Request $request)
    {
        $mollie = new MollieApiClient();
        try {
            $mollie->setApiKey("test_DyhVSrAnyxUJa96yPU7nvrxWTSS3WE");
            $payment = $mollie->payments->get($request['id']);
            if ($payment->isPaid()) {
               Payment::where("payment_id", "=", $payment->id)->update(['payed' => 1]);
               $p = Payment::where("payment_id", "=", $payment->id)->first();
                $note = new Note();
                $note->tikko_id = $payment->metadata->tikko_id;
                $note->note = $payment->metadata->note;
                $note->save();
                $swap = (new Builder())

                    // Use the Fixer.io service as first level provider
                    ->add('fixer', ['access_key' => '2edcb20696ef55624730ce23780be4bf'])

                    // Use the currencylayer.com service as first fallback
                    ->add('currency_layer', ['access_key' => '6433a802eeb920dda452adb9d7f62e8e', 'enterprise' => false])

                    ->build();
                $rateString = $payment->amount->currency."/EUR";
                $rate = $swap->latest("$rateString");



                $acc = $p->tikko->first()->bankaccount;
                $balance = $acc->balance;
                $payedAmount = (int)$payment->amount->value;
                $rateValue  = (float)$rate->getValue();
                $convertedAmount = $payedAmount*$rateValue;

                $newBalance = $balance += $convertedAmount;
                $newBalance = number_format($newBalance, 2);

                $p->tikko->bankaccount->update(['balance' => $p->tikko->bankaccount->balance = $newBalance]);
                return "Payment received.";
            }
        } catch (ApiException $e) {
            return dd($e);

        }

        return dd("yeet");


    }


    function create(){

    }

    public function store(Request $request)
    {

    }

    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {

    }


    public function show($id)
    {

    }

    public function destroy($id)
    {


    }

}


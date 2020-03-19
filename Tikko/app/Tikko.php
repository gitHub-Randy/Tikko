<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Tikko extends Model
{
    protected $table = 'tikkos';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'user_id', 'name', 'description', 'currency', 'amount', 'account_id', 'tikko_date'];

    public static function isAllowed($tikko_id)
    {
        $tikko = Tikko::find($tikko_id);
        if (!empty($tikko) && isset($tikko->name)) {
            foreach ($tikko->payments as $payment) {
                if ($payment->payer_id == Auth::user()->id) return true;
            }
        }
        return false;
    }

    public function user()
    {
        return $this->belongsTo("App\User");
    }

    public function payments()
    {
        return $this->hasMany("App\Payment", "tikko_id");
    }

    public function bankaccount()
    {
        return $this->belongsTo("App\BankAccount", "account_id");
    }

    public function hasPaymentOpen()
    {
        foreach ($this->payments as $payment) {
            if ($payment->payer_id == Auth::user()->id)
                if ($payment->payed) return true;
                else return false;
        }
        return false;
    }

    public function searchRelevant($search)
    {
        if (strpos(strtolower($this->name), strtolower($search)) !== false) return true;
        else if (strpos(strtolower($this->description), strtolower($search)) !== false) return true;
        else if (strpos(strtolower($this->amount), strtolower($search)) !== false) return true;
        else if (strpos(strtolower($this->user->name), strtolower($search)) !== false) return true;
        else return false;
    }
}

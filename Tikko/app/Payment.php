<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public $incrementing = false;
    protected $table = 'payments';
    protected $primaryKey = ['tikko_id', 'payer_id'];
    protected $fillable = ['tikko_id', 'payer_id', 'payment_id', 'payed'];

    public function tikko()
    {
        return $this->belongsTo("App\Tikko", "tikko_id");
    }

    public function payer()
    {
        return $this->belongsTo("App\User");
    }
}

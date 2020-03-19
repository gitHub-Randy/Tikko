<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $table = 'note';
    protected $primaryKey = 'tikko_id';
    protected $fillable = ['tikko_id', 'note'];

    public $timestamps = false;
    public $incrementing = false;

    public function tikko(){
        return $this->belongsTo("App\Tikko");
    }

}

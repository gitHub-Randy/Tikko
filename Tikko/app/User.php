<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function getUsers($filter)
    {
        if (strlen($filter) <= 0) return;
        $f = "";
        $arr = str_split($filter);
        for ($i = 0; $i < count($arr); $i++) {
            $f .= "%" . $arr[$i];
        }
        $f .= "%";
        return User::where("name", "LIKE", $f)->where("id", "<>", Auth::user()->id)->get();
    }

    public static function getName($id)
    {
        $user = User::find($id);
        if (!empty($user)) return $user->name;
        else return "";
    }

    public function tikkos()
    {
        return $this->hasMany("App\Tikko");
    }

    public function payments()
    {
        return $this->hasMany("App\Payment", "payer_id");
    }

    public function bank_accounts()
    {
        return $this->hasMany("App\BankAccount");
    }

    public function groups()
    {
        return $this->hasMany("App\Group");
    }
}

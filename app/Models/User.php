<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyMobile;
use App\Interfaces\MustVerifyMobile as IMustVerifyMobile;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

 // you must add " implements IMustVerifyMobile " for mobile vrification
  // you must add " implements MustVerifyEmail " for email vrification

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;
    //use MustVerifyMobile; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'mobile_verify_code',
        'mobile_attempts_left',
        'mobile_last_attempt_date',
        'mobile_verify_code_sent_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'number_verified_at' => 'datetime',
        'mobile_verify_code_sent_at' => 'datetime',
    ];
    public function routeNotificationForVonage( Notification $notification)
    {
        return $this->mobile_number;
    }

    function user_address()
    {
        return $this->hasMany(UserAddress::class);
    }
    public static function getuser()
    {
        if(Auth::user())
        {
            return User::where('id', Auth::user()->id)->get('Pagedata') ;

        }
    }
    
}

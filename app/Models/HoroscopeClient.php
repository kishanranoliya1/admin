<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class HoroscopeClient extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fname',
        'lname',
        'mobile_no',
        'email_id',
        'client_ip',
        'country_id',
        'image',
        'device_token',
        'password',
        'mobile_verified',
        'token',
        'token_validupto',
        'api_key',
        'source_url',
        'source_ip',
        'bypass_ip',
        'trial_end_date',
        'subscription_type',
        'subscription_amount',
        'subscription_end_date',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'registered_on',
        'profile_complete',
        'acnt_status',
        'customer_id',
        'request_api_id',
        'first_subscription',
        'client_type',
        'authorized_website',
        'api_acc_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        // 'remember_token',
    ];
    
    public static function get_user_by_api_key($api_key) {

        return self::select('*')->where('api_key', $api_key)->first();

    }

    public static function get_user_by_source_url($domain, $client_id) {

        return self::select('*')
                    ->where('source_url', 'LIKE', '%' . $domain . '%')
                    ->where('id', $client_id)
                    ->first();

    }

    public static function get_user_by_source_ip($ip, $client_id) {

        return self::select('*')
                    ->where('source_ip', $ip)
                    ->where('id', $client_id)
                    ->first();

    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

}

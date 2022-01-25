<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public static function booted(): void
    {
        static::creating(function ($obj) {
            $obj->credential = $obj->credential ? $obj->credential : sha1(password_hash(time(), PASSWORD_DEFAULT));
            $obj->secret = sha1(password_hash($obj->secret ?: time(), PASSWORD_DEFAULT));
        });

        static::created(function ($obj) {
            $account = AgencyAccount::create([]);

            \App\Models\Account::factory()->create([
                'user_id' => $obj->id,
                'name' => "CONTA DIGITAL S.A.",
                'value' => 0,
                'bank_code' => '0999',
                'bank_agency' => str_pad(1, 4, "0", STR_PAD_LEFT),
                'bank_account' => str_pad($account->id, 7, "0", STR_PAD_LEFT),
                'bank_digit' => rand(0, 9),
                'can_deleted' => false,
            ]);
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
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
    ];
}

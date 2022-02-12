<?php

namespace App\Models;

use App\Services\RecurrencyService;
use Costa\LaravelPackage\Traits\Models\UuidGenerate;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    private static $CACHE_TOKEN = 'v1';

    use HasApiTokens, HasFactory, Notifiable, UuidGenerate;

    public static function booted(): void
    {
        static::created(fn ($obj) => app(RecurrencyService::class)->register($obj));
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
        'balance_value',
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

    public function getTokenRelatorio()
    {
        return Cache::remember('user_token_relatorio_' . $this->uuid . '_' . self::$CACHE_TOKEN, 60 * 60, function () {
            $this->tokens()->where('name', 'acesso_relatorio')->delete();
            return $this->createToken('acesso_relatorio', ['relatorio:home']);
        });
    }

    public function getLoginCustomer()
    {
        return Cache::remember('user_token_custoemr_' . $this->uuid . '_' . self::$CACHE_TOKEN, 60 * 60, function () {
            $this->tokens()->where('name', 'acesso_relatorio')->delete();
            return $this->createToken('search_customer');
        });
    }

    public function getSharedIdUser(): array
    {
        $ret = [
            $this->id,
        ];

        return $ret;
    }
}

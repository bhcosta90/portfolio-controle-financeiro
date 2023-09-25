<?php

namespace App\Models;

use App\Exceptions\AccountException;
use App\Models\Charge\Charge;
use App\Models\Charge\Payment;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extract extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'value',
        'account_id',
        'account_version',
        'charge_type',
    ];

    protected static function booted()
    {
        parent::booted();

        static::creating(function ($obj) {
            if ($obj->account->version == $obj->account_version) {
                $obj->user_id = $obj->user_id ?: auth()->user()->id;

                $value = match ($obj->charge_type) {
                    Payment::class => $obj->value * -1,
                    default => $obj->value,
                };

                $obj->account->balance = $obj->account->balance + $value;
                $obj->account->save();
            } else {
                throw new AccountException();
            }
        });

        static::deleting(function ($obj) {
            if ($obj->account->version == $obj->account_version) {
                $value = match ($obj->charge_type) {
                    Payment::class => $obj->value,
                    default => $obj->value * -1,
                };
                $obj->account->balance = $obj->account->balance + $value;
                $obj->account->save();
            } else {
                throw new AccountException();
            }
        });
    }

    public function model()
    {
        return $this->morphOne(Charge::class, 'model');
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}

<?php

namespace App\Models;

use Costa\LaravelPackage\Traits\Models\UuidGenerate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserShared extends Model
{
    use HasFactory, UuidGenerate, SoftDeletes;

    public static $STATUS_PENDING = 'PE';
    public static $STATUS_ACCEPT = 'AC';
    public static $STATUS_NOT_ACCEPT = 'NA';

    public static function getStatusAttribute($status = null)
    {
        $ret = [
            self::$STATUS_PENDING => 'Pending',
            self::$STATUS_ACCEPT => 'Accept',
            self::$STATUS_NOT_ACCEPT => 'Not accept',
        ];

        if (!empty($status)) {
            return $ret[$status] ?? null;
        }

        return $ret;
    }
}

<?php

namespace App\Models;

use Costa\LaravelPackage\Traits\Models\UuidGenerate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Charge extends Model
{
    use HasFactory, SoftDeletes, UuidGenerate;

    public static $statusOptions = [
        'PE' => 'Pending',
        'PA' => 'Payed',
        'CA' => 'Canceled',
        'SY' => 'In syncronyzed',
    ];

    const STATUS_PENDING = 'PE';

    public static $typeOptions = [
        'month' => 'Month',
        'twoweek' => 'Two week',
        'week' => 'Week',
        'fifth_business_day' => 'Fifth business day',
        'every_20th' => 'Every 20th',
        'every_last_day' => 'Every last day',
    ];

    protected $fillable = [
        'user_id',
        'chargeable',
        'value',
        'value_recursive',
        'customer_name',
        'due_date',
        'last_date',
        'parcel_actual',
        'parcel_total',
        'type',
        'status',
        'future',
        'resume',
        'description',
    ];

    protected $casts = [
        'value' => 'float',
        'value_recursive' => 'float',
        'future' => 'boolean',
        'parcel_actual' => 'integer',
        'parcel_total' => 'integer',
    ];

    public function chargeable()
    {
        return $this->morphTo();
    }
}

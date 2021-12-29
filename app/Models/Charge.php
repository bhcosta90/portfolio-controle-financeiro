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

    public static $typeOptions = [
        'month' => 'Month',
        'twoweek' => 'Two week',
        'week' => 'Week',
        'fifth_business_day' => 'Fifth business day',
        'every_20th' => 'Every 20th',
        'every_fifth_business_day' => 'Every fifth business day',
    ];
}
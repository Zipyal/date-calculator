<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DateCalculation extends Model
{
    protected $fillable = [
    'start_date',
    'end_date',
    'days_difference',
    'user_ip',
];

    protected $casts = [
    'start_date' => 'date',
    'end_date' => 'date',
];
}

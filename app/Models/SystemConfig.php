<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemConfig extends Model
{
    // Define which fields can be filled
    protected $fillable = [
        'key',
        'value',
        'month_year'
    ];
}

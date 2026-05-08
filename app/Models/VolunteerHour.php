<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VolunteerHour extends Model
{
    protected $fillable = [
        'user_id',
        'shift_id',
        'hours'
    ];
}
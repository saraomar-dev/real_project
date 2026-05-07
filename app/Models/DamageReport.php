<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DamageReport extends Model
{
    protected $fillable = [
    'user_id',
    'tool_id',
    'description',
    'image',
    'fine',
    'status'
];
}

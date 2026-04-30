<?php

namespace App\Models;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Seed extends Model
{
    use Auditable;
    protected $fillable = [
        'name',
        'quantity',
        'password',
        'expiry_date',
    ];
}

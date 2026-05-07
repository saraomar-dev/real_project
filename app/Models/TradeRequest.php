<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradeRequest extends Model
{
    protected $fillable = [

        'listing_id',

        'requester_id',

        'status'

    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketplaceListing extends Model
{
    protected $fillable = [
    'product_name',
    'quantity',
    'description',
    'deadline',
    'user_id'
];
public function requests()
{
    return $this->hasMany(\App\Models\TradeRequest::class,'listing_id');
}
}

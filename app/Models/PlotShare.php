<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlotShare extends Model
{
    protected $fillable = [
        'plot_id',
        'user_id',
        'shared_with',
        'status'
    ];

    public function plot()
    {
        return $this->belongsTo(Plot::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sharedWithUser() {
    return $this->belongsTo(User::class, 'shared_with');
}
}
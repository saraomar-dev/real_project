<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoilRecord extends Model
{
    protected $fillable = ['plot_id', 'ph_level', 'fertilizer_type', 'crop_type', 'notes', 'record_date'];


    public function plot()
{
    // السجل ينتمي لأرض معينة
    return $this->belongsTo(Plot::class);
}
}



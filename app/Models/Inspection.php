<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    use HasFactory;

    // الخانات اللي مسموح للسيستم يملأها أوتوماتيك
    protected $fillable = [
        'plot_id',
        'user_id', // أو warden_id حسب اللي موجود في الـ Migration
        'status',
        'notes',
        'has_pests',
    ];

    // علاقة المعاينة بالأرض
    public function plot()
    {
        return $this->belongsTo(Plot::class);
    }
}
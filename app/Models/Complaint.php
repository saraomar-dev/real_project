<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'plot_id',
        'user_id',
        'title',
        'description',
        'status',
    ];

    // علاقة الشكوى باليوزر (المزارع)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // علاقة الشكوى بالأرض
    public function plot()
    {
        return $this->belongsTo(Plot::class);
    }
}
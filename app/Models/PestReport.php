<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PestReport extends Model
{
    protected $fillable = ['plot_id', 'user_id', 'pest_type', 'description', 'status'];

    // 1. علاقة البلاغ بالأرض (عشان السيستم يعرف البلاغ ده تبع أي أرض)
    public function plot(): BelongsTo
    {
        return $this->belongsTo(Plot::class);
    }

    // 2. علاقة البلاغ بالمستخدم (عشان السيستم يعرف مين اللي بلغ)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
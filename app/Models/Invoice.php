<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Invoice extends Model
{
    protected $fillable = ['user_id', 'plot_id', 'amount', 'status', 'due_date'];

    // الفاتورة تابعة لمستخدم (المستأجر)
    public function user() {
        return $this->belongsTo(User::class);
    }

    // الفاتورة تابعة لأرض معينة
    public function plot() {
        return $this->belongsTo(Plot::class);
    }
}


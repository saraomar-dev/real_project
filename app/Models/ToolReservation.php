<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToolReservation extends Model
{
    protected $fillable=[
        'tool_id',
        'user_name',
        'reservation_date',
    ];
    public function tool(){
        return $this->belongsTo(Tool::class);
    }
}

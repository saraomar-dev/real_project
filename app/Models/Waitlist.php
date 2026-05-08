<?php


namespace App\Models;
  use App\Models\User;


use Illuminate\Database\Eloquent\Model;

class Waitlist extends Model
{
   protected $fillable = ['user_id', 'status', 'plot_id'];

 
public function user()
{
    return $this->belongsTo(User::class);
}
}

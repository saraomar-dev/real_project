<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
        'date',
        'time',
        'required_users'
    ];
    public function users()

    {

        return $this->belongsToMany(User::class);

    }
    public function volunteerHours()
{
    return $this->hasMany(VolunteerHour::class);
}
}
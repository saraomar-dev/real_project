<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// شلنا سطر الـ Sanctum من هنا

class User extends Authenticatable
{
    // شلنا HasApiTokens من السطر اللي تحت ده
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'karma',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // علاقات منة (الأراضي والشكاوى)
    public function plots() {
        return $this->hasMany(Plot::class);
    }

    public function complaints() {
        return $this->hasMany(Complaint::class);
    }

    public function plotShares() {
        return $this->hasMany(PlotShare::class, 'shared_with');
    }

    // علاقات روان والتيم (الأدوات والمهام والساعات)
    public function tasks() {
        return $this->hasMany(Task::class);
    }

    public function volunteerHours() {
        return $this->hasMany(VolunteerHour::class);
    }

    public function toolReservations() {
        return $this->hasMany(ToolReservation::class);
    }

    public function shifts() {
        return $this->belongsToMany(Shift::class);
    }

    public function ratings() {
        return $this->hasMany(Rating::class, 'rated_user_id');
    }


    // دالة للتحقق إذا كان المستخدم أدمن
public function isAdmin()
{
    return $this->role === 'admin';
}

// بالمرة ضيفي دي لو احتجتيها للواردن قدام
public function isWarden()
{
    return $this->role === 'warden';
}
}
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\Auditable;

class User extends Authenticatable
{



const ROLE_ADMIN = 'admin';
const ROLE_MEMBER = 'member';

public function isAdmin()
{
    return $this->role === self::ROLE_ADMIN;
}
    
    use Auditable;
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
  
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    // المستخدم ممكن يأجر أكتر من أرض
public function plots()
{
    // اليوزر الواحد ممكن يكون عنده كذا أرض (علاقة One-to-Many)
    return $this->hasMany(Plot::class, 'user_id');
}

// الواردن (كمستخدم) ممكن يعمل معاينات كتير
public function inspections() {
    return $this->hasMany(Inspection::class);
}

public function isWarden()
{
    return $this->role === 'warden';
}


public function sharedPlots() {
    return $this->hasMany(PlotShare::class, 'shared_with');
}


}

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Lease;

class Plot extends Model {
    use HasFactory;

   
   protected $fillable = [
    'plot_number', 
    'area_sqm', 
    'soil_quality', 
    'sunlight_exposure', 
    'status', 
    'image', 
    'is_available', 
    'location_tag', 
    'user_id', 
    'price' // الضيف الجديد اللي هيحل مشكلة الصفر
];
    
    public function leases() {
        return $this->hasMany(Lease::class);
    }
    public function owner() {
    return $this->belongsTo(User::class, 'user_id');
}

public function soilRecords()
{
    return $this->hasMany(SoilRecord::class, 'plot_id');
}

public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

// كل أرض ممكن يكون ليها معاينات كتير من الواردن
public function inspections() {
    return $this->hasMany(Inspection::class);
}

// كل أرض فيها نوع بذور واحد حالياً
public function seed() {
    return $this->belongsTo(Seed::class);


    
}



public function shares() {
    return $this->hasMany(PlotShare::class);
}



 
}


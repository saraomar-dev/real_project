<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plot extends Model {
    use HasFactory;

   
    protected $fillable = [
        'plot_number',
        'area_sqm',
        'soil_quality',
        'sunlight_exposure',
        'status',
        'base_price'
    ];

    
    public function leases() {
        return $this->hasMany(Lease::class);
    }
}
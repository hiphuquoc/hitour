<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model {
    use HasFactory;
    protected $table        = 'region_info';

    public function tourLocations(){
        return $this->hasMany(\App\Models\TourLocation::class, 'region_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipBookingStatus extends Model {
    use HasFactory;
    protected $table        = 'ship_booking_status';
    protected $fillable     = [
        'name', 
        'color'
    ];
    public $timestamps      = false;

    public function relationAction(){
        return $this->hasMany(\App\Models\RelationShipStatusAction::class, 'ship_booking_status_id', 'id');
    }
}

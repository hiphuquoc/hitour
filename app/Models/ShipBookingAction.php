<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipBookingAction extends Model {
    use HasFactory;
    protected $table        = 'ship_booking_action';
    protected $fillable     = [
        'name'
    ];
    public $timestamps      = false;

    

}

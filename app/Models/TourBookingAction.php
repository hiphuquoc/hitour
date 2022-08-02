<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourBookingAction extends Model {
    use HasFactory;
    protected $table        = 'tour_booking_action';
    protected $fillable     = [
        'name'
    ];
    public $timestamps      = false;

    

}

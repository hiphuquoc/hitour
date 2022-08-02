<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourBookingStatus extends Model {
    use HasFactory;
    protected $table        = 'tour_booking_status';
    protected $fillable     = [
        'name', 
        'color'
    ];
    public $timestamps      = false;

    public function relationAction(){
        return $this->hasMany(\App\Models\RelationTourStatusAction::class, 'tour_booking_status_id', 'id');
    }
}

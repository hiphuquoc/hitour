<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationHotelBookingStatusAction extends Model {
    use HasFactory;
    protected $table        = 'relation_hotel_booking_status_action';
    protected $fillable     = [
        'booking_status_id',
        'booking_action_id'
    ];
    public $timestamps      = false;

    public function infoAction(){
        return $this->hasOne(\App\Models\HotelBookingAction::class, 'id', 'booking_action_id');
    }
}

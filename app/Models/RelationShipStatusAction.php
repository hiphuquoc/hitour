<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationShipStatusAction extends Model {
    use HasFactory;
    protected $table        = 'relation_ship_status_action';
    protected $fillable     = [
        'ship_booking_status_id',
        'ship_booking_action_id'
    ];
    public $timestamps      = false;

    public function action(){
        return $this->hasOne(\App\Models\ShipBookingAction::class, 'id', 'ship_booking_action_id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationTourStatusAction extends Model {
    use HasFactory;
    protected $table        = 'relation_tour_status_action';
    protected $fillable     = [
        'tour_booking_status_id',
        'tour_booking_action_id'
    ];
    public $timestamps      = false;

    public function action(){
        return $this->hasOne(\App\Models\TourBookingAction::class, 'id', 'tour_booking_action_id');
    }

}

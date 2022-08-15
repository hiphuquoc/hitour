<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipPartnerContact extends Model {
    use HasFactory;
    protected $table        = 'ship_partner_contact';
    protected $fillable     = [
        'partner_id', 
        'name',
        'address',
        'phone', 
        'zalo',
        'email',
        'default'
    ];
    public $timestamps      = false;

    // public static function getList($params = null){
    //     $paginate   = $params['paginate'] ?? null;
    //     $result     = self::select('*')
    //                     ->paginate($paginate);
    //     return $result;
    // }

    public static function getListByPartnerId($partnerId){
        $result         = null;
        if(!empty($partnerId)){
            $result     = ShipPartnerContact::select('*')
                                ->where('partner_id', $partnerId)
                                ->get();
        }
        return $result;
    }

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new ShipPartnerContact();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public static function updateItem($id, $params){
        $flag           = false;
        if(!empty($id)&&!empty($params)){
            $model      = ShipPartnerContact::find($id);
            foreach($params as $key => $value) $model->{$key}  = $value;
            $flag       = $model->update();
        }
        return $flag;
    }

    public static function deleteItem($id){
        $flag           = false;
        if(!empty($id)) $flag = ShipPartnerContact::find($id)->delete();
        return $flag;
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipPartner extends Model {
    use HasFactory;
    protected $table        = 'ship_partner';
    protected $fillable     = [
        'name', 
        'company_name', 
        'company_code', 
        'company_address',
        'company_hotline', 
        'company_email', 
        'company_logo',
        'created_by'
    ];
    public $timestamps      = true;

    public static function getList($params = null){
        $paginate   = $params['paginate'] ?? null;
        $result     = self::select('*')
                        ->with('contacts')
                        ->paginate($paginate);
        return $result;
    }

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new ShipPartner();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public static function updateItem($id, $params){
        $flag           = false;
        if(!empty($id)&&!empty($params)){
            $model      = ShipPartner::find($id);
            foreach($params as $key => $value) $model->{$key}  = $value;
            $flag       = $model->update();
        }
        return $flag;
    }

    public function seo() {
        return $this->hasOne(\App\Models\Seo::class, 'id', 'seo_id');
    }

    public function contacts(){
        return $this->hasMany(\App\Models\ShipPartnerContact::class, 'partner_id', 'id');
    }

    public function questions(){
        return $this->hasMany(\App\Models\QuestionAnswer::class, 'reference_id', 'id');
    }

}

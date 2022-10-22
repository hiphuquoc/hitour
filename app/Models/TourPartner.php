<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourPartner extends Model {
    use HasFactory;
    protected $table        = 'tour_partner';
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
        $paginate   = $params['paginate'] ?? 0;
        $result     = self::select('*')
                        ->when(!empty($params['search_name']), function($query) use($params){
                            $query->where('name', 'like', '%'.$params['search_name'].'%')
                                    ->orWhere('company_name', 'like', '%'.$params['search_name'].'%')
                                    ->orWhere('company_code', 'like', '%'.$params['search_name'].'%')
                                    ->orWhere('company_website', 'like', '%'.$params['search_name'].'%');
                        })
                        ->with('contacts')
                        ->orderBy('id', 'DESC')
                        ->paginate($params['paginate']);
        return $result;
    }

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new TourPartner();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public static function updateItem($id, $params){
        $flag           = false;
        if(!empty($id)&&!empty($params)){
            $model      = TourPartner::find($id);
            foreach($params as $key => $value) $model->{$key}  = $value;
            $flag       = $model->update();
        }
        return $flag;
    }

    public function contacts(){
        return $this->hasMany(\App\Models\TourPartnerContact::class, 'partner_id', 'id');
    }

}

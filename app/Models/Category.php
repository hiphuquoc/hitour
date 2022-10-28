<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Helpers\Url;

class Category extends Model {
    use HasFactory;
    protected $table        = 'category_info';
    protected $fillable     = [
        'name', 
        'description', 
        'seo_id',
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new Category();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public static function updateItem($id, $params){
        $flag           = false;
        if(!empty($id)&&!empty($params)){
            $model      = self::find($id);
            foreach($params as $key => $value) $model->{$key}  = $value;
            $flag       = $model->update();
        }
        return $flag;
    }

    public static function getAllCategoryByTree($list){
        /* lấy phần tử level 1 build cây thư mục category */
        $data           = [];
        if(!empty($list&&$list->isNotEmpty())){
            foreach($list as $l){
                if($l->seo->level==1) $data[] = self::buildParentChild($l, $list);
            }
        }
        return $data;
    }

    private static function buildParentChild($item, $arrayData){
        $item->child                = new \Illuminate\Database\Eloquent\Collection;
        foreach($arrayData as $data){
            if($item->seo_id==$data->seo->parent) {
                /* check đệ quy */
                $flagNext           = false;
                foreach($arrayData as $d) {
                    if($data->seo_id==$d->seo->parent){
                        $flagNext   = true;
                        break;
                    }
                }
                /* trả dữ liệu */
                if($flagNext==true){
                    $item->child[]  = self::buildParentChild($data, $arrayData);
                }else {
                    $item->child[]  = $data;
                }
            }
        }
        return $item;
    }

    public static function getArrayCategoryChildByIdSeo($idSeo){
        $result             = [];
        $tmp                = Category::select('*')
                                ->whereHas('seo', function($query) use($idSeo){
                                    $query->where('parent', $idSeo);
                                })
                                ->get();
        if(!empty($tmp)&&$tmp->isNotEmpty()){
            foreach($tmp as $t) {
                $result[]   = $t->id;
                $result     = array_merge($result, self::getArrayCategoryChildByIdSeo($t->seo->id));
            }
        }
        return $result;
    }

    public function seo() {
        return $this->hasOne(\App\Models\Seo::class, 'id', 'seo_id');
    }

    public function tourLocations() {
        return $this->hasMany(\App\Models\RelationCategoryInfoTourLocation::class, 'category_info_id', 'id');
    }

}
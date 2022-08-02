<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Helpers\Url;

class TourLocation extends Model {
    use HasFactory;
    protected $table        = 'tour_location';
    protected $fillable     = [
        'name', 
        'description',
        'content',
        'seo_id', 
        'district_id',
        'province_id',
        'region_id',
        'note'
    ];
    public $timestamps      = true;

    public static function getList($params = null){
        $paginate   = $params['paginate'] ?? null;
        $result     = self::select('*')
                        /* tìm theo tên */
                        ->when(!empty($params['search_name']), function($query) use($params){
                            $query->where('name', 'like', '%'.$params['search_name'].'%');
                        })
                        /* tìm theo vùng miền */
                        ->when(!empty($params['search_region']), function($query) use($params){
                            $query->where('region_id', $params['search_region']);
                        })
                        ->with('seo', 'files')
                        ->paginate($paginate);
        return $result;
    }

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new TourLocation();
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

    public static function getItemBySlug($slug = null){
        $result         = null;
        if(!empty($slug)){
            $result     = DB::table('seo')
                            ->join('tour_location', 'tour_location.seo_id', '=', 'seo.id')
                            ->select(array_merge(config('table.seo', 'table.tour_location')))
                            ->where('slug', $slug)
                            ->first();
        }
        return $result;
    }

    // public static function getInfoBySeoAlias($value){
    //     $result         = [];
    //     if(!empty($value)){
    //         $result     = DB::table('categories_info')
    //                     ->join('seo', 'seo.id', '=', 'categories_info.page_id')
    //                     ->select(array_merge(config('column.categories_info'), config('column.seo')))
    //                     ->where('seo.seo_alias', $value)
    //                     ->first();
    //     }
    //     return $result;
    // }

    // public static function getArrayCategoryChildById($idCate){
    //     $result         = [];
    //     if(!empty($idCate)){
    //         /* phần tử đầu tiên là Category cha */
    //         $result[]   = $idCate;
    //         $child1     = self::getInfoCategoryChildById($idCate);
    //         if(!empty($child1)){
    //             foreach($child1 as $c1){
    //                 /* cho phần tử con cấp tiếp theo (c1) vào mảng */
    //                 $result[]   = $c1['id'];
    //                 $child2     = self::getInfoCategoryChildById($c1['id']);
    //                 if(!empty($child2)){
    //                     /* cho phần tử con cấp tiếp theo (c2) vào mảng */
    //                     foreach($child2 as $c2) {
    //                         $result[]   = $c2['id'];
    //                         $child3     = self::getInfoCategoryChildById($c2['id']);
    //                         if(!empty($child3)){
    //                             foreach($child3 as $c3){
    //                                 $result[]   = $c3['id'];
    //                             }
    //                         }
    //                     }
    //                 } 
    //                 /* lấy 3 cấp category tiếp theo */
    //             }
    //         }
    //     }
    //     return $result;
    // }

    // private static function getInfoCategoryChildById($idCate){
    //     $result         = [];
    //     if(!empty($idCate)){
    //         $result     = self::select('*')
    //                         ->where('category_parent', $idCate)
    //                         ->get()
    //                         ->toArray();
    //     }
    //     return $result;
    // }

    // public static function getAllCategoryFullInfo(){
    //     $result = DB::table('categories_info')
    //                 ->join('seo', 'seo.id', '=', 'categories_info.page_id')
    //                 ->select(array_merge(config('column.categories_info'), config('column.seo')))
    //                 ->get()
    //                 ->toArray();
    //     return $result;
    // }

    // public static function getAllCategoryByTree(){
    //     /* Lấy danh sách category */
    //     $dataCategory   = Category::getAllCategoryFullInfo();
    //     /* Buil link full category */
    //     $result         = Url::buildFullLinkArray($dataCategory);
    //     /* lấy phần tử level 1 build cây thư mục category */
    //     $data           = [];
    //     foreach($result as $r){
    //         if($r->level===1) $data[] = Url::buildParentChild($r, $result);
    //     }
    //     return $data;
    // }

    // public static function getListCategoryByBlogId($idBlog){
    //     $result = [];
    //     if(!empty($idBlog)){
    //         $result     = DB::table('categories_info')
    //                         ->join('seo', 'seo.id', '=', 'categories_info.page_id')
    //                         ->join('relation_blog_category', 'relation_blog_category.category_info_id', '=', 'categories_info.id')
    //                         ->join('blogs_info', 'blogs_info.id', '=', 'relation_blog_category.blog_info_id')
    //                         ->select(array_merge(config('column.categories_info'), config('column.seo')))
    //                         ->where('blogs_info.id', $idBlog)
    //                         ->get()
    //                         ->toArray();
    //     }
    //     return $result;
    // }

    public function seo() {
        return $this->hasOne(\App\Models\Seo::class, 'id', 'seo_id');
    }

    public function files(){
        return $this->hasMany(\App\Models\SystemFile::class, 'attachment_id', 'id');
    }

    public function region(){
        return $this->hasOne(\App\Models\Region::class, 'id', 'region_id');
    }

    public function province(){
        return $this->hasOne(\App\Models\Province::class, 'id', 'province_id');
    }

    public function district(){
        return $this->hasOne(\App\Models\District::class, 'id', 'district_id');
    }

}

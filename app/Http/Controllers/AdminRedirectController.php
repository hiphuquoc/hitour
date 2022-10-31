<?php

namespace App\Http\Controllers;

use App\Models\Redirect;
use Illuminate\Http\Request;

class AdminRedirectController extends Controller {

    public function list(Request $request){
        $list           = Redirect::select('*')
                            ->orderBy('id', 'DESC')
                            ->get();
        return view('admin.redirect.list', compact('list'));
    }

    public function create(Request $request){
        $id             = 0;
        /* Message */
        $message        = [
            'type'      => 'danger',
            'message'   => '<strong>Thất bại!</strong> Có lỗi xảy ra, vui lòng thử lại'
        ];
        if(!empty($request->get('url_old'))&&!empty($request->get('url_new'))){
            $urlOld     = self::filterUrl($request->get('url_old'));
            $urlNew     = self::filterUrl($request->get('url_new'));
            /* kiểm tra trùng url cũ */
            $tmp        = Redirect::select('*')
                                    ->where('url_old', $urlOld)
                                    ->first();
            if(empty($tmp)){
                /* insert */
                $id         = Redirect::insertItem([
                    'url_old'   => $urlOld,
                    'url_new'   => $urlNew
                ]);
                if(!empty($id)){
                    /* Message */
                    $message        = [
                        'type'      => 'success',
                        'message'   => '<strong>Thành công!</strong> Đã thêm redirect mới'
                    ];
                }
            }else {
                /* Message */
                $message        = [
                    'type'      => 'danger',
                    'message'   => '<strong>Thất bại!</strong> Url này đã được chỉ định redirect trước đó'
                ];
            }
        }
        $request->session()->put('message', $message);
        return redirect()->route('admin.redirect.list', ['id' => $id]);
    }

    public function delete(Request $request){
        $flag       = false;
        if(!empty($request->get('id'))){
            $flag   = Redirect::find($request->get('id'))->delete();
        }
        echo $flag;
    }

    private static function filterUrl($input){
        $output     = null;
        if(!empty($input)){
            /* bỏ tên miền */
            $output     = str_replace(env('APP_URL'), '', $input);
            /* thêm / nếu không có */
            if(substr($output, 0, 1)!='/') $output = '/'.$output;
        }
        return $output;
    }
    
}

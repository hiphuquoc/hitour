<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\RegistryEmail;

class AjaxController extends Controller {

    public function buildTocContentSidebar(Request $request){
        $xhtml       = null;
        if(!empty($request->get('dataSend'))){
            $xhtml   = view('main.template.tocContentSidebar', ['data' => $request->get('dataSend')])->render();
        }
        echo $xhtml;
    }

    public function buildTocContentMain(Request $request){
        $xhtml       = null;
        if(!empty($request->get('dataSend'))){
            $xhtml   = view('main.template.tocContentMain', ['data' => $request->get('dataSend')])->render();
        }
        echo $xhtml;
    }

    public static function checkLoginAndSetShow(Request $request){
        $xhtmlModal             = '';
        $xhtmlButton            = '';
        $xhtmlButtonMobile      = '';
        $user = $request->user();
        $language               = $request->get('language') ?? 'vi';
        if(!empty($user)){
            /* đã đăng nhập => hiển thị button thông tin tài khoản */
            $xhtmlButton        = view('main.template.buttonLogin', ['user' => $user, 'language' => $language])->render();
            $xhtmlButtonMobile  = view('main.template.buttonLoginMobile', ['user' => $user, 'language' => $language])->render();
        }else {
            /* chưa đăng nhập => hiển thị button đăng nhập + modal */
            $xhtmlButton        = view('main.template.buttonLogin', ['language' => $language])->render();
            $xhtmlModal         = view('main.template.loginCustomerModal', ['language' => $language])->render();
            $xhtmlButtonMobile  = view('main.template.buttonLoginMobile', ['language' => $language])->render();
        }
        $result['modal']            = $xhtmlModal;
        $result['button']           = $xhtmlButton;
        $result['button_mobile']    = $xhtmlButtonMobile;
        return json_encode($result);
    }

    public static function registryEmail(Request $request){
        if(!empty($request->get('registry_email'))){
            $infoAlready    = RegistryEmail::select('*')
                                ->where('email', $request->get('registry_email'))
                                ->first();
            if(empty($infoAlready)){
                $idRegistryEmail    = RegistryEmail::insertItem([
                    'email'     => $request->get('registry_email')
                ]);
                if(!empty($idRegistryEmail)){
                    $result['type']     = 'success';
                    $result['title']    = 'Đăng ký email thành công!';
                    $result['content']  = '<div>Cảm ơn bạn đã đăng ký nhận tin!</div>
                                            <div>Trong thời gian tới nếu có bất kỳ chương trình khuyến mãi nào '.config('company.sortname').' sẽ gửi cho bạn đầu tiên.</div>'; 
                }else {
                    $result['type']     = 'error';
                    $result['title']    = 'Đăng ký email thất bại!';
                    $result['content']  = 'Có lỗi xảy ra, vui lòng thử lại'; 
                }
            }else {
                $result['type']     = 'success';
                $result['title']    = 'Email này đã được đăng ký trước đó!';
                $result['content']  = '<div>Cảm ơn bạn đã đăng ký nhận tin!</div>
                                        <div>Trong thời gian tới nếu có bất kỳ chương trình khuyến mãi nào '.config('company.sortname').' sẽ gửi cho bạn đầu tiên.</div>'; 
            }
            return json_encode($result);
        }
    }

    public static function setMessageModal(Request $request){
        $response   = view('main.modal.contentMessageModal', [
            'title'     => $request->get('title') ?? null,
            'content'   => $request->get('content') ?? null
        ])->render();
        echo $response;
    }
}

<?php

namespace App\Http\Controllers;
use App\Models\Seo;
use Illuminate\Http\Request;
use App\Models\CheckSeo as CheckSeoModel;

class ErrorController extends Controller {

    public static function error404(){
        // if($request->ajax()){
        //     return response()->json(['status' => '404']);
        // }
        return view('main.error.404');
    }

    // public function handle(){
    //     $idSeo          = 500;
    //     $infoPage       = Seo::select('*')
    //                         ->where('id', $idSeo)
    //                         ->first();
    //     /* xóa check_seo_info cũ */
    //     CheckSeoModel::select('*')
    //                 ->where('seo_id', $idSeo)
    //                 ->delete();
    //     if(!empty($infoPage)){
    //         $dataCheckSeo       = self::getDataCheckSeo(env('APP_URL').'/'.$infoPage->slug_full);
    //         /* insert danh sách heading */
    //         foreach($dataCheckSeo['heading'] as $heading){
    //             CheckSeoModel::insertItem([
    //                 'seo_id'    => $idSeo,
    //                 'name'      => $heading['name'],
    //                 'type'      => 'heading',
    //                 'text'      => $heading['text']
    //             ]);
    //         }
    //         /* insert danh sách link */
    //         dd($dataCheckSeo['link']);
    //         foreach($dataCheckSeo['link'] as $link){
    //             $error          = [];
    //             $errorType      = null;
    //             /* kiểm tra lỗi không có anchor_text :: cấp 2 */
    //             if(empty($link['text'])) {
    //                 $error[]    = 'Đường dẫn không có Anchor Text';
    //                 $errorType  = 2;
    //             }
    //             /* kiểm tra lỗi 404 :: cấp 1 (kiểm tra URL trong database) */
    //             if(substr($link['href'], 0, 4)=='http'){ /* đường dẫn tuyệt dối */
    //                 dd($link['href']);

    //             }
    //             // /* kiểm tra lỗi 404 :: cấp 1 (kiểm tra URL bằng response) */
    //             // if(substr($link['href'], 0, 4)=='http'){ /* đường dẫn tuyệt dối */
    //             //     $fullurl    = $link['href'];
    //             // }else if(substr($link['href'], 0, 1)=='/'){ /* đường dẫn tương đối đúng */
    //             //     $fullurl    = env('APP_URL').$link['href'];
    //             // }else { /* đường dẫn tương đối sai (không có / trước) */
    //             //     $fullurl    = env('APP_URL').'/'.$link['href'];
    //             //     $error[]    = 'Đường dẫn tương đối thiếu dấu / phía trước';
    //             //     $errorType  = 3;
    //             // }
    //             // $res            = \App\Helpers\RestFull::execute($fullurl, 'GET');
    //             // if(!empty($res['response'])&&$res['response']->status=='404'){
    //             //     $error[]    = 'Đường dẫn đến trang 404';
    //             //     $errorType  = 3;
    //             // }

    //             /* insert */
    //             CheckSeoModel::insertItem([
    //                 'seo_id'        => $idSeo,
    //                 'name'          => $link['name'],
    //                 'type'          => 'link',
    //                 'text'          => $link['text'],
    //                 'href'          => $link['href'],
    //                 'error'         => implode(', ', $error),
    //                 'error_type'    => $errorType
    //             ]);
    //         }
    //         /* insert danh sách image */
    //         foreach($dataCheckSeo['image'] as $image){
    //             $error          = [];
    //             $errorType      = null;
    //             /* kiểm tra lỗi title */
    //             if(empty($image['title'])) {
    //                 $error[]    = 'Title của ảnh trống';
    //                 $errorType  = 2;
    //             }
    //             /* kiểm tra lỗi alt */
    //             if(empty($image['alt'])) {
    //                 $error[]    = 'Alt của ảnh trống';
    //                 $errorType  = 3;
    //             }
    //             // /* kiểm tra lỗi ảnh */
    //             // if(substr($image['src'], 0, 4)=='http'){
    //             //     $fullImg    = $image['src'];
    //             // }else {
    //             //     $fullImg    = env('APP_URL').$image['src'];
    //             // }
    //             // $file_headers   = @get_headers($fullImg);
    //             // if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
    //             //     $error[]    = 'Không tải được ảnh do đường dẫn sai';
    //             //     $errorType  = 3;
    //             // }
    //             /* insert */
    //             CheckSeoModel::insertItem([
    //                 'seo_id'        => $idSeo,
    //                 'name'          => 'img',
    //                 'type'          => 'image',
    //                 'src'           => $image['src'],
    //                 'alt'           => $image['alt'],
    //                 'title'         => $image['title'],
    //                 'error'         => implode(', ', $error),
    //                 'error_type'    => $errorType
    //             ]);
    //         }
    //     }
    // }

    // public static function getDataCheckSeo($linkWebPage){
    //     $result             = [];
    //     if(!empty($linkWebPage)){
    //         $webPage        = \App\Helpers\RestFull::getWebPage($linkWebPage);
    //         preg_match('#<div class="pageContent">(.*)<section#imsU', $webPage['content'], $match);
    //         $newContent     = $match[0];
    //         /* lọc heading */
    //         preg_match_all('#<(h[1-6]).*>(.*)</h#imsU', $newContent, $match);
    //         $arrayHeading = [];
    //         for($i=0;$i<count($match[1]);++$i){
    //             $arrayHeading[$i]['name']   = $match[1][$i];
    //             $arrayHeading[$i]['text']   = $match[2][$i];
    //         }
    //         $result['heading']      = $arrayHeading;
    //         /* lọc thẻ a */
    //         preg_match_all('#<a.*href="(.*)".*>(.*)</a#imsU', $newContent, $match);
    //         $tmp                    = [];
    //         for($i=0;$i<count($match[1]);++$i){
    //             $tmp[$i]['name']    = 'a';
    //             $tmp[$i]['href']    = $match[1][$i];
    //             $tmp[$i]['text']    = $match[2][$i];
    //         }
    //         /* loại bỏ thẻ a link theo id và link đến sdt */
    //         $arrayAhref             = [];
    //         foreach($tmp as $t){
    //             if(substr($t['href'], 0, 1)!='#'&&substr($t['href'], 0, 4)!='tel:') $arrayAhref[] = $t;
    //         }
    //         $result['link']         = $arrayAhref;
    //         /* lọc thẻ img */
    //         preg_match_all('#<(img.*)>#imsU', $newContent, $match);
    //         $arrayImage                     = [];
    //         $i                              = 0;
    //         foreach($match[1] as $img){
    //             /* src */
    //             preg_match('#data-src="(.*)"#imsU', $img, $matchChild);
    //             $arrayImage[$i]['src']      = $matchChild[1] ?? null;
    //             /* alt */
    //             preg_match('#alt="(.*)"#imsU', $img, $matchChild);
    //             $arrayImage[$i]['alt']      = $matchChild[1] ?? null;
    //             /* title */
    //             preg_match('#title="(.*)"#imsU', $img, $matchChild);
    //             $arrayImage[$i]['title']    = $matchChild[1] ?? null;
    //             ++$i;
    //         }
    //         $result['image']                = $arrayImage;
    //     }
    //     return $result;
    // }

}

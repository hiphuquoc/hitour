<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seo;
use App\Models\Blogger;
use App\Models\Contentspin;
use App\Models\Keyword;
use App\Services\BuildInsertUpdateModel;
use Illuminate\Support\Facades\Cookie;

class AdminToolSeoController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel   = $BuildInsertUpdateModel;
    }

    public function listBlogger(){
        $viewPerPage    = Cookie::get('viewBloggers') ?? 50;
        $list           = Blogger::select('*')
                            ->orderBy('id', 'DESC')
                            ->paginate($viewPerPage);
        return view('admin.toolSeo.listBlogger', compact('list', 'viewPerPage'));
    }

    public function addBlogger(Request $request){
        $result['flag']     = false;
        $result['content']  = null;
        if(!empty($request->get('dataForm'))){
            $dataForm   = [];
            foreach($request->get('dataForm') as $tmp){
                $dataForm[$tmp['name']] = $tmp['value'];
            }
            /* insert blogger_info */
            $insertBloggerInfo      = $this->BuildInsertUpdateModel->buildArrayTableBloggerInfo($dataForm);
            $idBloggerInfo          = Blogger::insertItem($insertBloggerInfo);
            if(!empty($idBloggerInfo)){ /* -> update thành công */
                $infoBlogger        = Blogger::find($idBloggerInfo);
                $result['flag']     = true;
                $result['content']  = view('admin.toolSeo.oneRowBlogger', ['item' => $infoBlogger, 'no' => '-', 'style' => 'background:rgba(0, 123, 255, 0.12);'])->render();
            }
            return json_encode($result);
        }
        
    }

    public function deleteBlogger(Request $request){
        if(!empty($request->get('id'))){
            Blogger::select('*')->where('id', $request->get('id'))->delete();
            echo true;
        }
        echo false;
    }

    public function listAutoPost(){
        $viewPerPage    = Cookie::get('viewAutoPost') ?? 50;
        $list           = Seo::select('*')
                            ->orderBy('type', 'DESC')
                            ->with('keywords', 'contentspin')
                            ->paginate($viewPerPage);
        return view('admin.toolSeo.listAutoPost', compact('list', 'viewPerPage'));
    }

    public function loadRowAutoPost(Request $request){
        $result         = null;
        if(!empty($request->get('id'))){
            $item       = Seo::select('*')
                            ->where('id', $request->get('id'))
                            ->with('keywords', 'contentspin')
                            ->first();
            $result     = view('admin.toolSeo.oneRowAutoPost', compact('item'));
        }
        echo $result;
    }

    public function loadFormContentspin(Request $request){
        $result         = null;
        if(!empty($request->get('id'))){
            $item       = Seo::select('*')
                            ->where('id', $request->get('id'))
                            ->with('contentspin')
                            ->first();
            $result     = view('admin.toolSeo.formContentspin', compact('item'));
            
        }
        echo $result;
    }

    public function createContentspin(Request $request){
        if(!empty($request->get('dataForm'))){
            $dataForm   = [];
            foreach($request->get('dataForm') as $tmp){
                $dataForm[$tmp['name']] = $tmp['value'];
            }
            /* thêm vào CSDL */
            Contentspin::select('*')
                ->where('seo_id', $dataForm['seo_id'])
                ->delete();
            Contentspin::insertItem([
                'seo_id'        => $dataForm['seo_id'],
                'title'         => $dataForm['title'],
                'description'   => $dataForm['description'],
                'content'       => $dataForm['content']
            ]);
        }
        echo $dataForm['seo_id'];
    }

    public function loadFormKeyword(Request $request){
        $result         = null;
        if(!empty($request->get('id'))){
            $item       = Seo::select('*')
                            ->where('id', $request->get('id'))
                            ->with('keywords')
                            ->first();
            $result     = view('admin.toolSeo.formKeyword', compact('item'));
        }
        echo $result;
    }

    public function createKeyword(Request $request){
        $result                 = null;
        if(!empty($request->get('id')&&!empty($request->get('strKeyword')))){
            $idSeo              = $request->get('id');
            $arrayKeyword       = explode(',', $request->get('strKeyword'));
            foreach($arrayKeyword as $keyword){
                if(!empty($keyword)){
                    $tmp        = Keyword::select('*')
                                    ->where('seo_id', $idSeo)
                                    ->where('keyword', $keyword)
                                    ->first();
                    if(empty($tmp)){
                        $idKeyword = Keyword::insertItem([
                            'seo_id'    => $idSeo,
                            'keyword'   => $keyword
                        ]);
                        $result     .= '<span id="keyword_'.$idKeyword.'" class="bg-primary badgeKeyword">
                                            '.$keyword.'
                                            <i class="fa-solid fa-xmark" onClick="deleteKeyword('.$idKeyword.');"></i>
                                        </span>';
                    }
                }
            }
        }
        echo $result;
    }

    public function deleteKeyword(Request $request){
        $flag       = false;
        if(!empty($request->get('idKeyword'))){
            $flag   = Keyword::select('*')
                        ->where('id', $request->get('idKeyword'))
                        ->delete();
        }
        echo $flag;
    }
}

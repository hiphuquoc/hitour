<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seo;
use App\Models\Blogger;
use App\Models\Contentspin;
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

    public function loadContentspin(Request $request){
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
        $idContentspin  = null;
        if(!empty($request->get('dataForm'))){
            $dataForm   = [];
            foreach($request->get('dataForm') as $tmp){
                $dataForm[$tmp['name']] = $tmp['value'];
            }
            /* thêm vào CSDL */
            Contentspin::select('*')
                ->where('seo_id', $dataForm['seo_id'])
                ->delete();
            $idContentspin  = Contentspin::insertItem([
                'seo_id'        => $dataForm['seo_id'],
                'title'         => $dataForm['title'],
                'description'   => $dataForm['description'],
                'content'       => $dataForm['content']
            ]);
        }
        echo $dataForm['seo_id'];
    }
}

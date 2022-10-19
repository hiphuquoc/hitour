<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blogger;
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
        // dd($request->get('id'));
        if(!empty($request->get('id'))){
            Blogger::select('*')->where('id', $request->get('id'))->delete();
            echo true;
        }
        echo false;
    }
}

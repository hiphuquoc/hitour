<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\Comment;
use Illuminate\Support\Facades\Cache;
use App\Services\BuildInsertUpdateModel;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Jobs\CheckSeo;

class AdminHotelCommentController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel   = $BuildInsertUpdateModel;
    }

    public function view(Request $request){
        $message            = $request->get('message') ?? null;
        $item               = Hotel::select('*')
                                ->where('id', $request->get('id'))
                                ->with(['files' => function($query){
                                    $query->where('relation_table', 'hotel_info');
                                }])
                                ->with('seo', 'facilities', 'contents', 'questions')
                                ->first();
        return view('admin.hotelComment.view', compact('item', 'message'));
    }

    public function update(Request $request){
        try {
            DB::beginTransaction();
            $idHotel            = $request->get('hotel_info_id') ?? 0;
            
            /* xóa và insert comments */
            Comment::select('*')
                ->where('reference_type', 'hotel_info')
                ->where('reference_id', $idHotel)
                ->delete();
            if(!empty($request->get('comments'))){
                foreach($request->get('comments') as $comment){
                    $insertComment = $this->BuildInsertUpdateModel->buildArrayTableCommentInfo($comment, $idHotel, 'hotel_info');
                    Comment::insertItem($insertComment);
                }
            }
            DB::commit();
            /* Message */
            $message        = [
                'type'      => 'success',
                'message'   => '<strong>Thành công!</strong> Các thay đổi đã được lưu'
            ];
        } catch (\Exception $exception){
            DB::rollBack();
            /* Message */
            $message        = [
                'type'      => 'danger',
                'message'   => '<strong>Thất bại!</strong> Có lỗi xảy ra, vui lòng thử lại'
            ];
        }
        /* ===== START:: check_seo_info */
        CheckSeo::dispatch($request->get('seo_id'));
        /* ===== END:: check_seo_info */
        $request->session()->put('message', $message);
        return redirect()->route('admin.hotelComment.view', ['id' => $idHotel]);
    }
}


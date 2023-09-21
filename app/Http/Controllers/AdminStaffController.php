<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Upload;
use App\Services\BuildInsertUpdateModel;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminStaffController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel  = $BuildInsertUpdateModel;
    }

    public function list(){
        $list               = Staff::getList();
        return view('admin.staff.list', compact('list'));
    }

    public function viewEdit(Request $request, $id){
        if(!empty($id)){
            $item           = Staff::find($id);
            $type           = 'edit';
            if(!empty($request->get('type'))) $type = $request->get('type');
            if(!empty($item)) return view('admin.staff.view', compact('item', 'type'));

        }
        return redirect()->route('admin.staff.list');
    }

    public function viewInsert(Request $request){
        $type               = 'create';
        return view('admin.staff.view', compact('type'));
    }

    public function create(Request $request){
        /* upload image */
        $avatarPath         = null;
        if($request->hasFile('avatar')) {
            $avatarPath     = Upload::uploadAvatar($request->file('avatar'));
        }
        /* tạo user & password */
        $user = User::create([
            'name'      => $request->get('email'),
            'email'     => $request->get('email'),
            'password'  => Hash::make('hitourVN@mk123')
        ]);
        /* insert staff_info */
        $insertStaffInfo    = $this->BuildInsertUpdateModel->buildArrayTableStaffInfo($user->id, $request->all(), $avatarPath);
        // dd($insertTourInfo);
        $idStaff            = Staff::insertItem($insertStaffInfo);
        /* Message */
        return redirect()->route('admin.staff.list');
    }

    public function update(Request $request){
        /* upload image */
        $avatarPath         = null;
        if($request->hasFile('avatar')) {
            $avatarPath     = Upload::uploadAvatar($request->file('avatar'));
        }
        /* cập nhật lại email user */
        $infoStaff          = Staff::find($request->get('id'));
        $modalUser          = User::find($infoStaff->user_id);
        $modalUser->email   = $request->get('email');
        $modalUser->update();
        /* insert staff_info */
        $updateStaffInfo    = $this->BuildInsertUpdateModel->buildArrayTableStaffInfo($infoStaff->user_id, $request->all(), $avatarPath);
        // dd($insertTourInfo);
        Staff::updateItem($request->get('id'), $updateStaffInfo);
        /* Message */
        return redirect()->route('admin.staff.list');
    }

    public static function delete(Request $request){
        if(!empty($request->get('id'))){
            try {
                DB::beginTransaction();
                $id         = $request->get('id');
                /* lấy thông tin */
                $infoStaff  = Staff::find($id);
                /* xóa user */
                User::select('*')
                    ->where('id', $infoStaff->user_id)
                    ->delete();
                /* delete ảnh */
                if(!empty($infoStaff->avatar)&&file_exists(public_path($infoStaff->avatar))) @unlink(public_path($infoStaff->avatar));
                /* delete bảng tour_location */
                $infoStaff->delete();
                DB::commit();
                return true;
            } catch (\Exception $exception){
                DB::rollBack();
                return false;
            }
        }
    }
}

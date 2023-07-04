<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Tour;
use App\Models\Combo;
use App\Models\Booking;
use App\Models\BookingQuantityAndPrice;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use App\Services\BuildInsertUpdateModel;
use Illuminate\Support\Facades\Cookie;
use App\Models\BookingStatus;

class AdminBookingController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel  = $BuildInsertUpdateModel;
    }

    public function list(Request $request){
        $params             = [];
        /* Search theo tên */
        if(!empty($request->get('search_customer'))) $params['search_customer'] = $request->get('search_customer');
        /* Search theo vùng miền */
        if(!empty($request->get('search_type'))) $params['search_type'] = $request->get('search_type');
        /* Search theo trang thái */
        if(!empty($request->get('search_status'))) $params['search_status'] = $request->get('search_status');
        /* paginate */
        $viewPerPage        = Cookie::get('viewBooking') ?? 50;
        $params['paginate'] = $viewPerPage;
        /* lấy dữ liệu */
        $list               = Booking::getList($params);
        /* status */
        $status             = BookingStatus::all();
        return view('admin.booking.list', compact('list', 'params', 'status', 'viewPerPage'));
    }

    public function view(Request $request, $id){
        if(!empty($id)){
            $item           = Booking::select('*')
                                ->where('id', $id)
                                ->with('customer_contact', 'quantityAndPrice', 'customer_list', 'tour.options.prices', 'service.options.prices', 'combo.options.prices', 'costMoreLess', 'vat')
                                ->first();
            if(!empty($item->type)&&$item->type=='tour_info'){
                $list       = Tour::all();
            }else if(!empty($item->type)&&$item->type=='service_info'){
                $list       = Service::all();
            }else {
                $list       = Combo::all();
            }
            $message        = $request->get('message') ?? null;
            $type           = 'edit';
            if(!empty($item)) return view('admin.booking.view', compact('item', 'list', 'type', 'message'));
        }
        return redirect()->route('admin.booking.list');
    }

    public function viewExport($id){
        $item               = Booking::select('*')
                                ->where('id', $id)
                                ->with('customer_contact', 'customer_list', 'quantityAndPrice', 'tour', 'service', 'costMoreLess', 'vat', 'status')
                                ->first();
        $idUser             = Auth::id() ?? 0;
        $infoStaff          = \App\Models\Staff::select('*')
                                ->where('user_id', $idUser)
                                ->first();
        return view('admin.booking.viewExport', compact('item', 'infoStaff'));
    }

    public function viewExportHtml(Request $request){
        $item               = Booking::select('*')
                                ->where('id', $request->get('id'))
                                ->with('customer_contact', 'customer_list', 'quantityAndPrice', 'tour', 'service', 'costMoreLess', 'vat', 'status')
                                ->first();
        $idUser             = Auth::id() ?? 0;
        $infoStaff          = \App\Models\Staff::select('*')
                                ->where('user_id', $idUser)
                                ->first();
        return view('admin.booking.viewExportHtml', compact('item', 'infoStaff'));
    }

    public static function getExpirationAt(Request $request){
        $result             = null;
        if(!empty($request->get('booking_info_id'))){
            $info           = Booking::select('required_deposit', 'expiration_at')
                                ->where('id', $request->get('booking_info_id'))
                                ->first();
            if(empty($info->expiration_at)){
                $time           = date('Y-m-d', time()).' 18:00';
                $expirationAt   = date('Y-m-d H:i', strtotime($time));
            }else {
                $expirationAt   = date('Y-m-d H:i', strtotime($info->expiration_at));
            }
            $result['expiration_at']    = $expirationAt;
            $result['required_deposit'] = $info->required_deposit;
        }
        return $result;
    }

    public static function createPdfConfirm(Request $request){
        $flag               = false;
        if(!empty($request->get('booking_info_id'))&&!empty($request->get('expiration_at'))){
            /* cập nhật thời hạn booking && đổi trạng thái sang đã xác nhận Zalo */
            $flag           = Booking::updateItem($request->get('booking_info_id'), [
                'expiration_at'             => $request->get('expiration_at'),
                'required_deposit'          => $request->get('required_deposit'),
                'status_id'                 => 3
            ]);
        }
        echo $flag;
    }

    public static function sendMailConfirm(Request $request){
        if(!empty($request->get('booking_info_id'))&&!empty($request->get('expiration_at'))){
            /* cập nhật thời hạn booking && đổi trạng thái đã xác nhận email */
            Booking::updateItem($request->get('booking_info_id'), [
                'expiration_at'             => $request->get('expiration_at'),
                'required_deposit'          => $request->get('required_deposit'),
                'status_id'                 => 2
            ]);
            /* tạo queue gửi email */
            $infoBooking            = Booking::select('*')
                                        ->where('id', $request->get('booking_info_id'))
                                        ->with('customer_contact', 'customer_list', 'status', 'service', 'tour', 'quantityAndPrice', 'costMoreLess', 'vat')
                                        ->first();
            $addressMail            = $infoBooking->customer_contact->email ?? null;
            if(!empty($addressMail)){
                /* trường hợp booking có email */
                $idUser             = Auth::id() ?? 0;
                $infoStaff          = \App\Models\Staff::select('*')
                                        ->where('user_id', $idUser)
                                        ->first();
                \App\Jobs\ConfirmBooking::dispatch($infoBooking, $infoStaff);
                /* Message */
                $toast              = [
                    'title'     => 'Thành công!',
                    'message'   => 'Đã gửi email xác nhận cho khách',
                    'type'      => 'success'
                ];
            }else {
                /* Message */
                $toast              = [
                    'title'     => 'Thất bại!',
                    'message'   => 'Khách này không có email để thao tác',
                    'type'      => 'error'
                ];
            }
            $request->session()->put('toast', $toast);
        }
    }

    public static function paymentExtension(Request $request){
        if(!empty($request->get('booking_info_id'))&&!empty($request->get('expiration_at'))){
            /* cập nhật thời hạn booking */
            $flag           = Booking::updateItem($request->get('booking_info_id'), [
                'expiration_at'             => $request->get('expiration_at')
            ]);
            /* Message */
            $toast              = [
                'title'     => 'Thành công!',
                'message'   => 'Đã cập nhật thời hạn thanh toán mới',
                'type'      => 'success'
            ];
        }else {
            /* Message */
            $toast              = [
                'title'     => 'Thất bại!',
                'message'   => 'Có lỗi xảy ra, vui lòng thử lại',
                'type'      => 'error'
            ];
        }
        $request->session()->put('toast', $toast);
    }

    public static function cancelBooking(Request $request){
        if(!empty($request->get('booking_info_id'))){
            /* đổi trạng thái sang Hủy booking */
            $flag           = Booking::updateItem($request->get('booking_info_id'), [
                'status_id'    => 7
            ]);
            /* Message */
            $toast              = [
                'title'     => 'Thành công!',
                'message'   => 'Đã hủy booking này',
                'type'      => 'success'
            ];
        }else {
            /* Message */
            $toast              = [
                'title'     => 'Thất bại!',
                'message'   => 'Có lỗi xảy ra, vui lòng thử lại',
                'type'      => 'error'
            ];
        }
        $request->session()->put('toast', $toast);
    }

    public static function restoreBooking(Request $request){
        if(!empty($request->get('booking_info_id'))){
            /* đổi trạng thái sang Hủy booking */
            $flag           = Booking::updateItem($request->get('booking_info_id'), [
                'status_id'    => 1
            ]);
            /* Message */
            $toast              = [
                'title'     => 'Thành công!',
                'message'   => 'Đã khôi phục booking này',
                'type'      => 'success'
            ];
        }else {
            /* Message */
            $toast              = [
                'title'     => 'Thất bại!',
                'message'   => 'Có lỗi xảy ra, vui lòng thử lại',
                'type'      => 'error'
            ];
        }
        $request->session()->put('toast', $toast);
    }

    public function update(Request $request){
        /* insert customer_info */
        $idCustomer                 = $request->get('customer_info_id');
        $updateCustomer             = $this->BuildInsertUpdateModel->buildArrayTableCustomerInfo($request->all());
        Customer::updateItem($idCustomer, $updateCustomer);
        /* update booking_info */
        $idBooking                  = $request->get('booking_info_id');
        /* trường hợp là tour */
        if(!empty($request->get('tour_option_id'))){
            $updateBooking              = $this->BuildInsertUpdateModel->buildArrayTableBookingInfo(0, 'tour_info', $request->all());
            Booking::updateItem($idBooking, $updateBooking);
            /* insert tour_booking_quantity_and_price */
            BookingQuantityAndPrice::select('*')
                ->where('booking_info_id', $idBooking)
                ->delete();
            $insertBookingQuantityAndPrice  = $this->BuildInsertUpdateModel->buildArrayTableTourQuantityAndPrice($idBooking, $request->all());
        }
        /* trường hợp là service */
        if(!empty($request->get('service_option_id'))){
            $updateBooking              = $this->BuildInsertUpdateModel->buildArrayTableBookingInfo(0, 'service_info', $request->all());
            Booking::updateItem($idBooking, $updateBooking);
            /* insert tour_booking_quantity_and_price */
            BookingQuantityAndPrice::select('*')
                ->where('booking_info_id', $idBooking)
                ->delete();
            $insertBookingQuantityAndPrice  = $this->BuildInsertUpdateModel->buildArrayTableServiceQuantityAndPrice($idBooking, $request->all());
        }
        /* trường hợp là combo */
        if(!empty($request->get('combo_option_id'))){
            $updateBooking              = $this->BuildInsertUpdateModel->buildArrayTableBookingInfo(0, 'combo_info', $request->all());
            Booking::updateItem($idBooking, $updateBooking);
            /* insert combo_booking_quantity_and_price */
            BookingQuantityAndPrice::select('*')
                ->where('booking_info_id', $idBooking)
                ->delete();
            $insertBookingQuantityAndPrice  = $this->BuildInsertUpdateModel->buildArrayTableComboQuantityAndPrice($idBooking, $request->all());
        }
        /* ===== */
        foreach($insertBookingQuantityAndPrice as $itemInsert) BookingQuantityAndPrice::insertItem($itemInsert);
        return redirect()->route('admin.booking.view', ['id' => $idBooking]);
    }

    public static function delete(Request $request){
        $result                 = false;
        if(!empty($request->get('id'))){
            $infoShipBooking    = Booking::find($request->get('id'));
            /* delete relation */
            $infoShipBooking->customer_contact()->delete();
            $infoShipBooking->customer_list()->delete();
            $infoShipBooking->quantityAndPrice()->delete();
            $infoShipBooking->costMoreLess()->delete();
            /* delete main */
            $infoShipBooking->delete();
            $result             = true;
        }
        return $result;
    }

    // public static function filterOptionByDate($date, $tourOptions){
    //     $result         = new \Illuminate\Database\Eloquent\Collection;
    //     if(!empty($date)&&!empty($tourOptions)){
    //         $dataCheck  = config('admin.tour_option_apply_day');
    //         foreach($tourOptions as $option){
    //             foreach($dataCheck as $checker){
    //                 if($option->apply_day==$checker['name']){
    //                     $checked    = in_array(date('w', strtotime($date)), $checker['apply_day']);
    //                     if($checked==true) $result[] = $option;
    //                     break;
    //                 }
    //             }
    //         }
    //     }
    //     return $result;
    // }
}

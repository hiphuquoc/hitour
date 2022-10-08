<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Helpers\Upload;
use App\Services\BuildInsertUpdateModel;
use App\Models\Tour;
use App\Models\TourOption;
use App\Models\TourPrice;
use Illuminate\Support\Facades\DB;

class AdminTourOptionController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel   = $BuildInsertUpdateModel;
    }

    public function loadOptionPrice(Request $request){
        $result                         = null;
        if(!empty($request->get('tour_info_id'))){
            $infoTour                   = Tour::getItemById($request->get('tour_info_id'));
            /* build array */
            $dataOption                 = self::margeTourPriceByDate($infoTour->options);
            foreach($dataOption as $option) $result .= view('admin.tour.optionPrice', compact('option'))->render();
        }
        if(empty($result)) $result      = config('admin.message_data_empty');
        echo $result;
    }

    public function create(Request $request){
        $flag                           = false;
        if(!empty($request->get('dataForm'))){
            $dataForm                   = $request->get('dataForm');
            /* insert tour_option */
            $insertTourOption           = $this->BuildInsertUpdateModel->buildArrayTableTourOption($request->get('dataForm'));
            $idTourOption               = TourOption::insertItem($insertTourOption);
            /* insert tour_price */
            foreach($dataForm['date_range'] as $dateRange){
                if(!empty($dateRange)){
                    $tmp                = explode(' to ', $dateRange);
                    $dateStart          = $tmp[0] ?? null;
                    $dateEnd            = $tmp[1] ?? null;
                    for($i=0;$i<count($request->get('dataForm')['apply_age']);++$i){
                        if(!empty($request->get('dataForm')['apply_age'][$i])&&!empty($request->get('dataForm')['price'][$i])){
                            TourPrice::insertItem([
                                'tour_option_id'    => $idTourOption,
                                'apply_age'         => $request->get('dataForm')['apply_age'][$i],
                                'price'             => $request->get('dataForm')['price'][$i],
                                'profit'            => $request->get('dataForm')['profit'][$i],
                                'date_start'        => $dateStart,
                                'date_end'          => $dateEnd
                            ]);
                        }
                    }
                }
            } 
            /* Message */
            if(!empty($idTourOption)) $flag = true;
        }
        echo $flag;
    }

    public function update(Request $request){
        $flagUpdate                 = false;
        if(!empty($request->get('dataForm'))&&!empty($request->get('dataForm')['tour_option_id'])){
            $dataForm               = $request->get('dataForm');
            /* update tour_option */
            $updateTourOption       = $this->BuildInsertUpdateModel->buildArrayTableTourOption($dataForm);
            $flagUpdate             = TourOption::updateItem($dataForm['tour_option_id'], $updateTourOption);
            /* delete and insert lại tour_price */
            TourPrice::select('*')->where('tour_option_id', $dataForm['tour_option_id'])->delete();
            foreach($dataForm['date_range'] as $dateRange){
                if(!empty($dateRange)){
                    $tmp                = explode(' to ', $dateRange);
                    $dateStart          = $tmp[0] ?? null;
                    $dateEnd            = $tmp[1] ?? null;
                    for($i=0;$i<count($request->get('dataForm')['apply_age']);++$i){
                        if(!empty($request->get('dataForm')['apply_age'][$i])&&!empty($request->get('dataForm')['price'][$i])){
                            TourPrice::insertItem([
                                'tour_option_id'    => $dataForm['tour_option_id'],
                                'apply_age'         => $request->get('dataForm')['apply_age'][$i],
                                'price'             => $request->get('dataForm')['price'][$i],
                                'profit'            => $request->get('dataForm')['profit'][$i],
                                'date_start'        => $dateStart,
                                'date_end'          => $dateEnd
                            ]);
                        }
                    }
                }
            }
        }
        echo $flagUpdate;
    }

    public static function delete(Request $request){
        $flag           = false;
        if(!empty($request->get('id'))){
            /* xóa price (con của option) */
            TourPrice::select('*')
                        ->where('tour_option_id', $request->get('id'))
                        ->delete();
            /* xóa option */
            $flag       = TourOption::find($request->get('id'))
                                    ->delete();
        }
        echo $flag;
    }

    public function loadFormOption(Request $request){
        if(!empty($request->get('tour_info_id'))){
            $option             = [];
            if(!empty($request->get('tour_option_id'))) $option   = TourOption::select('*')
                                                                    ->where('id', $request->get('tour_option_id'))
                                                                    ->with('prices')
                                                                    ->get();
            $options            = self::margeTourPriceByDate($option);
            /* lấy option đầu tiên vì là duy nhất */
            foreach($options as $o) $option = $o;
            $result['header']   = !empty($option) ? 'Chỉnh sửa Option' : 'Thêm Option';
            $result['body']     = view('admin.tour.formTourOption', compact('option'))->render();
        }else {
            $result['header']   = 'Thêm Option';
            $result['body']     = '<div style="margin-top:1rem;font-weight:600;">Vui lòng tạo và lưu Tour trước khi tạo Option & Giá!</div>';
        }
        return json_encode($result);
    }

    public static function margeTourPriceByDate($options){
        $result = [];
        if(!empty($options)){
            foreach($options as $option){
                $result[$option->option]['tour_info_id']    = $option->tour_info_id;
                $result[$option->option]['tour_option_id']  = $option->id;
                $result[$option->option]['option']          = $option->option;
                foreach($option->prices as $price){
                    dd($price);
                    $result[$option->option]['date_apply'][$price->date_start.'-'.$price->date_end][]    = $price->toArray();
                }
            }
        }
        return $result;
    }
}

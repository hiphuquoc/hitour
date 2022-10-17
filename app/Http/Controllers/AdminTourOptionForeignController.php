<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Helpers\Upload;
use App\Services\BuildInsertUpdateModel;
use App\Models\TourInfoForeign;
use App\Models\TourOptionForeign;
use App\Models\TourPrice;
use App\Models\TourPriceForeign;

class AdminTourOptionForeignController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel   = $BuildInsertUpdateModel;
    }

    public function loadOptionPrice(Request $request){
        $result                         = null;
        if(!empty($request->get('tour_info_foreign_id'))){
            $infoTour                   = TourInfoForeign::getItemById($request->get('tour_info_foreign_id'));
            /* build array */
            $dataOption                 = self::margeTourPriceByDate($infoTour->options);
            foreach($dataOption as $option) $result .= view('admin.tourInfoForeign.optionPrice', compact('option'))->render();
        }
        if(empty($result)) $result      = config('admin.message_data_empty');
        echo $result;
    }

    public function create(Request $request){
        $flag                           = false;
        if(!empty($request->get('dataForm'))){
            $dataForm                   = $request->get('dataForm');
            /* insert tour_option_foreign */
            $insertTourOptionForeign           = $this->BuildInsertUpdateModel->buildArrayTableTourOptionForeign($request->get('dataForm'));
            $idTourOptionForeign               = TourOptionForeign::insertItem($insertTourOptionForeign);
            /* insert tour_price_foreign */
            foreach($dataForm['date_range'] as $dateRange){
                if(!empty($dateRange)){
                    $tmp                = explode(' to ', $dateRange);
                    $dateStart          = $tmp[0] ?? null;
                    $dateEnd            = $tmp[1] ?? null;
                    for($i=0;$i<count($request->get('dataForm')['apply_age']);++$i){
                        if(!empty($request->get('dataForm')['apply_age'][$i])&&!empty($request->get('dataForm')['price'][$i])){
                            TourPriceForeign::insertItem([
                                'tour_option_foreign_id'    => $idTourOptionForeign,
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
            if(!empty($idTourOptionForeign)) $flag = true;
        }
        echo $flag;
    }

    public function update(Request $request){
        $flagUpdate                     = false;
        if(!empty($request->get('dataForm'))&&!empty($request->get('dataForm')['tour_option_foreign_id'])){
            $dataForm                   = $request->get('dataForm');
            /* update tour_option_foreign */
            $updateTourOptionForeign    = $this->BuildInsertUpdateModel->buildArrayTableTourOptionForeign($dataForm);
            $flagUpdate                 = TourOptionForeign::updateItem($dataForm['tour_option_foreign_id'], $updateTourOptionForeign);
            /* delete and insert lại tour_price_foreign */
            TourPriceForeign::select('*')->where('tour_option_foreign_id', $dataForm['tour_option_foreign_id'])->delete();
            foreach($dataForm['date_range'] as $dateRange){
                if(!empty($dateRange)){
                    $tmp                = explode(' to ', $dateRange);
                    $dateStart          = $tmp[0] ?? null;
                    $dateEnd            = $tmp[1] ?? null;
                    for($i=0;$i<count($request->get('dataForm')['apply_age']);++$i){
                        if(!empty($request->get('dataForm')['apply_age'][$i])&&!empty($request->get('dataForm')['price'][$i])){
                            TourPriceForeign::insertItem([
                                'tour_option_foreign_id'    => $dataForm['tour_option_foreign_id'],
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
            TourPriceForeign::select('*')
                        ->where('tour_option_foreign_id', $request->get('id'))
                        ->delete();
            /* xóa option */
            $flag       = TourOptionForeign::find($request->get('id'))
                                    ->delete();
        }
        echo $flag;
    }

    public function loadFormOption(Request $request){
        if(!empty($request->get('tour_info_foreign_id'))){
            $option             = [];
            if(!empty($request->get('tour_option_foreign_id'))) $option   = TourOptionForeign::select('*')
                ->where('id', $request->get('tour_option_foreign_id'))
                ->with('prices')
                ->get();
            $options            = self::margeTourPriceByDate($option);
            /* lấy option đầu tiên vì là duy nhất */
            foreach($options as $o) $option = $o;
            $result['header']   = !empty($option) ? 'Chỉnh sửa Option' : 'Thêm Option';
            $result['body']     = view('admin.tourInfoForeign.formTourOption', compact('option'))->render();
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
                $result[$option->option]['tour_info_foreign_id']    = $option->tour_info_foreign_id;
                $result[$option->option]['tour_option_foreign_id']  = $option->id;
                $result[$option->option]['option']          = $option->option;
                foreach($option->prices as $price){
                    $result[$option->option]['date_apply'][$price->date_start.'-'.$price->date_end][]    = $price->toArray();
                }
            }
        }
        return $result;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Helpers\Upload;
use App\Services\BuildInsertUpdateModel;
use App\Models\Combo;
use App\Models\ComboOption;
use App\Models\ComboPrice;
use App\Models\TourDeparture;
use Illuminate\Support\Facades\DB;

class AdminHotelOptionController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel   = $BuildInsertUpdateModel;
    }

    public function loadOptionPrice(Request $request){
        $result                         = null;
        if(!empty($request->get('combo_info_id'))){
            $infoTour                   = Combo::getItemById($request->get('combo_info_id'));
            /* build array */
            $dataOption                 = self::margeComboPriceByDate($infoTour->options);
            foreach($dataOption as $option) $result .= view('admin.combo.optionPrice', compact('option'))->render();
        }
        if(empty($result)) $result      = config('admin.message_data_empty');
        echo $result;
    }

    public function create(Request $request){
        $flag                           = false;
        if(!empty($request->get('dataForm'))){
            $dataForm                   = $request->get('dataForm');
            /* insert combo_option */
            $insertComboOption           = $this->BuildInsertUpdateModel->buildArrayTableComboOption($request->get('dataForm'));
            $idComboOption               = ComboOption::insertItem($insertComboOption);
            /* insert combo_price */
            foreach($dataForm['date_range'] as $dateRange){
                if(!empty($dateRange)){
                    $tmp                = explode(' to ', $dateRange);
                    $dateStart          = $tmp[0] ?? null;
                    $dateEnd            = $tmp[1] ?? null;
                    for($i=0;$i<count($request->get('dataForm')['apply_age']);++$i){
                        if(!empty($request->get('dataForm')['apply_age'][$i])&&!empty($request->get('dataForm')['price'][$i])){
                            ComboPrice::insertItem([
                                'departure_id'      => $dataForm['departure_id'],
                                'combo_option_id'   => $idComboOption,
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
            if(!empty($idComboOption)) $flag = true;
        }
        echo $flag;
    }

    public function update(Request $request){
        $flagUpdate                 = false;
        if(!empty($request->get('dataForm'))&&!empty($request->get('dataForm')['combo_option_id'])){
            $dataForm               = $request->get('dataForm');
            /* update combo_option */
            $updateComboOption      = $this->BuildInsertUpdateModel->buildArrayTableComboOption($dataForm);
            $flagUpdate             = ComboOption::updateItem($dataForm['combo_option_id'], $updateComboOption);
            /* delete and insert lại combo_price */
            ComboPrice::select('*')->where('combo_option_id', $dataForm['combo_option_id'])->delete();
            foreach($dataForm['date_range'] as $dateRange){
                if(!empty($dateRange)){
                    $tmp                = explode(' to ', $dateRange);
                    $dateStart          = $tmp[0] ?? null;
                    $dateEnd            = $tmp[1] ?? null;
                    for($i=0;$i<count($request->get('dataForm')['apply_age']);++$i){
                        if(!empty($request->get('dataForm')['apply_age'][$i])&&!empty($request->get('dataForm')['price'][$i])){
                            ComboPrice::insertItem([
                                'departure_id'      => $dataForm['departure_id'],
                                'combo_option_id'   => $dataForm['combo_option_id'],
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
            ComboPrice::select('*')
                        ->where('combo_option_id', $request->get('id'))
                        ->delete();
            /* xóa option */
            $flag       = ComboOption::find($request->get('id'))
                                    ->delete();
        }
        echo $flag;
    }

    public function loadFormOption(Request $request){
        if(!empty($request->get('combo_info_id'))){
            $option             = [];
            if(!empty($request->get('combo_option_id'))) $option   = ComboOption::select('*')
                                                                        ->where('id', $request->get('combo_option_id'))
                                                                        ->with('prices')
                                                                        ->get();
            $options            = self::margeComboPriceByDate($option);
            /* lấy option đầu tiên vì là duy nhất */
            foreach($options as $o) $option = $o;
            /* lấy dach sách departure */
            $departures         = TourDeparture::all();
            $result['header']   = !empty($option) ? 'Chỉnh sửa Option' : 'Thêm Option';
            $result['body']     = view('admin.combo.formComboOption', compact('option', 'departures'))->render();
        }else {
            $result['header']   = 'Thêm Option';
            $result['body']     = '<div style="margin-top:1rem;font-weight:600;">Vui lòng tạo và lưu Tour trước khi tạo Option & Giá!</div>';
        }
        return json_encode($result);
    }

    public static function margeComboPriceByDate($options){
        $result = [];
        if(!empty($options)){
            foreach($options as $option){
                $result[$option->name]['combo_info_id']     = $option->combo_info_id;
                $result[$option->name]['combo_option_id']   = $option->id;
                $result[$option->name]['name']              = $option->name;
                $result[$option->name]['days']              = $option->days;
                $result[$option->name]['nights']            = $option->nights;
                foreach($option->prices as $price){
                    $result[$option->name]['date_apply'][$price->date_start.'-'.$price->date_end][]    = $price->toArray();
                }
            }
        }
        return $result;
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class AirRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'slug'                      => [
                'required',
                function($attribute, $value, $fail){
                    $slug           = !empty(request('slug')) ? request('slug') : null;
                    if(!empty($slug)){
                        $dataCheck  = DB::table('seo')
                                        ->join('air_info', 'air_info.seo_id', '=', 'seo.id')
                                        ->select('seo.slug', 'air_info.id')
                                        ->where('slug', $slug)
                                        ->first();
                        if(!empty($dataCheck)){
                            if(empty(request('air_info_id'))) $fail('Dường dẫn tĩnh đã trùng với một Chi tiết tàu khác trên hệ thống!');
                        }
                    }
                }
            ],
            'title'                     => 'required|max:255',
            'description'               => 'required',
            'content'                   => 'required',
            'ordering'                  => 'min:0',
            'seo_title'                 => 'required',
            'seo_description'           => 'required',
            'rating_aggregate_count'    => 'required',
            'rating_aggregate_star'     => 'required',
            'air_departure_id'         => 'integer|min:1',
            'air_location_id'          => 'integer|min:1',
            'air_port_departure_id'    => 'integer|min:1',
            'air_port_location_id'     => 'integer|min:1'
        ];
    }

    public function messages()
    {
        return [
            'title.required'                    => 'Tiêu đề trang không được để trống!',
            'title.max'                         => 'Tiêu đề trang không được vượt quá 255 ký tự!',
            'description.required'              => 'Mô tả trang không được để trống!',
            'content.required'                  => 'Nội dung trang không được để trống!',
            'ordering.min'                      => 'Giá trị không được nhỏ hơn 0!',
            'seo_title.required'                => 'Tiêu đề SEO không được để trống!',
            'seo_description.required'          => 'Mô tả SEO không được để trống!',
            'slug.required'                     => 'Đường dẫn tĩnh không được để trống!',
            'rating_aggregate_count.required'   => 'Số lượt đánh giá không được để trống!',
            'rating_aggregate_star.required'    => 'Điểm đánh giá không được để trống!',
            'air_departure_id.min'             => 'Điểm khởi hành không được để trống',
            'air_location_id.min'              => 'Điểm đến Tàu không được để trống',
            'air_port_departure_id.min'        => 'Cảng khởi hành không được để trống',
            'air_port_location_id.min'         => 'Cảng cập bến không được để trống'
        ];
    }
}

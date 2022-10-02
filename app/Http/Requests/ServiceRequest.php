<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class ServiceRequest extends FormRequest
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
            'tour_location_id'          => 'required',
            'staff'                     => 'required',
            'price_show'                => 'required|min:0',
            'price_del'                 => 'min:0',
            'code'                      => 'required',
            'slug'                      => [
                'required',
                function($attribute, $value, $fail){
                    $slug           = !empty(request('slug')) ? request('slug') : null;
                    if(!empty($slug)){
                        $dataCheck  = DB::table('seo')
                                        ->join('service_info', 'service_info.seo_id', '=', 'seo.id')
                                        ->select('seo.slug', 'service_info.id')
                                        ->where('slug', $slug)
                                        ->first();
                        if(!empty($dataCheck)&&$dataCheck->id!=request('service_info_id')) $fail('Dường dẫn tĩnh đã trùng với một Dịch vụ khác trên hệ thống!');
                    }
                }
            ],
            'title'                     => 'required|max:255',
            'description'               => 'required',
            'ordering'                  => 'min:0',
            'seo_title'                 => 'required',
            'seo_description'           => 'required',
            'rating_aggregate_count'    => 'required',
            'rating_aggregate_star'     => 'required'
        ];
    }

    public function messages()
    {
        return [
            'tour_location_id.required'         => 'Điểm đến không được để trống!',
            'staff.required'                    => 'Nhân viên tư vấn không được để trống!',
            'price_show.required'               => 'Giá hiển thị không được để trống!',
            'price_show.min'                    => 'Giá hiển thị không được nhỏ hơn 0!',
            'price_del.min'                     => 'Giá cũ không được nhỏ hơn 0!',
            'code.required'                     => 'Mã không được để trống!',
            'title.required'                    => 'Tiêu đề trang không được để trống!',
            'title.max'                         => 'Tiêu đề trang không được vượt quá 255 ký tự!',
            'description'                       => 'Mô tả trang không được để trống!',
            'ordering.min'                      => 'Giá trị không được nhỏ hơn 0!',
            'seo_title.required'                => 'Tiêu đề SEO không được để trống!',
            'seo_description.required'          => 'Mô tả SEO không được để trống!',
            'slug.required'                     => 'Đường dẫn tĩnh không được để trống!',
            'rating_aggregate_count.required'   => 'Số lượt đánh giá không được để trống!',
            'rating_aggregate_star.required'    => 'Điểm đánh giá không được để trống!'
        ];
    }
}

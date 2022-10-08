<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class TourDepartureRequest extends FormRequest
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
            'title'                     => 'required|max:255',
            'description'               => 'required',
            'ordering'                  => 'min:0',
            'seo_title'                 => 'required',
            'seo_description'           => 'required',
            'display_name'              => 'required',
            'slug'                      => [
                'required',
                function($attribute, $value, $fail){
                    $slug           = !empty(request('slug')) ? request('slug') : null;
                    if(!empty($slug)){
                        $dataCheck  = DB::table('seo')
                                        ->join('tour_departure', 'tour_departure.seo_id', '=', 'seo.id')
                                        ->select('seo.slug', 'tour_departure.id')
                                        ->where('slug', $slug)
                                        ->first();
                        if(!empty($dataCheck)){
                            if(empty(request('tour_departure_id'))) $fail('Dường dẫn tĩnh đã trùng với một Điểm khởi hành tour khác trên hệ thống!');
                        }
                    }
                }
            ],
            'rating_aggregate_count'    => 'required',
            'rating_aggregate_star'     => 'required'
        ];
    }

    public function messages()
    {
        return [
            'title.required'            => 'Tiêu đề trang không được để trống!',
            'title.max'                 => 'Tiêu đề trang không được vượt quá 255 ký tự!',
            'description.required'      => 'Mô tả trang không được để trống!',
            'display_name.required'     => 'Tên hiển thị không được để trống!',
            'ordering.min'              => 'Giá trị không được nhỏ hơn 0!',
            'seo_title.required'        => 'Tiêu đề SEO không được để trống!',
            'seo_description.required'  => 'Mô tả SEO không được để trống!',
            'slug.required'             => 'Đường dẫn tĩnh không được để trống!',
            'rating_aggregate_count'    => 'Số lượt đánh giá không được để trống!',
            'rating_aggregate_star'     => 'Điểm đánh giá không được để trống!'
        ];
    }
}

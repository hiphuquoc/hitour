<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class HotelRequest extends FormRequest
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
                                        ->join('hotel_info', 'hotel_info.seo_id', '=', 'seo.id')
                                        ->select('seo.slug', 'hotel_info.id')
                                        ->where('slug', $slug)
                                        ->first();
                        if(!empty($dataCheck)){
                            if(empty(request('hotel_info_id'))) $fail('Dường dẫn tĩnh đã trùng với một Chi tiết Hotel khác trên hệ thống!');
                        }
                    }
                }
            ],
            'title'                     => 'required|max:255',
            'seo_title'                 => 'required',
            'seo_description'           => 'required',
            'rating_aggregate_count'    => 'required',
            'rating_aggregate_star'     => 'required'
        ];
    }

    public function messages()
    {
        return [
            'title.required'                    => 'Tiêu đề không được bỏ trống!',
            'title.max'                         => 'Tiêu đề không quá 255 ký tự!',
            'seo_title.required'                => 'Tiêu đề SEO không được bỏ trống!',
            'seo_description.required'          => 'Mô tả SEO không được bỏ trống!',
            'rating_aggregate_count.required'   => 'Số lượt đánh giá không được bỏ trống!',
            'rating_aggregate_star.required'    => 'Số sao đánh giá không được bỏ trống!'
        ];
    }
}

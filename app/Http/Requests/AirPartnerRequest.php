<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class AirPartnerRequest extends FormRequest
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
            'ordering'                  => 'min:0',
            'seo_title'                 => 'required',
            'seo_description'           => 'required',
            'content'                   => 'required',
            'slug'                      => [
                'required',
                function($attribute, $value, $fail){
                    $slug           = !empty(request('slug')) ? request('slug') : null;
                    if(!empty($slug)){
                        $dataCheck  = DB::table('seo')
                                        ->join('air_partner', 'air_partner.seo_id', '=', 'seo.id')
                                        ->select('seo.slug', 'air_partner.id')
                                        ->where('slug', $slug)
                                        ->first();
                        if(!empty($dataCheck)){
                            if(empty(request('air_partner_id'))) $fail('Dường dẫn tĩnh đã trùng với một Đối tác tàu khác trên hệ thống!');
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
            'ordering.min'              => 'Giá trị không được nhỏ hơn 0!',
            'seo_title.required'        => 'Tiêu đề SEO không được để trống!',
            'seo_description.required'  => 'Mô tả SEO không được để trống!',
            'content.required'          => 'Nội dung trang không được để trống!',
            'slug.required'             => 'Đường dẫn tĩnh không được để trống!',
            'rating_aggregate_count'    => 'Số lượt đánh giá không được để trống!',
            'rating_aggregate_star'     => 'Điểm đánh giá không được để trống!'
        ];
    }
}

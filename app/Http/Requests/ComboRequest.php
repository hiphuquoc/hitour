<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class ComboRequest extends FormRequest
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
            'price_show'                => 'min:0',
            'price_del'                 => 'min:0',
            'days'                      => 'min:0',
            'nights'                    => 'min:0',
            'slug'                      => [
                'required',
                function($attribute, $value, $fail){
                    $slug           = !empty(request('slug')) ? request('slug') : null;
                    if(!empty($slug)){
                        $dataCheck  = DB::table('seo')
                                        ->join('combo_info', 'combo_info.seo_id', '=', 'seo.id')
                                        ->select('seo.slug', 'combo_info.id')
                                        ->where('slug', $slug)
                                        ->first();
                        if(!empty($dataCheck)){
                            if(empty(request('combo_info_id'))) $fail('Dường dẫn tĩnh đã trùng với một Chi tiết tour khác trên hệ thống!');
                        }
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
            'tour_departure_id.min'             => 'Điểm khởi hành không được để trống!',
            'price_show.min'                    => 'Giá hiển thị không được nhỏ hơn 0!',
            'price_del.min'                     => 'Giá cũ không được nhỏ hơn 0!',
            'departure_schedule.min'            => 'Thời gian khởi hành không được để trống!',
            'days.min'                          => 'Số ngày không được nhỏ hơn 0!',
            'nights.min'                        => 'Số đêm không được nhỏ hơn 0!',
            'title.required'                    => 'Tiêu đề trang không được để trống!',
            'title.max'                         => 'Tiêu đề trang không được vượt quá 255 ký tự!',
            'description'                       => 'Mô tả trang không được để trống!',
            'ordering.min'                      => 'Giá trị không được nhỏ hơn 0!',
            'seo_title.required'                => 'Tiêu đề SEO không được để trống!',
            'seo_description.required'          => 'Mô tả SEO không được để trống!',
            'slug.required'                     => 'Đường dẫn tĩnh không được để trống!',
            'rating_aggregate_count.required'   => 'Số lượt đánh giá không được để trống!',
            'rating_aggregate_star.required'    => 'Điểm đánh giá không được để trống!',
            'special_content.required'          => 'Điểm nổi bật Tour (dạng giới thiệu) không được bỏ trống!',
            'special_list.required'             => 'Điểm nổi bật Tour (dạng danh sách) không được bỏ trống!',
            'include.required'                  => 'Tour bao gồm không được bỏ trống!',
            'not_include.required'              => 'Tour không bao gồm không được bỏ trống!',
            'policy_child.required'             => 'Chính sách Tour trẻ em không được bỏ trống!',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Tour;

class ShipRequest extends FormRequest
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
                        $dataCheck  = Tour::getItemBySlug($slug);
                        if(!empty($dataCheck)&&$dataCheck->id_tour!=request('tour_info_id')) $fail('Dường dẫn tĩnh đã trùng với một Tour khác trên hệ thống!');
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

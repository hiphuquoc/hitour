<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Tour;

class TourBookingRequest extends FormRequest
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
            'prefix_name'               => 'required',
            'name'                      => 'required',
            'phone'                     => 'required',
            'tour_info_id'              => 'min:1',
            'tour_option_id'            => 'required',
            'departure_day'             => 'required'
        ];
    }

    public function messages()
    {
        return [
            'prefix_name.required'      => 'Danh xưng không được để trống!',
            'name.required'             => 'Tên khách hàng không được để trống!',
            'phone.required'            => 'Số điện thoại không được để trống!',
            'tour_info_id.min'          => 'Chương trình Tour không được để trống!',
            'tour_option_id.required'   => 'Tùy chọn Tour không được để trống!',
            'departure_day.required'    => 'Ngày khởi hành không được để trống!'
        ];
    }
}

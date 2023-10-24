@extends('main.layouts.main')
@push('head-custom')
    
@endpush
@section('content')

    @include('main.snippets.breadcrumb')

    <div class="pageConfirm background">
        <div class="sectionBox">
            <div class="container">
                <div class="bookingForm">
                    <!-- Tình trạng booking -->
                    <div class="bookingForm_item">
                        <div class="bookingForm_item_body successMessageBox">
                            <div class="successMessageBox_head">
                                <i class="fa-solid fa-check"></i>Đặt Phòng thành công!
                            </div>
                            <div class="successMessageBox_body">
                                <div>Quý khách vừa thực hiện đăng ký cho booking có MÃ: <span class="highLight">{{ $item->no ?? null }}</span></div>
                                <div>Tình trạng: <span class="badgeWait"><i class="fa-regular fa-clock"></i>Chờ nhân viên xác nhận</span></div>
                                <div class="noteWait">Booking của Quý khách đã được thông báo đến nhân viên {{ config('company.sortname') }}.<br/>Sau khi kiểm tra dịch vụ nhân viên sẽ gửi xác nhận vào Email hoặc Zalo của Quý khách.</div>
                            </div>
                        </div>
                    </div>
                    <!-- thông tin liên hệ -->
                    <div class="bookingForm_item">
                        <div class="bookingForm_item_head">
                            Thông tin liên hệ
                        </div>
                        <div class="bookingForm_item_body" style="padding:0;background:none;border:none;box-shadow:none;">
                            <table class="tableDetailShipBooking noResponsive">
                                <tbody>
                                    <tr>
                                        <td style="width:150px;">Tên khách hàng</td>
                                        <td>{{ $item->customer_contact->name ?? null }}</td>
                                    </tr>
                                    <tr>
                                        <td>Điện thoại</td>
                                        <td>{{ $item->customer_contact->phone ?? null }}</td>
                                    </tr>
                                    @if(!empty($item->customer_contact->zalo))
                                        <tr>
                                            <td>Zalo</td>
                                            <td>{{ $item->customer_contact->zalo ?? null }}</td>
                                        </tr>
                                    @endif
                                    @if(!empty($item->customer_contact->email))
                                        <tr>
                                            <td>Email</td>
                                            <td>{{ $item->customer_contact->email }}</td>
                                        </tr>
                                    @endif
                                    @foreach($item->request as $request)
                                        <tr>
                                            <td>{{ $request->name }}</td>
                                            <td>{{ $request->detail }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>                        
                    </div>
                    <!-- thông tin khách sạn -->
                    <div class="bookingForm_item">
                        <div class="bookingForm_item_head">
                            Thông tin khách sạn
                        </div>
                        <div class="bookingForm_item_body" style="padding:0;background:none;border:none;box-shadow:none;">
                            
                            @include('main.hotelBooking.hotelInfo', [
                                'hotel'     => $item->quantityAndPrice[0]->hotel,
                                'room'      => $item->quantityAndPrice[0]->room,
                                'price'     => $item->quantityAndPrice[0]->price,
                                'booking'   => $item,
                                'pageConfirm'   => true
                            ])

                        </div>                        
                    </div>
                    <!-- Thông tin liên hệ -->
                    <div class="bookingForm_item">
                        <div class="bookingForm_item_head">
                            Chi phí
                        </div>
                        <div class="bookingForm_item_body" style="padding:0;background:none;border:none;box-shadow:none;">
                            <table class="tableDetailShipBooking noResponsive">
                                <thead>
                                    <!-- Bảng tính tiền -->
                                    <tr>
                                        <th style="text-align:center;"><div>Dịch vụ</div></th>
                                        <th style="text-align:center;"><div>Đơn giá</div></th>
                                        <th style="text-align:center;"><div>Thành tiền</div></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $xhtmlTable = null;
                                        $total      = 0;
                                        foreach($item->quantityAndPrice as $quantityPrice){
                                            $xhtmlTable .= '<tr>
                                                                <td>'.$quantityPrice->hotel_room_name.'</td>
                                                                <td style="text-align:right;">'.$quantityPrice->quantity.' phòng * '.$quantityPrice->number_night.' đêm * '.number_format($quantityPrice->hotel_price_price).'</td>
                                                                <td style="text-align:right;">'.number_format($quantityPrice->quantity*$quantityPrice->number_night*$quantityPrice->hotel_price_price).'</td>
                                                            </tr>';
                                            $total  += $quantityPrice->quantity*$quantityPrice->number_night*$quantityPrice->hotel_price_price;
                                        }
                                    @endphp
                                    {!! $xhtmlTable !!}
                                    <tr>
                                        <td colspan="2">
                                        <div style="font-weight:500;">Tổng tiền:</div>
                                        </td>
                                        <td style="text-align:right;">
                                        <div style="font-weight:700;font-size:1.2rem;color:#E74C3C;letter-spacing:0.5px;">{!! number_format($total).config('main.unit_currency') !!}</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Quy trình đặt vé -->
                    <div class="bookingForm_item">
                        <div class="bookingForm_item_head">
                        Quy trình đặt Phòng
                        </div>
                        <div class="bookingForm_item_body" style="padding:0;background:none;border:none;box-shadow:none;">
                        <ul>
                            <li>Thông tin Booking đã được gửi thành công. Sau khi kiểm tra dịch vụ nhân viên sẽ gửi xác nhận vào Email hoặc Zalo của Quý khách.</li>
                            <li>Sau khi nhận được xác nhận. Quý khách vui lòng chuyển khoản cọc theo hướng dẫn có trong xác nhận.</li>
                            <li>Phiếu dịch vụ sẽ được gửi qua Email hoặc Zalo để Quý khách mở trên điện thoại xác nhận với lễ tân khi Check-in.</li>
                        </ul>
                        </div>
                    </div>
                    <!-- Button Quay lại -->
                    <div class="buttonBox">
                        <a href="/">
                        <button class="buttonCancel" type="button" aria-label="Quay lại trang chủ">
                        <i class="fa-solid fa-angles-left"></i>Quay lại trang chủ
                        </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts-custom')
    <script type="text/javascript">

    </script>
@endpush
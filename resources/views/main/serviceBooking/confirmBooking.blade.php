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
                    <div class="bookingForm_item_body successMessageBox">
                        <div class="successMessageBox_head">
                            <i class="fa-solid fa-check"></i>Đặt Dịch vụ thành công!
                        </div>
                        <div class="successMessageBox_body">
                            <div>Quý khách vừa thực hiện đăng ký cho booking có MÃ: <span class="highLight">{{ $item->no ?? null }}</span></div>
                            <div>Tình trạng: <span class="badgeWait"><i class="fa-regular fa-clock"></i>Chờ nhân viên xác nhận</span></div>
                            <div class="noteWait">Booking của Quý khách đã được thông báo đến nhân viên {{ config('company.sortname') }}.<br/>Sau khi kiểm tra dịch vụ nhân viên sẽ gửi xác nhận vào Email hoặc Zalo của Quý khách.</div>
                        </div>
                    </div>
                    
                    <div class="bookingForm_item">
                        <div class="bookingForm_item_head">
                            Thông tin chi tiết
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
                                    @if(!empty($item->service->serviceLocation->display_name))
                                        <tr>
                                            <td>Điểm đến</td>
                                            <td>{{ $item->service->serviceLocation->display_name }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td>Tên dịch vụ</td>
                                        <td><a href="{{ env('APP_URL').'/'.$item->service->seo->slug_full }}" style="color:rgba(0,123,255,1);text-decoration:none" target="_blank">{{ $item->service->name ?? null }}</a></td>
                                    </tr>
                                    <tr>
                                        <td>Loại</td>
                                        <td>{{ $item->quantityAndPrice[0]->option_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Ngày</td>
                                        <td>{{ \App\Helpers\DateAndTime::convertMktimeToDayOfWeek(strtotime($item->date_from)) }}, {{ date('d/m/Y', strtotime($item->date_from)) }}</td>
                                    </tr>
                                    @if(!empty($item->note_customer))
                                        <tr>
                                            <td>Ghi chú</td>
                                            <td>{{ $item->note_customer }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
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
                                                                <td>'.$quantityPrice->option_age.'</td>
                                                                <td style="text-align:right;">'.$quantityPrice->quantity.' * '.number_format($quantityPrice->price).'</td>
                                                                <td style="text-align:right;">'.number_format($quantityPrice->quantity*$quantityPrice->price).'</td>
                                                            </tr>';
                                            $total  += $quantityPrice->quantity*$quantityPrice->price;
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
                        Quy trình đặt dịch vụ
                        </div>
                        <div class="bookingForm_item_body" style="padding:0;background:none;border:none;box-shadow:none;">
                        <ul>
                            <li>Thông tin đã được gửi thành công. Quý khách vui lòng chờ nhân viên kiểm tra và gửi xác nhận vào Email hoặc Zalo sau.</li>
                            <li>Sau khi nhân viên gửi xác nhận Quý khách vui lòng chuyển khoản thanh toán theo hướng dẫn có trong xác nhận.</li>
                            <li>Code vé điện tử sẽ được gửi qua Email hoặc Zalo để Quý khách mở trên điện thoại quét mã vào cổng.</li>
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
@extends('main.layouts.main')
@push('head-custom')
    
@endpush
@section('content')

    @include('main.snippets.breadcrumb')

    <div class="pageContent">
        <div class="sectionBox">
            <div class="container">
            <!-- title -->
            <h1 class="titlePage" style="margin-bottom:1.5rem;text-align:center;">Đơn hàng thành công</h1>
            <!-- ship box -->
            <div class="pageContent_body">
                <div class="bookingForm">
                    <!-- Tình trạng booking -->
                    <div class="bookingForm_item">
                        <div class="bookingForm_item_body">
                        <div>Quý khách vừa thực hiện đăng ký cho đơn hàng có MÃ: <span style="font-size:1.2rem;font-weight:700;color:rgb(0, 90, 180);">{{ $item->no ?? null }}</span></div>
                        <div>Tình trạng đơn hàng: <span style="font-size:1.2rem;font-weight:700;color:#00C000;">Chờ nhân viên xác nhận</span></div>
                        <div>Đơn hàng của Quý khách đã được thông báo đến nhân viên Hitour. Sau khi kiểm tra xong nhân viên sẽ gửi xác nhận vào Email hoặc Zalo Quý khách đăng ký và liên hệ cho Quý khách.</div>
                        </div>
                    </div>
                    <div class="bookingForm_item">
                        <div class="bookingForm_item_head">
                            Chi tiết
                        </div>
                        <table class="tableDetailShipBooking noResponsive">
                            <tbody>
                                <tr>
                                    <td style="width:200px;">Tên khách hàng</td>
                                    <td>{{ $item->customer_contact->name ?? null }}</td>
                                </tr>
                                @if(!empty($item->customer_contact->zalo)&&$item->customer_contact->phone==$item->customer_contact->zalo)
                                    <tr>
                                        <td>Điện thoại</td>
                                        <td>{{ $item->customer_contact->phone ?? null }} (Zalo)</td>
                                    </tr>
                                @else 
                                    <tr>
                                        <td>Điện thoại</td>
                                        <td>{{ $item->customer_contact->phone ?? null }}</td>
                                    </tr>
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
                                <tr>
                                    <td>Tên dịch vụ</td>
                                    <td>{{ $item->tour->name ?? null }}</td>
                                </tr>
                                <tr>
                                    @php
                                        $dateTourEnd = date('d/m/Y', strtotime($item->departure_day) + ($item->tour->days-1)*86400);
                                    @endphp
                                    <td>Ngày</td>
                                    <td>{{ date('d/m/Y', strtotime($item->departure_day)) }} - {{ $dateTourEnd }}</td>
                                </tr>
                                <tr>
                                    <td>Tiêu chuẩn</td>
                                    <td>{{ $item->quantiesAndPrices[0]->option_name }}</td>
                                </tr>
                                <tr>
                                    @php
                                        $linkTour   = env('APP_URL').'/'.$item->tour->seo->slug_full;
                                    @endphp
                                    <td>Lịch trình</td>
                                    <td>
                                        <a href="{{ $linkTour }}" style="color:rgba(0,123,255,1);text-decoration:none" target="_blank">{{ $linkTour }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Điểm đón</td>
                                    <td>{{ $item->tour->pick_up }}</td>
                                </tr>
                                <tr>
                                    <td>Ghi chú</td>
                                    <td>{{ $item->note_customer ?? null }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Thông tin liên hệ -->
                    <div class="bookingForm_item">
                        <div class="bookingForm_item_head">
                            Chi phí
                        </div>
                        <div class="bookingForm_item_body" style="padding:0;background:none;border:none;box-shadow:none;">
                            <table class="tableDetailShipBooking noResponsive">
                                <tbody>
                                    <!-- Bảng tính tiền -->
                                    <tr class="head">
                                        <td style="text-align:center;"><div>Dịch vụ</div></td>
                                        <td style="text-align:center;"><div>Đơn giá</div></td>
                                        <td style="text-align:center;"><div>Thành tiền</div></td>
                                    </tr>
                                    @php
                                        $xhtmlTable = null;
                                        $total      = 0;
                                        foreach($item->quantiesAndPrices as $quantityPrice){
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
                        Quy trình đặt Tour
                        </div>
                        <div class="bookingForm_item_body" style="padding:0;background:none;border:none;box-shadow:none;">
                        <ul>
                            <li>Đơn hàng của Quý khách đã được gửi thành công. Quý khách vui lòng chờ nhân viên kiểm tra và gửi xác nhận vào Email hoặc Zalo sau.</li>
                            <li>Nếu chưa cung cấp thông tin hành hành khách Quý khách vui lòng chuẩn bị danh sách từng hành khách gồm: Họ tên đầy đủ + Năm sinh + Số một trong những giấy tờ tùy thân sau (Chứng minh nhân dân, Passport hoặc Bằng lái xe).</li>
                            <li>Sau khi nhân viên gửi xác nhận Quý khách vui lòng chuyển khoản cọc theo hướng dẫn có trong xác nhận.</li>
                            <li>Phiếu dịch vụ và Vé dịch vụ (tùy vào chương trình Tour) sẽ được gửi qua Email hoặc Zalo để Quý khách mở trên điện thoại xác nhận với nhân viên trong quá trình đón.</li>
                        </ul>
                        </div>
                    </div>
                    <!-- Button Quay lại -->
                    <div class="buttonBox">
                        <a href="/">
                        <button class="buttonSecondary" type="button" aria-label="Quay lại trang chủ">
                        <i class="fa-solid fa-angles-left"></i>Quay lại trang chủ
                        </button>
                        </a>
                    </div>
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
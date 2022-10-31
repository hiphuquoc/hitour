@extends('main.layouts.main')
@push('head-custom')
    
@endpush
@section('content')

    @include('main.snippets.breadcrumb')

    <div class="pageContent">
        <div class="container">
            <!-- title -->
            <h1 class="titlePage" style="margin-bottom:1.5rem;text-align:center;">Đơn hàng thành công</h1>
            <!-- ship box -->
            <div class="pageContent_body">
                <div class="bookingForm">
                    <!-- Tình trạng booking -->
                    <div class="bookingForm_item">
                        <div class="bookingForm_item_body">
                            <div>Quý khách vừa thực hiện đăng ký cho đơn hàng có MÃ: <span style="font-size:1.2rem;font-weight:700;color:rgb(0, 90, 180);">{{ $item->no ?? '-' }}</span></div>
                            <div>Tình trạng đơn hàng: <span style="font-size:1.2rem;font-weight:700;color:#00C000;">Chờ nhân viên xác nhận</span></div>
                            <div>Đơn hàng của Quý khách đã được thông báo đến nhân viên Hitour. Sau khi kiểm tra xong nhân viên sẽ gửi xác nhận vào Email hoặc Zalo Quý khách đăng ký và liên hệ cho Quý khách.</div>
                        </div>
                    </div>
                    <!-- Thông tin liên hệ -->
                    <div class="bookingForm_item">
                        <div class="bookingForm_item_head">
                            Thông tin booking
                        </div>
                        <div class="bookingForm_item_body" style="padding:0;background:none;border:none;box-shadow:none;">
                            <table class="tableDetailShipBooking">
                                <tbody>
                                    @if(!empty($item->customer->name))
                                        <tr>
                                            <td style="width:150px;">Họ tên</td>
                                            <td colspan="2">{{ $item->customer->name }}</td>
                                        </tr>
                                    @endif
                                    @if(!empty($item->customer->phone))
                                        @if(!empty($item->customer->zalo)&&$item->customer->zalo==$item->customer->phone)
                                            <tr>
                                                <td>Điện thoại</td>
                                                <td colspan="2">{{ $item->customer->phone }} (Zalo)</td>
                                            </tr>
                                        @else 
                                            <tr>
                                                <td>Điện thoại</td>
                                                <td colspan="2">{{ $item->customer->phone }}</td>
                                            </tr>
                                            @if(!empty($item->customer->zalo))
                                                <tr>
                                                    <td>Zalo</td>
                                                    <td colspan="2">{{ $item->customer->zalo }}</td>
                                                </tr>
                                            @endif
                                        @endif
                                    @endif
                                    @if(!empty($item->customer->email))
                                        <tr>
                                            <td>Email</td>
                                            <td colspan="2">{{ $item->customer->email }}</td>
                                        </tr>
                                    @endif
                                    <!-- Bảng tính tiền -->
                                    @php
                                        $total  = 0;
                                    @endphp
                                    @if(!empty($item->infoDeparture))
                                        @foreach($item->infoDeparture as $departure)
                                            <tr class="head">
                                                <td colspan="3">
                                                    <div><i class="fa-solid fa-ship"></i>Chuyến {{ $departure->departure }} - {{ $departure->location }}</div>
                                                </td>
                                            </tr>
                                            @if(!empty($departure->quantity_adult)&&!empty($departure->price_adult))
                                                <tr>
                                                    <td>Người lớn</td>
                                                    <td style="text-align:right;">{{ $departure->quantity_adult }} * {{ number_format($departure->price_adult) }}</td>
                                                    <td style="text-align:right;">{{ number_format($departure->quantity_adult*$departure->price_adult) }}</td>
                                                </tr>
                                                @php
                                                    $total  += $departure->quantity_adult*$departure->price_adult;
                                                @endphp
                                            @endif
                                            @if(!empty($departure->quantity_child)&&!empty($departure->price_child))
                                                <tr>
                                                    <td>Trẻ em 6-11 tuổi</td>
                                                    <td style="text-align:right;">{{ $departure->quantity_child }} * {{ number_format($departure->price_child) }}</td>
                                                    <td style="text-align:right;">{{ number_format($departure->quantity_child*$departure->price_child) }}</td>
                                                </tr>
                                                @php
                                                    $total  += $departure->quantity_child*$departure->price_child;
                                                @endphp
                                            @endif
                                            @if(!empty($departure->quantity_old)&&!empty($departure->price_old))
                                                <tr>
                                                    <td>Người trên 60 tuổi</td>
                                                    <td style="text-align:right;">{{ $departure->quantity_old }} * {{ number_format($departure->price_old) }}</td>
                                                    <td style="text-align:right;">{{ number_format($departure->quantity_old*$departure->price_old) }}</td>
                                                </tr>
                                                @php
                                                    $total  += $departure->quantity_old*$departure->price_old;
                                                @endphp
                                            @endif
                                        @endforeach
                                    @endif
                                    @if(!empty($total))
                                        <tr>
                                            <td colspan="2">
                                                <div style="font-weight:500;">Tổng tiền:</div>
                                            </td>
                                            <td style="text-align:right;">
                                                <div style="font-weight:700;font-size:1.2rem;color:#E74C3C;letter-spacing:0.5px;">{{ number_format($total).config('main.unit_currency') }}</div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Thông tin chuyến tàu -->
                    @if($item->infoDeparture->isNotEmpty())
                    <div class="bookingForm_item">
                        <div class="bookingForm_item_head">
                            Thông tin chuyến tàu
                        </div>
                        <div class="bookingForm_item_body" style="padding:0;background:none;border:none;box-shadow:none;">
                            <div class="shipDepartureConfirmBox">
                                @foreach($item->infoDeparture as $departure)
                                <div class="shipDepartureConfirmBox_item">
                                    <div class="shipDepartureConfirmBox_item_title">
                                        @if(!empty($departure->departure)&&!empty($departure->location)&&!empty($departure->partner_name))
                                            <div><span class="highLight">{{ $departure->departure }}</span> - <span class="highLight">{{ $departure->location }}</span> tàu {{ $departure->partner_name }}</div>
                                        @endif
                                        @if(!empty($departure->date))
                                            @php
                                                $dayOfWeek  = \App\Helpers\DateAndTime::convertMktimeToDayOfWeek(strtotime($departure->date));
                                            @endphp
                                            <div>Khởi hành <span class="highLight">{{ $dayOfWeek }}, {{ date('d/m/Y', strtotime($departure->date)) }}</span></div>
                                        @endif
                                    </div>
                                    <div class="shipDepartureConfirmBox_item_body">
                                        <div class="shipDepartureConfirmBox_item_body_item">
                                            <div class="timeShipDepartureBox">
                                                <div class="timeShipDepartureBox_departure">
                                                    @if(!empty($departure->time_departure))
                                                        <div>Xuất bến <span class="highLight">{{ $departure->time_departure }}</span></div>
                                                    @endif
                                                    @php
                                                        $arrayPort              = [];
                                                        if(!empty($departure->port_departure_address)) $arrayPort[]     = $departure->port_departure_address;
                                                        if(!empty($departure->port_departure_district)) $arrayPort[]    = $departure->port_departure_district;
                                                        if(!empty($departure->port_departure_province)) $arrayPort[]    = $departure->port_departure_province;
                                                        $fullAddressDeparture   = implode(', ', $arrayPort);
                                                    @endphp
                                                    <div class="highLight">{{ $departure->port_departure ?? '-' }}</div>
                                                    <div style="font-style:italic;">{{ $fullAddressDeparture }}</div>
                                                </div>
                                                <div class="timeShipDepartureBox_icon">
                                                    <i class="fas fa-angle-double-right" style="font-size:1.6rem;vertical-align:middle;"></i>
                                                </div>
                                                <div class="timeShipDepartureBox_departure">
                                                    @if(!empty($departure->time_arrive))
                                                        <div>Cập bến <span class="highLight">09:20</span></div>
                                                    @endif
                                                    @php
                                                        $arrayPort              = [];
                                                        if(!empty($departure->port_location_address)) $arrayPort[]     = $departure->port_location_address;
                                                        if(!empty($departure->port_location_district)) $arrayPort[]    = $departure->port_location_district;
                                                        if(!empty($departure->port_location_province)) $arrayPort[]    = $departure->port_location_province;
                                                        $fullAddressLocation    = implode(', ', $arrayPort);
                                                    @endphp
                                                    <div class="highLight">{{ $departure->port_location ?? '-' }}</div>
                                                    <div style="font-style:italic;">{{ $fullAddressLocation }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="shipDepartureConfirmBox_item_body_item">
                                            @php
                                                $typeTicket     = '-';
                                                if(!empty($departure->type)){
                                                    $typeTicket = strtoupper($departure->type);
                                                }
                                            @endphp
                                            @if(!empty($departure->quantity_adult))
                                                <div><span class="highLight">{{ $departure->quantity_adult }}</span> người lớn ({{ $typeTicket }})</div>
                                            @endif
                                            @if(!empty($departure->quantity_child))
                                                <div><span class="highLight">{{ $departure->quantity_child }}</span> trẻ em 6-11 tuổi ({{ $typeTicket }})</div>
                                            @endif
                                            @if(!empty($departure->quantity_old))
                                                <div><span class="highLight">{{ $departure->quantity_old }}</span> người trên 60 tuổi ({{ $typeTicket }})</div>
                                            @endif
                                        </div>
                                        <div class="shipDepartureConfirmBox_item_body_item">
                                            <div>Danh sách hành khách cập nhật sau</div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                    <!-- Quy trình đặt vé -->
                    <div class="bookingForm_item">
                        <div class="bookingForm_item_head">
                            Quy trình đặt vé
                        </div>
                        <div class="bookingForm_item_body" style="padding:0;background:none;border:none;box-shadow:none;">
                            <ul>
                                <li>Đơn hàng của Quý khách đã được gửi thành công. Quý khách vui lòng chờ nhân viên kiểm tra và gửi xác nhận vào Email hoặc Zalo sau.</li>
                                <li>Nếu chưa cung cấp thông tin hành hành khách Quý khách vui lòng chuẩn bị danh sách từng hành khách gồm: Họ tên đầy đủ + Năm sinh + Số một trong những giấy tờ tùy thân sau (Chứng minh nhân dân hoặc Passport hoặc Bằng lái xe).</li>
                                <li>Sau khi nhân viên gửi xác nhận Quý khách vui lòng chuyển khoản theo hướng dẫn có trong xác nhận.</li>
                                <li>Vé điện tử sẽ được gửi qua Email hoặc Zalo để Quý khách mở tên điện thoại hoặc in ra dùng để quét mã lên tàu.</li>
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

@endsection
@push('scripts-custom')
    <script type="text/javascript">

    </script>
@endpush
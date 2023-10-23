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
                                <i class="fa-solid fa-check"></i>Đặt Vé Tàu thành công!
                            </div>
                            <div class="successMessageBox_body">
                                <div>Quý khách vừa thực hiện đăng ký cho booking có MÃ: <span class="highLight">{{ $item->no ?? null }}</span></div>
                                <div>Tình trạng: <span class="badgeWait"><i class="fa-regular fa-clock"></i>Chờ nhân viên xác nhận</span></div>
                                <div class="noteWait">Booking của Quý khách đã được thông báo đến nhân viên {{ config('company.sortname') }}.<br/>Sau khi kiểm tra dịch vụ nhân viên sẽ gửi xác nhận vào Email hoặc Zalo của Quý khách.</div>
                            </div>
                        </div>
                    </div>
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
                                            <td>{{ $item->customer_contact->zalo }}</td>
                                        </tr>
                                    @endif
                                    @if(!empty($item->customer_contact->email))
                                        <tr>
                                            <td>Email</td>
                                            <td>{{ $item->customer_contact->email }}</td>
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
                            <table class="tableDetailShipBooking noResponsive" style="border-radius:10px;overflow:hidden;">
                                {{-- <thead>

                                </thead> --}}
                                <tbody>
                                    <!-- Bảng tính tiền -->
                                    @php
                                        $total  = 0;
                                    @endphp
                                    @if(!empty($item->infoDeparture))
                                        @foreach($item->infoDeparture as $departure)
                                            <tr style="background:#EDF2F7;">
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
                                        @include('main.shipBooking.tableDeparture', compact('departure'))
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
                                <li>Thông tin đã được gửi thành công. Quý khách vui lòng chờ nhân viên kiểm tra và gửi xác nhận vào Email hoặc Zalo sau.</li>
                                <li>Nếu chưa cung cấp thông tin hành hành khách Quý khách vui lòng chuẩn bị danh sách từng hành khách gồm: Họ tên đầy đủ + Năm sinh + Số một trong những giấy tờ tùy thân sau (Chứng minh nhân dân, Căn cước công dân, Passport hoặc Bằng lái xe).</li>
                                <li>Sau khi nhân viên gửi xác nhận Quý khách vui lòng chuyển khoản theo hướng dẫn có trong xác nhận.</li>
                                <li>Vé điện tử sẽ được gửi qua Email hoặc Zalo để Quý khách mở trên điện thoại và quét mã lên tàu.</li>
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
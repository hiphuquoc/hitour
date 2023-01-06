@php
    use App\Helpers\DateAndTime;
    $stt = 1;
@endphp
<!-- Hiển thị thông tin booking -->
@if(!empty($item))
<table class="sendEmail" style="font-family:'Roboto',Montserrat,-apple-system,'Segoe UI',sans-serif;width:100%;background-color:#EDF2F7;" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tbody>
        <tr>
            <td align="center" style="font-family:'Roboto',Montserrat,-apple-system,'Segoe UI',sans-serif;">
                <table class="sendEmail" role="presentation" style="border-collapse:collapse;background:#ffffff;border-radius:3px;width:100%;max-width:640px;margin:20px auto 40px auto;">
                    <tbody>
                        <tr>
                            <td style="box-sizing:border-box;padding:20px 15px 15px 15px;line-height:1">
                                <div style="text-align:center">
                                    <img width="70px" src="{{ config('main.logo_square') }}" style="display:inline-block;width:70px;">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="box-sizing:border-box;text-align:center;line-height:1.68;color:#456;padding:0 15px 0 15px;">
                                @php
                                    /* Thời gian book */
                                    $dayOfWeekBooking           = DateAndTime::convertMktimeToDayOfWeek(strtotime($item->created_at));
                                    $bookingAt                  = 'Đặt lúc '.date('H:i', strtotime($item->created_at)).' '.$dayOfWeekBooking.', '.date('d/m/Y', strtotime($item->created_at));
                                    /* Thời hạn booking */
                                    $expirationAt               = null;
                                    if(!empty($item->expiration_at)){
                                        $dayOfWeekExpiration    = DateAndTime::convertMktimeToDayOfWeek(strtotime($item->expiration_at));
                                        $expirationAt           = '<div style="text-align:right;margin-right:10px;font-size:15px;">
                                                                Hết hạn '.date('H:i', strtotime($item->expiration_at)).' '.$dayOfWeekExpiration.', '.date('d/m/Y', strtotime($item->expiration_at)).'</div>';
                                    }
                                    /* Nhân viên xác nhận */
                                    $staffSupport               = null;
                                    if(!empty($infoStaff)){
                                        $staffSupport           = '<div style="text-align:right;margin-right:10px;font-size:15px;">Nhân viên hỗ trợ: '.$infoStaff->firstname.' '.$infoStaff->lastname.' - '.$infoStaff->phone.'</div>';
                                    }
                                @endphp
                                <h1 style="font-size:20px;font-weight:bold;color:#345;margin-bottom:15px;margin-top:0;">XÁC NHẬN DỊCH VỤ</h1>
                                <div style="text-align:right;margin-right:10px;font-size:15px;">{{ $bookingAt }}</div>
                                {!! $expirationAt !!}
                                {!! $staffSupport !!}
                            </td>
                        </tr>
                        <tr>
                            <td style="box-sizing:border-box;padding:10px 15px">
                                <table class="sendEmail" role="presentation" style="border-collapse:collapse;width:100%;font-size:15px;line-height:1.68;color:#456">
                                    <tbody>
                                        <tr>
                                            <td style="width:140px;font-size:15px;padding:5px;">Mã xác nhận</td>
                                            <td style="padding:5px;font-weight:bold;color:rgba(0,123,255,1);font-size:20px;">{{ $item->no ?? null }}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:15px;padding:5px;">Tên khách hàng</td>
                                            <td style="font-weight:bold;font-size:15px;padding:5px;">{{ $item->customer_contact->name }}</td>
                                        </tr>
                                        @if(!empty($item->customer_contact->phone))
                                            <tr>
                                                <td style="font-size:15px;padding:5px;">Điện thoại</td>
                                                <td style="font-weight:bold;font-size:15px;padding:5px;">{{ $item->customer_contact->phone }}</td>
                                            </tr>
                                        @endif
                                        @if(!empty($item->customer_contact->zalo))
                                            <tr>
                                                <td style="font-size:15px;padding:5px;">Zalo</td>
                                                <td style="font-weight:bold;font-size:15px;padding:5px;">{{ $item->customer_contact->zalo }}</td>
                                            </tr>
                                        @endif
                                        @if(!empty($item->customer_contact->email))
                                            <tr>
                                                <td style="font-size:15px;padding:5px;">Email</td>
                                                <td style="font-weight:bold;font-size:15px;padding:5px;">{{ $item->customer_contact->email }}</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <!-- GIÁ DỊCH VỤ & SỐ LƯỢNG -->
                        @if($item->infoDeparture->isNotEmpty())
                        <tr>
                            <td style="box-sizing:border-box;font-weight:bold;padding:10px 15px 5px 15px;">
                                <div style="font-size:18px;font-weight:bold;color:#345;">{{ $stt }}. Chi tiết giá</div>
                                @php
                                    ++$stt;
                                @endphp
                            </td>
                        </tr>
                        <tr>
                            <td style="box-sizing:border-box;padding:5px 15px;">
                                <table class="sendEmail" role="presentation" style="border-collapse:collapse;width:100%;line-height:1.68;font-size:15px;color:#456;border:1px solid #d1d1d1;">
                                    <thead>
                                        <tr style="background:rgba(0,123,255,0.4);text-align:center;font-size:14px;font-weight:bold;">
                                            <td style="font-size:15px;padding:7px 12px;">Dịch vụ</td>
                                            <td style="font-size:15px;padding:7px 12px;border-left:1px solid #d1d1d1;">Đơn giá</td>
                                            <td style="font-size:15px;padding:7px 12px;border-left:1px solid #d1d1d1;">Thành tiền</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Thành tiền chính -->
                                        @php
                                            $total          = 0;
                                        @endphp     
                                        @foreach($item->infoDeparture as $departure)
                                            @if(!empty($departure->departure)&&!empty($departure->location))
                                                <tr>
                                                    <td colspan="3" style="font-size:15px;padding:7px 12px !important;background:#EDF2F7;font-weight:bold;">Chuyến {{ $departure->departure }} - {{ $departure->location }}</td>
                                                </tr>
                                            @endif
                                            @if(!empty($departure->quantity_adult)&&!empty($departure->price_adult))
                                                <tr>
                                                    <td style="font-size:15px;padding:7px 12px !important;border-top:1px dashed #d1d1d1;">Người lớn</td>
                                                    <td style="font-size:15px;padding:7px 12px !important;text-align:right;border-left:1px dashed #d1d1d1;border-top:1px dashed #d1d1d1;">{{ $departure->quantity_adult }} * {{ number_format($departure->price_adult) }}</td>
                                                    <td style="font-size:15px;padding:7px 12px !important;text-align:right;border-left:1px dashed #d1d1d1;border-top:1px dashed #d1d1d1;">{{ number_format($departure->quantity_adult*$departure->price_adult) }}</td>
                                                </tr>
                                                @php
                                                    $total  += $departure->quantity_adult*$departure->price_adult;
                                                @endphp
                                            @endif
                                            @if(!empty($departure->quantity_child)&&!empty($departure->price_child))
                                                <tr>
                                                    <td style="font-size:15px;padding:7px 12px !important;border-top:1px dashed #d1d1d1;">Trẻ em</td>
                                                    <td style="font-size:15px;padding:7px 12px !important;text-align:right;border-left:1px dashed #d1d1d1;border-top:1px dashed #d1d1d1;">{{ $departure->quantity_child }} * {{ number_format($departure->price_child) }}</td>
                                                    <td style="font-size:15px;padding:7px 12px !important;text-align:right;border-left:1px dashed #d1d1d1;border-top:1px dashed #d1d1d1;">{{ number_format($departure->quantity_child*$departure->price_child) }}</td>
                                                </tr>
                                                @php
                                                    $total  += $departure->quantity_child*$departure->price_child;
                                                @endphp
                                            @endif
                                            @if(!empty($departure->quantity_old)&&!empty($departure->price_old))
                                                <tr>
                                                    <td style="font-size:15px;padding:7px 12px !important;border-top:1px dashed #d1d1d1;">Cao tuổi</td>
                                                    <td style="font-size:15px;padding:7px 12px !important;text-align:right;border-left:1px dashed #d1d1d1;border-top:1px dashed #d1d1d1;">{{ $departure->quantity_old }} * {{ number_format($departure->price_old) }}</td>
                                                    <td style="font-size:15px;padding:7px 12px !important;text-align:right;border-left:1px dashed #d1d1d1;border-top:1px dashed #d1d1d1;">{{ number_format($departure->quantity_old*$departure->price_old) }}</td>
                                                </tr>
                                                @php
                                                    $total  += $departure->quantity_old*$departure->price_old;
                                                @endphp
                                            @endif
                                        @endforeach
                                        <!-- Thành tiền phát sinh và trừ lại -->
                                        @if(!empty($item->costMoreLess)&&$item->costMoreLess->isNotEmpty())
                                            @foreach($item->costMoreLess as $cost)
                                                @php
                                                    $total  += $cost->value;
                                                @endphp 
                                                <tr style="background:#EDF2F7;">
                                                    <td style="font-size:15px;padding:5px 10px;border-top:1px dashed #d1d1d1;" colspan="2">{{ $cost->name }}</td>
                                                    <td style="font-size:15px;padding:5px 10px;text-align:right;border-left:1px dashed #d1d1d1;border-top:1px dashed #d1d1d1;">{{ number_format($cost->value) }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        <!-- Tổng -->
                                        <tr>
                                            <td colspan="2" style="font-size:15px;padding:7px 12px !important;text-align:center;border-top:1px dashed #d1d1d1;font-weight:bold;">Tổng</td>
                                            <td style="font-size:18px;padding:7px 12px !important;text-align:right;border-left:1px dashed #d1d1d1;border-top:1px dashed #d1d1d1;color:#E74C3C;font-weight:bold;">{{ number_format($total).config('main.unit_currency') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="sendEmail" role="presentation" style="border-collapse:collapse;width:100%;line-height:1.68;font-size:15px;color:#456;">
                                    <tbody>
                                        <tr>
                                            <td colspan="3" style="font-size:15px;padding:7px 12px !important;text-align:center;font-style:italic;font-size:14px;">Giá trên chưa gồm VAT 10% (nếu lấy hóa đơn)</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        @endif
                        <!-- THÔNG TIN CHUYẾN ĐI -->
                        @if(!empty($item->infoDeparture)&&$item->infoDeparture->isNotEmpty())
                            @foreach($item->infoDeparture as $departure)
                                <tr>
                                    <td style="box-sizing:border-box;font-weight:bold;padding:10px 15px 5px 15px;">
                                        <div style="font-size:18px;font-weight:bold;color:#345;">{{ $stt }}. Thông tin chuyến {{ $loop->index==0 ? 'đi' : 'về' }}</div>
                                        @php
                                            ++$stt;
                                        @endphp
                                    </td>
                                </tr>
                                <tr>
                                    <td style="box-sizing:border-box;padding:5px 15px 20px 15px;">
                                        <table width="100%" style="border:2px dashed #c1c1c1;border-collapse:collapse;font-size:15px;line-height:1.48">
                                            <tbody>
                                                <tr>
                                                    <td colspan="2" style="display:flex;padding:7px 12px !important;border:1px dashed #d1d1d1">
                                                        <div style="width:calc(50% - 30px);display:inline-block;vertical-align:top">
                                                            <div style="font-size:15px;font-weight:normal;color:#456"><span style="font-size:18px;font-weight:bold;color:rgb(0,90,180);text-transform:capitalize;">{{ $departure->departure ?? '-' }}</span>, {{ $departure->port_departure_province ?? '-' }}</div>
                                                            <div><span style="font-size:18px;font-weight:bold;color:rgb(0,90,180);text-transform:capitalize;">{{ $departure->time_departure ?? '-' }}</span> Xuất bến tại {{ $departure->port_departure ?? '-' }}, {{ $departure->port_departure_address ?? '-' }}</div>
                                                        </div>
                                                        <div style="width:60px;margin-top:10px;display:inline-block;vertical-align:top;margin-left:15px;">
                                                            <img src="{{ config('main.icon-arrow-email') }}" style="width:100%;">
                                                        </div>
                                                        <div style="width:calc(50% - 30px);display:inline-block;vertical-align:top;margin-left:15px;">
                                                            <div style="font-size:15px;font-weight:normal;color:#456"><span style="font-size:18px;font-weight:bold;color:rgb(0,90,180);text-transform:capitalize;">{{ $departure->location ?? '-' }}</span>, {{ $departure->port_location_province ?? '-' }}</div>
                                                            <div><span style="font-size:18px;font-weight:bold;color:rgb(0,90,180);text-transform:capitalize;">{{ $departure->time_arrive ?? '-' }}</span> Cập bến tại {{ $departure->port_location ?? '-' }}, {{ $departure->port_location_address ?? '-' }}</div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="padding:7px 12px !important;border:1px dashed #d1d1d1">
                                                        <span style="width:70px;display:inline-block;">Ngày</span> : <span style="font-weight:bold;color:rgb(0,90,180);">{{ \App\Helpers\DateAndTime::convertMktimeToDayOfWeek(strtotime($departure->date)) }}, ngày {{ date('d/m/Y', strtotime($departure->date)) }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="padding:7px 12px !important;border:1px dashed #d1d1d1">
                                                        <span style="width:70px;display:inline-block;">Hãng tàu</span> : <span style="font-weight:bold;color:rgb(0,90,180);">{{ $departure->partner_name ?? '-' }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="padding:7px 12px !important;border:1px dashed #d1d1d1">
                                                        @php
                                                            $tmp    = [];
                                                            if(!empty($departure->quantity_adult)) $tmp[] = '<span style="font-size:18px;font-weight:bold;color:rgb(0,90,180);text-transform:capitalize;">'.$departure->quantity_adult.'</span> người lớn';
                                                            if(!empty($departure->quantity_child)) $tmp[] = '<span style="font-size:18px;font-weight:bold;color:rgb(0,90,180);text-transform:capitalize;">'.$departure->quantity_child.'</span> trẻ em 6-11 tuổi';
                                                            if(!empty($departure->quantity_old)) $tmp[] = '<span style="font-size:18px;font-weight:bold;color:rgb(0,90,180);text-transform:capitalize;">'.$departure->quantity_old.'</span> người trên 60 tuổi';
                                                            $xhtmlQuantity = implode(', ', $tmp);
                                                        @endphp
                                                        <span style="width:70px;display:inline-block;">Số lượng</span> : <span>{!! $xhtmlQuantity !!}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="padding:7px 12px !important;border:1px dashed #d1d1d1">
                                                        <span style="width:70px;display:inline-block;">Loại vé</span> : <span style="font-weight:bold;color:rgb(0,90,180);text-transform:capitalize;">{{ $departure->type ?? '-' }}</span>
                                                    </td>
                                                </tr>
                                                
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        @if(!empty($type)&&$type=='notice')
                            <!-- nút quản lí booking nếu là thông báo -->
                            <tr>
                                <td style="text-align:center;padding:20px 0 30px 0;">
                                    <a href="{{ route('admin.shipBooking.viewExport', ['id' => $item->id]) }}" style="background:rgb(0,123,255);color:#fff;padding:8px 15px;border-radius:5px;font-weight:600;margin:0 auto;text-decoration:none;font-size:15px;">Quản lí Booking</a>
                                </td>
                            </tr>
                        @else
                            <!-- Thông tin chính sách nếu là gửi cho khách -->
                            @include('admin.shipBooking.policyShipBooking')
                        @endif
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
@endif
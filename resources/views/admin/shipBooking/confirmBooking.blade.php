@php
    use App\Helpers\DateAndTime;
@endphp

@if(!empty($item))
<table class="sendEmail" style="font-family:'Roboto',Montserrat,-apple-system,'Segoe UI',sans-serif;width:100%;" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tbody>
        <tr>
            <td align="center" style="background-color:#f8f8f8;font-family:'Roboto',Montserrat,-apple-system,'Segoe UI',sans-serif;">
                <table class="sendEmail" role="presentation" style="border-collapse:collapse;background:#ffffff;border-radius:3px;width:100%;max-width:640px;margin:20px auto;box-shadow:0 0 10px #e9ecef;">
                    <tbody>
                        <tr>
                            <td style="box-sizing:border-box;padding:15px 20px 10px 20px;line-height:1">
                                <div style="text-align:center">
                                    <img width="70px" src="https://ci4.googleusercontent.com/proxy/gmLnVCmKn0KPiXsR__Yktg8nixAUBimU4Sx7E-Fe1iBjj7Fx5i3Hf6_yGesB-l31oxZwotYGw56pkw027YNxpJBUigC2uovspw=s0-d-e1-ft#https://hitour.vn/public/image/logo-hitour-200x200.png" style="display:inline-block;width:70px;" class="CToWUd" data-bit="iit">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="box-sizing:border-box;text-align:center;line-height:1.68;color:#456;padding:10px 15px 0 15px;">
                                @php
                                    $dayOfWeekBooking           = DateAndTime::convertMktimeToDayOfWeek(strtotime($item->created_at));
                                    $bookingAt                  = 'Đặt lúc '.date('H:i', strtotime($item->created_at)).' '.$dayOfWeekBooking.', '.date('d/m/Y', strtotime($item->created_at));
                                    $expirationAt               = null;
                                    if(!empty($item->expiration_at)){
                                        $dayOfWeekExpiration    = DateAndTime::convertMktimeToDayOfWeek(strtotime($item->expiration_at));
                                        $expirationAt           = '<div style="text-align:right;margin-right:10px;font-size:15px;">
                                                                Hết hạn '.date('H:i', strtotime($item->expiration_at)).' '.$dayOfWeekExpiration.', '.date('d/m/Y', strtotime($item->expiration_at)).'</div>';
                                    }
                                @endphp
                                <h1 style="font-size:20px;font-weight:bold;color:#345;">XÁC NHẬN DỊCH VỤ</h1>
                                <div style="text-align:right;margin-right:10px;font-size:15px;">{{ $bookingAt }}</div>
                                {{ $expirationAt }}
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
                            <td style="box-sizing:border-box;font-weight:bold;text-align:center;padding:10px 15px 5px 15px;">
                                <div style="font-size:18px;font-weight:bold;color:#345;">CHI TIẾT GIÁ DỊCH VỤ</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="box-sizing:border-box;padding:5px 15px;">
                                <table class="sendEmail" role="presentation" style="border-collapse:collapse;width:100%;line-height:1.68;font-size:15px;color:#456;border:1px solid #d1d1d1;">
                                    <thead>
                                        <tr style="background:rgba(0,123,255,0.4);text-align:center;font-size:14px;">
                                            <td style="font-size:15px;padding:5px;">Dịch vụ</td>
                                            <td style="font-size:15px;padding:5px;border-left:1px solid #d1d1d1;">Đơn giá</td>
                                            <td style="font-size:15px;padding:5px;border-left:1px solid #d1d1d1;">Thành tiền</td>
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
                                                    <td colspan="3" style="font-size:15px;padding:5px 10px;background:#f1f1f1;">Chuyến {{ $departure->departure }} - {{ $departure->location }}</td>
                                                </tr>
                                            @endif
                                            @if(!empty($departure->quantity_adult)&&!empty($departure->price_adult))
                                                <tr>
                                                    <td style="font-size:15px;padding:5px 10px;border-top:1px dashed #d1d1d1;">Người lớn</td>
                                                    <td style="font-size:15px;padding:5px 10px;text-align:right;border-left:1px dashed #d1d1d1;border-top:1px dashed #d1d1d1;">{{ $departure->quantity_adult }} * {{ number_format($departure->price_adult) }}</td>
                                                    <td style="font-size:15px;padding:5px 10px;text-align:right;border-left:1px dashed #d1d1d1;border-top:1px dashed #d1d1d1;">{{ number_format($departure->quantity_adult*$departure->price_adult) }}</td>
                                                </tr>
                                                @php
                                                    $total  += $departure->quantity_adult*$departure->price_adult;
                                                @endphp
                                            @endif
                                            @if(!empty($departure->quantity_child)&&!empty($departure->price_child))
                                                <tr>
                                                    <td style="font-size:15px;padding:5px 10px;border-top:1px dashed #d1d1d1;">Trẻ em</td>
                                                    <td style="font-size:15px;padding:5px 10px;text-align:right;border-left:1px dashed #d1d1d1;border-top:1px dashed #d1d1d1;">{{ $departure->quantity_child }} * {{ number_format($departure->price_child) }}</td>
                                                    <td style="font-size:15px;padding:5px 10px;text-align:right;border-left:1px dashed #d1d1d1;border-top:1px dashed #d1d1d1;">{{ number_format($departure->quantity_child*$departure->price_child) }}</td>
                                                </tr>
                                                @php
                                                    $total  += $departure->quantity_child*$departure->price_child;
                                                @endphp
                                            @endif
                                            @if(!empty($departure->quantity_old)&&!empty($departure->price_old))
                                                <tr>
                                                    <td style="font-size:15px;padding:5px 10px;border-top:1px dashed #d1d1d1;">Cao tuổi</td>
                                                    <td style="font-size:15px;padding:5px 10px;text-align:right;border-left:1px dashed #d1d1d1;border-top:1px dashed #d1d1d1;">{{ $departure->quantity_old }} * {{ number_format($departure->price_old) }}</td>
                                                    <td style="font-size:15px;padding:5px 10px;text-align:right;border-left:1px dashed #d1d1d1;border-top:1px dashed #d1d1d1;">{{ number_format($departure->quantity_old*$departure->price_old) }}</td>
                                                </tr>
                                                @php
                                                    $total  += $departure->quantity_old*$departure->price_old;
                                                @endphp
                                            @endif
                                        @endforeach
                                        <!-- Tổng -->
                                        <tr>
                                            <td colspan="2" style="font-size:15px;padding:5px 10px;text-align:center;border-top:1px dashed #d1d1d1;">Tổng</td>
                                            <td style="font-size:15px;padding:5px 10px;text-align:right;border-left:1px dashed #d1d1d1;border-top:1px dashed #d1d1d1;color:#E74C3C;font-weight:bold;">{{ number_format($total).config('main.unit_currency') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="sendEmail" role="presentation" style="border-collapse:collapse;width:100%;line-height:1.68;font-size:15px;color:#456;">
                                    <tbody>
                                        <tr>
                                            <td colspan="3" style="font-size:15px;padding:5px 10px;text-align:center;font-style:italic;font-size:14px;">Giá trên chưa gồm VAT 10% (nếu lấy hóa đơn)</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        @endif
                        <!-- DANH SÁCH HÀNH KHÁCH -->
                        <tr>
                            <td style="box-sizing:border-box;font-weight:bold;text-align:center;padding:10px 15px 5px 15px;">
                                <div style="font-size:18px;font-weight:bold;color:#345;">DANH SÁCH HÀNH KHÁCH</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="box-sizing:border-box;padding:5px 15px;">
                                <table class="sendEmail" role="presentation" style="border-collapse:collapse;width:100%;line-height:1.68;font-size:15px;color:#456;border:1px solid #d1d1d1;">
                                    <thead>
                                        <tr style="background:rgba(0,123,255,0.4);text-align:center;font-size:14px;">
                                            <td style="font-size:15px;padding:5px;width:50px;">STT</td>
                                            <td style="font-size:15px;padding:5px;border-left:1px dashed #d1d1d1;">Họ tên</td>
                                            <td style="font-size:15px;padding:5px;border-left:1px solid #d1d1d1;">Số CMND</td>
                                            <td style="font-size:15px;padding:5px;border-left:1px solid #d1d1d1;">Năm sinh</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($item->customer_list->isNotEmpty())
                                            @foreach($item->customer_list as $infoCustomer)
                                                <tr>
                                                    <td style="font-size:15px;padding:5px 10px;border-top:1px dashed #d1d1d1;text-align:center;">{{ $loop->index+1 }}</td>
                                                    <td style="font-size:15px;padding:5px 10px;border-left:1px dashed #d1d1d1;border-top:1px dashed #d1d1d1;">{{ $infocustomer_contact->customer_name }}</td>
                                                    <td style="font-size:15px;padding:5px 10px;text-align:right;border-left:1px dashed #d1d1d1;border-top:1px dashed #d1d1d1;">{{ $infocustomer_contact->customer_year_of_birth }}</td>
                                                    <td style="font-size:15px;padding:5px 10px;text-align:right;border-left:1px dashed #d1d1d1;border-top:1px dashed #d1d1d1;">{{ $infocustomer_contact->customer_identity ?? 'Chưa có'  }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4" style="font-size:15px;padding:5px 10px;border-top:1px dashed #d1d1d1;text-align:center;">Chưa cập nhật!</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                <table class="sendEmail" role="presentation" style="border-collapse:collapse;width:100%;line-height:1.68;font-size:15px;color:#456;">
                                    <tbody>
                                        <tr>
                                            <td colspan="3" style="font-size:15px;padding:5px 10px;text-align:center;font-style:italic;font-size:14px;">Quý khách cập nhật Danh sách hành khách bằng cách soạn mail đơn giản gồm: Họ Tên - Năm sinh - Số CMND/CCCD từng hành khách và trả lời email này</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <!-- STATUS -->
                        <tr>
                            <td style="box-sizing:border-box;font-weight:bold;padding:10px 15px 0 15px;">
                                <div style="font-size:18px;font-weight:bold;color:#345;">Yêu cầu:</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="box-sizing:border-box;padding:0 15px 5px 15px;">
                                <table class="sendEmail" role="presentation" style="border-collapse:collapse;width:100%;line-height:1.68;font-size:15px;color:#456;">
                                    <tbody>
                                        <tr style="border-bottom:1px dotted #d1d1d1;color:#456;">
                                            <td style="padding:5px;">
                                                <div>
                                                    1. Nếu chưa cung cấp thông tin từng người đi, quý khách cập nhật bằng cách trả lời email này
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="border-bottom:1px dotted #d1d1d1;color:#456;">
                                            <td style="padding:5px;">
                                                @php
                                                    $deadline       = '...';
                                                    if(!empty($item->expiration_at)){
                                                        $hour       = date('H:i', strtotime($item->expiration_at));
                                                        $dayOfWeek  = \App\Helpers\DateAndTime::convertMktimeToDayOfWeek(strtotime($item->expiration_at));
                                                        $date       = date('d/m/Y', strtotime($item->expiration_at));
                                                        $deadline   = 'trước '.$hour.' '.$dayOfWeek.', ngày '.$date;
                                                    }
                                                @endphp
                                                <div>
                                                    2. Quý khách chuyển khoản theo thông tin bên dưới <span style="font-weight:bold;font-size:16px;">{{ $deadline }}</span>, trong nội dung chuyển khoản ghi <span style="font-weight:bold;font-size:16px;">{{ $item->customer_contact->phone }}</span>
                                                </div>
                                                @php
                                                    $dataBank = config('company.bank');
                                                    
                                                @endphp
                                                @foreach(config('company.bank') as $bank)
                                                    <div>
                                                        <div><b>{{ $bank['title'] }}</b></div>
                                                        <div>Tên TK: {{ $bank['name'] }}</div>
                                                        <div>Số TK: {{ $bank['number'] }}</div>
                                                        <div>Chi nhánh: {{ $bank['department'] }}</div>
                                                    </div>
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr style="color:#456;">
                                            <td style="padding:5px;">
                                                <div>3. Nếu có yêu cầu xuất hóa đơn, quý khách vui lòng cung cấp thông tin xuất hóa đơn bằng cách trả lời email này (chậm nhất trước 48 giờ kể từ lúc nhận được vé điện tử)</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>

                        </tr>
                        <!-- CHÍNH SÁCH -->
                        <tr>
                            <td style="box-sizing:border-box;font-weight:bold;padding:10px 15px 0 15px;">
                                <div style="font-size:18px;font-weight:bold;color:#345;">Chính sách hoàn - hủy:</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="box-sizing:border-box;padding:0 15px 5px 15px;">
                                <table class="sendEmail" role="presentation" style="border-collapse:collapse;width:100%;line-height:1.68;font-size:15px;color:#456;">
                                    <tbody>
                                        @foreach(config('company.cancelShipTicket') as $policy)
                                            <tr style="border-bottom:1px dotted #d1d1d1;color:#456;">
                                                <td style="padding:5px;">
                                                    <div>
                                                        {!! $policy !!}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <!-- GHI CHÚ -->
                        <tr>
                            <td style="box-sizing:border-box;font-weight:bold;padding:10px 15px 0 15px;">
                                <div style="font-size:18px;font-weight:bold;color:#345;">Ghi chú:</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="box-sizing:border-box;padding:0 15px 5px 15px;">
                                <table class="sendEmail" role="presentation" style="border-collapse:collapse;width:100%;line-height:1.68;font-size:15px;color:#456;">
                                    <tbody>
                                        @foreach(config('company.noticeShipTicket') as $notice)
                                            <tr style="border-bottom:1px dotted #d1d1d1;color:#456;">
                                                <td style="padding:5px;">
                                                    <div>{!! $notice !!}</div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:15px 0;text-align:center;font-size:15px;color:#3498db">
                                <div>
                                    Mọi cập nhật/thay đổi thông tin hành khách, tăng/giảm vé, đổi chuyến/hủy vé, đóng góp ý kiến/khiếu nại,... Vui lòng gửi phản hồi bằng cách trả lời trực tiếp email này để được hỗ trợ nhanh chóng và chính xác nhất
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
@endif
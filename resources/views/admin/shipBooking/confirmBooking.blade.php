@php
    use App\Helpers\DateAndTime;
@endphp
<!-- Hiển thị thông tin booking -->
@if(!empty($item))
<table class="sendEmail" style="font-family:'Roboto',Montserrat,-apple-system,'Segoe UI',sans-serif;width:100%;background-color:#EDF2F7;" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tbody>
        <tr>
            <td align="center" style="font-family:'Roboto',Montserrat,-apple-system,'Segoe UI',sans-serif;">
                <table class="sendEmail" role="presentation" style="border-collapse:collapse;background:#ffffff;border-radius:3px;width:100%;max-width:640px;margin:0 auto 40px auto;">
                    <tbody>
                        <tr>
                            <td style="box-sizing:border-box;padding:15px 15px 10px 15px;line-height:1">
                                <div style="text-align:center">
                                    <img width="70px" src="{{ config('main.logo_square') }}" style="display:inline-block;width:70px;">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="box-sizing:border-box;text-align:center;line-height:1.68;color:#456;padding:10px 15px 0 15px;">
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
                                    $idUser                     = Auth::id() ?? 0;
                                    $infoStaff                  = \App\Models\Staff::select('*')
                                                                    ->where('user_id', $idUser)
                                                                    ->first();
                                    $staffSupport               = null;
                                    if(!empty($infoStaff)){
                                        $staffSupport           = '<div style="text-align:right;margin-right:10px;font-size:15px;">Nhân viên hỗ trợ: '.$infoStaff->firstname.' '.$infoStaff->lastname.' - '.$infoStaff->phone.'</div>';
                                    }
                                @endphp
                                <h1 style="font-size:20px;font-weight:bold;color:#345;margin-bottom:15px;">XÁC NHẬN DỊCH VỤ</h1>
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
                                <div style="font-size:18px;font-weight:bold;color:#345;">1. Chi tiết giá</div>
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
                        @for($i=2;$i<4;++$i)
                        <tr>
                            <td style="box-sizing:border-box;font-weight:bold;padding:10px 15px 5px 15px;">
                                <div style="font-size:18px;font-weight:bold;color:#345;">{{ $i }}. Thông tin chuyến đi</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="box-sizing:border-box;padding:5px 15px 20px 15px;">
                                <table width="100%" style="border:2px dashed #c1c1c1;border-collapse:collapse;font-size:15px;line-height:1.48">
                                    <tbody>
                                        <tr>
                                            <td colspan="2" style="display:flex;padding:7px 12px !important;border:1px dashed #d1d1d1">
                                                <div style="width:calc(50% - 30px);display:inline-block;vertical-align:top">
                                                    <div style="font-size:18px;font-weight:bold;color:rgb(0,90,180);text-transform:capitalize;">Sóc Trăng</div>
                                                    <div style="font-size:15px;font-weight:normal;color:#456">Sóc Trăng, Việt Nam</div>
                                                    <div><span style="font-size:18px;font-weight:bold;color:rgb(0,90,180);text-transform:capitalize;">07:30</span> Xuất bến tại cảng cá Trần Đề</div>
                                                </div>
                                                <div style="width:60px;margin-top:10px;display:inline-block;vertical-align:top;margin-left:15px;">
                                                    <img src="{{ config('main.icon-arrow-email') }}" style="width:100%;">
                                                </div>
                                                <div style="width:calc(50% - 30px);display:inline-block;vertical-align:top;margin-left:15px;">
                                                    <div style="font-size:18px;font-weight:bold;color:rgb(0,90,180);text-transform:capitalize;">Côn đảo</div>
                                                    <div style="font-size:15px;font-weight:normal;color:#456">Bà Rịa Vũng Tàu, Việt Nam</div>
                                                    <div><span style="font-size:18px;font-weight:bold;color:rgb(0,90,180);text-transform:capitalize;">10:00</span> Cập bến tại cảng Bến Đầm</div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding:7px 12px !important;border:1px dashed #d1d1d1">
                                                <span style="width:70px;display:inline-block;">Ngày</span> : <span style="font-weight:bold;color:rgb(0,90,180);">Thứ 5, ngày 29-09-2022</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding:7px 12px !important;border:1px dashed #d1d1d1">
                                                <span style="width:70px;display:inline-block;">Hãng tàu</span> : <span style="font-weight:bold;color:rgb(0,90,180);">Phú Quốc Express</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding:7px 12px !important;border:1px dashed #d1d1d1">
                                                <span style="width:70px;display:inline-block;">Số lượng</span> : <span><span style="font-size:18px;font-weight:bold;color:rgb(0,90,180);text-transform:capitalize;">5</span> người lớn, <span style="font-size:18px;font-weight:bold;color:rgb(0,90,180);text-transform:capitalize;">5</span> trẻ em 6-11 tuổi, <span style="font-size:18px;font-weight:bold;color:rgb(0,90,180);text-transform:capitalize;">5</span> người trên 60 tuổi</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding:7px 12px !important;border:1px dashed #d1d1d1">
                                                <span style="width:70px;display:inline-block;">Loại vé</span> : <span style="font-weight:bold;color:rgb(0,90,180);text-transform:capitalize;">ECO</span>
                                            </td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        @endfor
                        <!-- DANH SÁCH HÀNH KHÁCH -->
                        <tr>
                            <td style="box-sizing:border-box;font-weight:bold;padding:10px 15px 5px 15px;">
                                <div style="font-size:18px;font-weight:bold;color:#345;">4. Danh sách hành khách</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="box-sizing:border-box;padding:5px 15px;">
                                <table class="sendEmail" role="presentation" style="border-collapse:collapse;width:100%;line-height:1.68;font-size:15px;color:#456;border:1px solid #d1d1d1;">
                                    <thead>
                                        <tr style="background:rgba(0,123,255,0.4);text-align:center;font-size:14px;font-weight:bold;">
                                            <td style="font-size:15px;padding:7px 12px;width:50px;">STT</td>
                                            <td style="font-size:15px;padding:7px 12px;border-left:1px dashed #d1d1d1;">Họ tên</td>
                                            <td style="font-size:15px;padding:7px 12px;border-left:1px solid #d1d1d1;">Số CMND</td>
                                            <td style="font-size:15px;padding:7px 12px;border-left:1px solid #d1d1d1;">Năm sinh</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($item->customer_list->isNotEmpty())
                                            @foreach($item->customer_list as $infoCustomer)
                                                <tr>
                                                    <td style="font-size:15px;padding:7px 12px !important;border-top:1px dashed #d1d1d1;text-align:center;">{{ $loop->index+1 }}</td>
                                                    <td style="font-size:15px;padding:7px 12px !important;border-left:1px dashed #d1d1d1;border-top:1px dashed #d1d1d1;">{{ $infocustomer_contact->customer_name }}</td>
                                                    <td style="font-size:15px;padding:7px 12px !important;text-align:right;border-left:1px dashed #d1d1d1;border-top:1px dashed #d1d1d1;">{{ $infocustomer_contact->customer_year_of_birth }}</td>
                                                    <td style="font-size:15px;padding:7px 12px !important;text-align:right;border-left:1px dashed #d1d1d1;border-top:1px dashed #d1d1d1;">{{ $infocustomer_contact->customer_identity ?? 'Chưa có'  }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4" style="font-size:15px;padding:7px 12px !important;border-top:1px dashed #d1d1d1;text-align:center;">Chưa cập nhật!</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                <table class="sendEmail" role="presentation" style="border-collapse:collapse;width:100%;line-height:1.68;font-size:15px;color:#456;">
                                    <tbody>
                                        <tr>
                                            <td colspan="3" style="font-size:15px;padding:7px 12px !important;text-align:center;font-style:italic;font-size:14px;">Quý khách cập nhật Danh sách hành khách bằng cách soạn mail đơn giản gồm: Họ Tên - Năm sinh - Số CMND/CCCD từng hành khách và trả lời email này</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <!-- STATUS -->
                        <tr>
                            <td style="box-sizing:border-box;font-weight:bold;padding:10px 15px 5px 15px;">
                                <div style="font-size:18px;font-weight:bold;color:#345;">5. Yêu cầu</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="box-sizing:border-box;padding:0 15px 5px 15px;">
                                <table class="sendEmail" role="presentation" style="border-collapse:collapse;width:100%;line-height:1.68;font-size:15px;color:#456;">
                                    <tbody>
                                        <tr style="border-bottom:1px dashed #d1d1d1;color:#456;">
                                            <td style="padding:5px;">
                                                <div>
                                                    1. Nếu chưa cung cấp thông tin từng người đi, quý khách cập nhật bằng cách trả lời email này
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="border-bottom:1px dashed #d1d1d1;color:#456;">
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
                                                    2. Quý khách chuyển khoản theo thông tin bên dưới <span style="font-weight:bold;font-size:16px;color:red;">{{ $deadline }}</span>, trong nội dung chuyển khoản ghi <span style="font-weight:bold;font-size:16px;">{{ $item->customer_contact->phone }}</span>
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
                            <td style="box-sizing:border-box;font-weight:bold;padding:10px 15px 5px 15px;">
                                <div style="font-size:18px;font-weight:bold;color:#345;">6. Chính sách hoàn - hủy</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="box-sizing:border-box;padding:0 15px 5px 15px;">
                                <table class="sendEmail" role="presentation" style="border-collapse:collapse;width:100%;line-height:1.68;font-size:15px;color:#456;">
                                    <tbody>
                                        @foreach(config('company.cancelShipTicket') as $policy)
                                            <tr style="border-bottom:1px dashed #d1d1d1;color:#456;">
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
                            <td style="box-sizing:border-box;font-weight:bold;padding:10px 15px 5px 15px;">
                                <div style="font-size:18px;font-weight:bold;color:#345;">7. Ghi chú</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="box-sizing:border-box;padding:0 15px 5px 15px;">
                                <table class="sendEmail" role="presentation" style="border-collapse:collapse;width:100%;line-height:1.68;font-size:15px;color:#456;">
                                    <tbody>
                                        @foreach(config('company.noticeShipTicket') as $notice)
                                            <tr style="border-bottom:1px dashed #d1d1d1;color:#456;">
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
                            <td style="padding:10px 15px 5px 15px;text-align:center;font-size:15px;color:#3498db">
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
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
                                    <img width="70px" src="{{ config('main.logo_square') }}" style="display:inline-block;width:70px;">
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
                        <tr>
                            <td style="box-sizing:border-box;font-weight:bold;text-align:center;padding:10px 15px 0 15px;">
                                <div style="font-size:18px;font-weight:bold;color:#345;">CHI TIẾT</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="box-sizing:border-box;padding:5px 15px;">
                                <table class="sendEmail" role="presentation" style="border-collapse:collapse;width:100%;line-height:1.68;font-size:15px;color:#456;">
                                    <tbody>
                                        @if(!empty($item->service->serviceLocation->display_name))
                                            <tr>
                                                <td style="width:140px;font-size:15px;padding:5px;">Điểm đến</td>
                                                <td style="font-size:15px;padding:5px;">{{ $item->service->serviceLocation->display_name }}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td style="width:140px;font-size:15px;padding:5px;">Tên dịch vụ</td>
                                            <td style="font-size:15px;padding:5px;"><a href="{{ env('APP_URL') }}/{{ $item->service->seo->slug_full ?? $item->tour->seo->slug_full ?? null }}" style="color:rgba(0,123,255,1);text-decoration:none" target="_blank">{{ $item->tour->name ?? $item->service->name }}</a></td>
                                        </tr>
                                        @if(!empty($item->tour))
                                            <tr style="border-top:1px dotted #d1d1d1;">
                                                <td style="width:140px;font-size:15px;padding:5px;">Lịch trình</td>
                                                <td style="font-size:15px;padding:5px;">
                                                    <a href="{{ env('APP_URL') }}/{{ $item->tour->seo->slug_full ?? null }}" style="color:rgba(0,123,255,1);text-decoration:none" target="_blank">{{ env('APP_URL') }}/{{ $item->tour->seo->slug_full ?? null }}</a>
                                                </td>
                                            </tr>
                                        @endif
                                        <tr style="border-top:1px dotted #d1d1d1">
                                            <td style="width:140px;font-size:15px;padding:5px;">Loại</td>
                                            <td style="font-size:15px;padding:5px;">{{ $item->quantityAndPrice[0]->option_name }}</td>
                                        </tr>
                                        <tr style="border-top:1px dotted #d1d1d1">
                                            <td style="width:140px;font-size:15px;padding:5px;">Ngày</td>
                                            <td style="font-size:15px;padding:5px;">
                                                @if($item->date_from==$item->date_to)
                                                    {{ \App\Helpers\DateAndTime::convertMktimeToDayOfWeek(strtotime($item->date_from)) }}, {{ date('d/m/Y', strtotime($item->date_from)) }}
                                                @else 
                                                    {{ \App\Helpers\DateAndTime::convertMktimeToDayOfWeek(strtotime($item->date_from)) }}, {{ date('d/m/Y', strtotime($item->date_from)) }} - {{ \App\Helpers\DateAndTime::convertMktimeToDayOfWeek(strtotime($item->date_to)) }}, {{ date('d/m/Y', strtotime($item->date_to)) }}
                                                @endif
                                            </td>
                                        </tr>
                                        
                                        <!-- xử lý cho giao diện tour -->
                                        @if(!empty($item->tour))
                                            <tr style="border-top:1px dotted #d1d1d1">
                                                <td style="width:140px;font-size:15px;padding:5px;">Điểm đón</td>
                                                <td style="font-size:15px;padding:5px;">{{ $item->tour->pick_up ?? null }}</td>
                                            </tr>
                                            <tr style="border-top:1px dotted #d1d1d1">
                                                <td style="width:140px;font-size:15px;padding:5px;">Ăn uống</td>
                                                <td style="font-size:15px;padding:5px;">1 bữa ăn sáng (50,000{{ config('main.unit_currency') }} /suát) và 3 bữa ăn chính (150,000{{ config('main.unit_currency') }} /suát)</td>
                                            </tr>
                                            <tr style="border-top:1px dotted #d1d1d1">
                                                <td style="width:140px;font-size:15px;padding:5px;">Khách sạn</td>
                                                <td style="font-size:15px;padding:5px;">3 phòng 2 khách Villa hướng vườn (2 giường đơn) và 1 phòng 3 khách Villa hướng vườn (1 giường đôi và 1 giường đơn)</td>
                                            </tr>
                                        @endif
                                        <!-- xử lý cho giao diện vé vui chơi -->
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <!-- GIÁ DỊCH VỤ & SỐ LƯỢNG -->
                        @if(!empty($item->quantityAndPrice))
                        <tr>
                            <td style="box-sizing:border-box;font-weight:bold;text-align:center;padding:10px 15px 5px 15px;">
                                <div style="font-size:18px;font-weight:bold;color:#345;">CHI PHÍ</div>
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
                                        @foreach($item->quantityAndPrice as $quantity)
                                            @if(!empty($quantity->quantity))
                                                @php
                                                    $total  += $quantity->quantity*$quantity->price;
                                                @endphp 
                                                <tr>
                                                    <td style="font-size:15px;padding:5px 10px;border-top:1px dashed #d1d1d1;">{{ $quantity->option_age }}</td>
                                                    <td style="font-size:15px;padding:5px 10px;text-align:right;border-left:1px dashed #d1d1d1;border-top:1px dashed #d1d1d1;">{{ $quantity->quantity }} * {{ number_format($quantity->price) }}</td>
                                                    <td style="font-size:15px;padding:5px 10px;text-align:right;border-left:1px dashed #d1d1d1;border-top:1px dashed #d1d1d1;">{{ number_format($quantity->quantity*$quantity->price) }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        <!-- Thành tiền phát sinh và trừ lại -->
                                        @if(!empty($item->costMoreLess))
                                            @foreach($item->costMoreLess as $cost)
                                                @php
                                                    $total  += $cost->quantity*$cost->unit_price;
                                                @endphp 
                                                <tr>
                                                    <td style="font-size:15px;padding:5px 10px;border-top:1px dashed #d1d1d1;">{{ $cost->name }}</td>
                                                    <td style="font-size:15px;padding:5px 10px;text-align:right;border-left:1px dashed #d1d1d1;border-top:1px dashed #d1d1d1;">{{ $cost->quantity }} * {{ ($cost->unit_price>0) ? number_format($cost->unit_price) : '('.number_format($cost->unit_price).')' }}</td>
                                                    <td style="font-size:15px;padding:5px 10px;text-align:right;border-left:1px dashed #d1d1d1;border-top:1px dashed #d1d1d1;">{{ ($cost->quantity*$cost->unit_price)>0 ? number_format($cost->quantity*$cost->unit_price) : '('.number_format($cost->quantity*$cost->unit_price).')' }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
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
                        <!-- DANH SÁCH HÀNH KHÁCH (chỉ giao diện tour) -->
                        @if(!empty($item->tour))
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
                                                        <td style="font-size:15px;padding:5px 10px;border-left:1px dashed #d1d1d1;border-top:1px dashed #d1d1d1;">{{ $infoCustomer->customer_name }}</td>
                                                        <td style="font-size:15px;padding:5px 10px;text-align:right;border-left:1px dashed #d1d1d1;border-top:1px dashed #d1d1d1;">{{ $infoCustomer->customer_year_of_birth }}</td>
                                                        <td style="font-size:15px;padding:5px 10px;text-align:right;border-left:1px dashed #d1d1d1;border-top:1px dashed #d1d1d1;">{{ $infoCustomer->customer_identity ?? 'Chưa có'  }}</td>
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
                        @endif
                        <!-- STATUS -->
                        <tr>
                            <td style="box-sizing:border-box;font-weight:bold;padding:10px 15px 0 15px;">
                                <div style="font-size:18px;font-weight:bold;color:#345;">Ghi chú:</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="box-sizing:border-box;padding:0 15px 5px 15px;">
                                <table class="sendEmail" role="presentation" style="border-collapse:collapse;width:100%;line-height:1.68;font-size:15px;color:#456;">
                                    <tbody>
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
                                                    1. Quý khách chuyển khoản cọc /thanh toán số tiền <span style="font-weight:bold;color:#E74C3C;font-size:17px;">{{ !empty($item->require_deposit) ? number_format($item->require_deposit) : number_format($total).config('main.unit_currency') }}</span> {{ $deadline }}, trong nội dung chuyển khoản ghi <span style="font-weight:bold;font-size:16px;">{{ $item->customer_contact->phone }}</span>
                                                </div>
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
                                                <div>2. Nếu có yêu cầu xuất hóa đơn, quý khách vui lòng cung cấp thông tin xuất hóa đơn bằng cách trả lời email này (chậm nhất trước 48 giờ kể từ lúc nhận được vé điện tử)</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        {{-- <tr>
                            <td style="box-sizing:border-box;padding:5px 15px;line-height:1.58;font-size:15px;text-align:center">
                                <div>
                                    <a href="https://hitour.vn/index.php?module=admin&amp;controller=tour&amp;action=index" style="text-decoration:none;width:200px;background:rgba(0,123,255,1);font-weight:bold;color:#fff;font-size:15px;padding:10px 20px;border-radius:3px;display:block;margin:0 auto" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://hitour.vn/index.php?module%3Dadmin%26controller%3Dtour%26action%3Dindex&amp;source=gmail&amp;ust=1659348539134000&amp;usg=AOvVaw38wNvmLhySoXvj_IRTX6Tl">
                                    Quản lí Booking
                                    </a>
                                </div>
                            </td>
                        </tr> --}}
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
@endif
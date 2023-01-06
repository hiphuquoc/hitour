<!-- DANH SÁCH HÀNH KHÁCH -->
<tr>
    <td style="box-sizing:border-box;font-weight:bold;padding:10px 15px 5px 15px;">
        <div style="font-size:18px;font-weight:bold;color:#345;">{{ $stt }}. Danh sách hành khách</div>
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
                    <td style="font-size:15px;padding:7px 12px;width:50px;">STT</td>
                    <td style="font-size:15px;padding:7px 12px;border-left:1px dashed #d1d1d1;">Họ tên</td>
                    <td style="font-size:15px;padding:7px 12px;border-left:1px solid #d1d1d1;">CMND /CCCD</td>
                    <td style="font-size:15px;padding:7px 12px;border-left:1px solid #d1d1d1;">Năm sinh</td>
                </tr>
            </thead>
            <tbody>
                @if($item->customer_list->isNotEmpty())
                    @foreach($item->customer_list as $infoCustomer)
                        @php
                            $customerNote   = null;
                            $yearNow        = date('Y', time());
                            if($yearNow-$infoCustomer->year_of_birth<11) $customerNote = '(trẻ em)';
                            if($yearNow-$infoCustomer->year_of_birth>60) $customerNote = '(cao tuổi)';
                        @endphp 
                        <tr>
                            <td style="font-size:15px;padding:7px 12px !important;border-top:1px dashed #d1d1d1;text-align:center;">{{ $loop->index+1 }}</td>
                            <td style="font-size:15px;padding:7px 12px !important;border-left:1px dashed #d1d1d1;border-top:1px dashed #d1d1d1;">{{ $infoCustomer->name }} {{ $customerNote }}</td>
                            <td style="font-size:15px;padding:7px 12px !important;text-align:right;border-left:1px dashed #d1d1d1;border-top:1px dashed #d1d1d1;">{{ $infoCustomer->identity ?? 'Chưa có'  }}</td>
                            <td style="font-size:15px;padding:7px 12px !important;text-align:right;border-left:1px dashed #d1d1d1;border-top:1px dashed #d1d1d1;">{{ $infoCustomer->year_of_birth }}</td>
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
        <div style="font-size:18px;font-weight:bold;color:#345;">{{ $stt }}. Hướng dẫn hoàn tất</div>
        @php
            ++$stt;
        @endphp
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
                            2. Quý khách chuyển khoản theo thông tin bên dưới <span style="font-weight:bold;background:yellow;">{{ $deadline }}</span>, trong nội dung chuyển khoản ghi <span style="font-weight:bold;font-size:16px;background:yellow;">{{ $item->customer_contact->phone }}</span>
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
        <div style="font-size:18px;font-weight:bold;color:#345;">{{ $stt }}. Chính sách hoàn - hủy</div>
        @php
            ++$stt;
        @endphp
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
        <div style="font-size:18px;font-weight:bold;color:#345;">{{ $stt }}. Ghi chú</div>
        @php
            ++$stt;
        @endphp
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
            Mọi cập nhật/thay đổi thông tin hành khách, đổi chuyến/hủy vé, tăng/giảm vé, đóng góp ý kiến/khiếu nại,... Vui lòng gửi phản hồi bằng cách trả lời trực tiếp email này để được hỗ trợ nhanh chóng và chính xác nhất
        </div>
    </td>
</tr>
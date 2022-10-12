<div class="contentTour">
    <!-- Điểm nổi bật của Tour -->
	<div id="diem-noi-bat-chuong-trinh-tour" class="contentTour_item">
		<div class="contentTour_item_title">
			<i class="fa-solid fa-award"></i>
			<h2>Điểm nổi bật của Tour</h2>
		</div>
		<div class="contentTour_item_text">
			<table class="tableList">
				<tbody>
					<tr>
						<td style="width:90px;vertical-align:top;">Hành trình</td>
						<td>{{ $item->seo->description }}</td>
					</tr>
					@if(!empty($item->days))
                        @if($item->days>1)
                            <tr>
                                <td style="width:90px;vertical-align:top;">Thời gian</td>
                                <td>{{ $item->days }} ngày {{ $item->nights }} đêm</td>
                            </tr>
                        @else 
                            <tr>
                                <td style="width:90px;vertical-align:top;">Thời gian</td>
                                <td>{{ $item->time_start }} - {{ $item->time_end }}</td>
                            </tr>
                        @endif
                    @endif
                    @if(!empty($item->departure_schedule))
                        <tr>
                            <td style="width:90px;vertical-align:top;">Lịch tour</td>
                            <td>{{ $item->departure_schedule }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td style="width:90px;vertical-align:top;">Vận chuyển</td>
                        <td>{{ $item->transport }}</td>
                    </tr>
                    <tr>
                        <td style="width:90px;vertical-align:top;">Xuất phát</td>
                        <td>{{ $item->pick_up }}</td>
                    </tr>
				</tbody>
			</table>
			{!! $item->content->special_content ?? null !!}
		</div>
	</div>
    <!-- Bảng giá Tour -->
	@if($item->options->isNotEmpty())
        <div id="bang-gia-tour" class="contentTour_item">
            <div class="contentTour_item_title noBorder">
                <i class="fa-solid fa-hand-holding-dollar"></i>
                <h2>Bảng giá Tour</h2>
            </div>
            <div class="contentTour_item_text">
                @php
                    $options    = \App\Http\Controllers\AdminTourOptionController::margeTourPriceByDate($item->options);
                @endphp
                <table class="tableContentBorder">
                    <thead>
                        <tr>
                            <th>Tùy chọn</th>
                            <th>Giá áp dụng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($options as $option)
                            <tr>
                                <td>
                                    <div style="font-weight:700;">{{ $option['option'] }}</div>
                                    @foreach($option['date_apply'] as $price)
                                        @foreach($price as $applyAge)
                                            <div style="font-size:0.95rem;">từ <b>{{ !empty($applyAge['date_start']) ? date('d/m/Y', strtotime($applyAge['date_start'])) : '...' }}</b> đến <b>{{ !empty($applyAge['date_end']) ? date('d/m/Y', strtotime($applyAge['date_end'])) : '...' }}</b></div>
                                            @break;
                                        @endforeach
                                        
                                    @endforeach
                                </td>
                                <td style="vertical-align:top;">
                                    @foreach($option['date_apply'] as $price)
                                        @foreach($price as $applyAge)
                                            <div><span style="font-weight:700;color:rgb(0, 90, 180);font-size:1.1rem;">{!! !empty($applyAge['price']) ? number_format($applyAge['price']).config('main.unit_currency') : '-' !!}</span> /{{ $applyAge['apply_age'] ?? '-' }}</div>
                                        @endforeach
                                        @break
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
            </div>
        </div>
    @endif
    <!-- Chương trình tour -->
    @if($item->timetables->isNotEmpty())
        <div id="lich-trinh-tour-du-lich" class="contentTour_item">
            <div class="contentTour_item_title noBorder">
                <i class="fa-solid fa-bookmark"></i>
                <h2>Lịch trình Tour</h2>
                <div>
                    <span class="active" data-tabcontent="timeTables_full" onClick="tabContent(this);" style="cursor:pointer;">Bản đầy đủ</span>
                    <span data-tabcontent="timeTables_sort" onClick="tabContent(this);" style="cursor:pointer;">Bản tóm tắt</span>
                </div>
            </div>
            <div class="contentTour_item_text">
                <!-- nội dung đầy đủ -->
                <div id="timeTables_full" class="dayTourByList">
                    @foreach($item->timetables as $timetable)
                        <div class="dayTourByList_item">
                            <div class="dayTourByList_item_title" onClick="hideShowContent(this);">
                                <h3>{{ $timetable->title }}</h3>
                            </div>
                            <div class="dayTourByList_item_text">
                                {!! $timetable->content !!}
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- nội dung rút gọn -->
                <div id="timeTables_sort" class="dayTourByList" style="display:none;">
                    @foreach($item->timetables as $timetable)
                        @if(!empty($timetable->content_sort))
                            <div class="dayTourByList_item">
                                <div class="dayTourByList_item_title" onClick="hideShowContent(this);">
                                    <h3>{{ $timetable->title }}</h3>
                                </div>
                                <div class="dayTourByList_item_text">
                                    {!! $timetable->content_sort !!}
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    <!-- Chính sách trẻ em -->
    @if(!empty($item->content->policy_child))
        <div id="chinh-sach-tre-em-tour" class="contentTour_item">
            <div class="contentTour_item_title">
                <i class="fa-solid fa-bookmark"></i>
                <h2>Chính sách trẻ em</h2>
            </div>
            <div class="contentTour_item_text">
                {!! $item->content->policy_child !!}
            </div>
        </div>
    @endif
    <!-- Tour bao gồm -->
    @if(!empty($item->content->include))
        <div id="tour-bao-gom-va-khong-bao-gom" class="contentTour_item">
            <div class="contentTour_item_title">
                <i class="fa-solid fa-bookmark"></i>
                <h2>Tour bao gồm</h2>
            </div>
            <div class="contentTour_item_text">
                {!! $item->content->include !!}
            </div>
        </div>
    @endif
    <!-- Tour chưa bao gồm -->
    @if(!empty($item->content->not_include))
        <div class="contentTour_item">
            <div class="contentTour_item_title">
                <i class="fa-solid fa-bookmark"></i>
                <h2>Tour chưa bao gồm</h2>
            </div>
            <div class="contentTour_item_text">
                {!! $item->content->not_include !!}
            </div>
        </div>
    @endif
    <!-- Thực đơn -->
    @if(!empty($item->content->menu))
        <div id="thuc-don-theo-chuong-trinh-tour" class="contentTour_item">
            <div class="contentTour_item_title noBorder">
                <i class="fa-solid fa-bookmark"></i>
                <h2>Thực đơn</h2>
            </div>
            <div class="contentTour_item_text">
                <div class="menuTour">
                    {!! $item->content->menu !!}
                </div>
            </div>
        </div>
    @endif
    <!-- Khách sạn tham khảo -->
    @if(!empty($item->content->hotel))
        <div id="khach-san-tham-khao" class="contentTour_item">
            <div class="contentTour_item_title">
                <i class="fa-solid fa-bookmark"></i>
                <h2>Khách sạn tham khảo</h2>
            </div>
            <div class="contentTour_item_text">
                <div class="hotelTour">
                    {!! $item->content->hotel !!}
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts-custom')
    <script type="text/javascript">
        function tabContent(elemtBtn){
            const idShow        = $(elemtBtn).data('tabcontent');
            const elementShow   = $('#'+idShow);
            /* ẩn tất cả phần tử con => hiện lại phần tử được chọn */
            elementShow.parent().children().each(function(){
                $(this).css('display', 'none');
            });
            elementShow.css('display', 'block');
            /* xóa active tất cả phần tử con button => active button vừa click */
            $(elemtBtn).parent().children().each(function(){
                $(this).removeClass('active');
            });
            $(elemtBtn).addClass('active');
        }
        function hideShowContent(elemtBtn){
            const elemtContent      = $(elemtBtn).next();
            const displayContent    = elemtContent.css('display');
            if(displayContent=='none'){
                elemtContent.css('display', 'block');
            }else {
                elemtContent.css('display', 'none');
            }
        }
    </script>
@endpush
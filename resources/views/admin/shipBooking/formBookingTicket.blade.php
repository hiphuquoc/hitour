<!-- lấy id của cảng chuyến về để tải ajax lần đầu -->
@php
    $idPortShipLocation                     = [];
    foreach($item->infoDeparture as $departure){
        if(!empty($departure->port_location)){
            foreach($ports as $port){
                if($departure->port_location==$port->name) {
                    $idPortShipLocation[]   = $port->id;
                    break;
                }
            }
        }
    }
@endphp
<div class="card">
    <div class="card-header border-bottom">
        <h4 class="card-title">Loại booking</h4>
    </div>
    <div class="card-body">
        <div class="formBox">
            <div class="formBox_full">
                @php
                    $activeRound        = 'active';
                    $activeOnetrip      = null;
                    if($item->infoDeparture->count()==1){
                        $activeRound    = null;
                        $activeOnetrip  = 'active';
                    }
                @endphp
                <div class="formBox_full_item">
                    <input type="hidden" id="type_booking" name="type_booking" value="{{ $item->infoDeparture->count() ?? null }}">
                    <div class="chooseTripBox">
                        <div class="chooseTripBox_item {{ $activeRound }}" onclick="changeValueTypeBooking(this, 2);">
                            Khứ hồi
                        </div>
                        <div class="chooseTripBox_item {{ $activeOnetrip }}" onclick="changeValueTypeBooking(this, 1);">
                            Một chiều
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Thông tin chuyến đi - về -->
@for($i=0;$i<2;++$i)
@php
    $code       = $i + 1;
    $displayDp2 = $code==2&&empty($item->infoDeparture[1]) ? 'none' : 'block';
@endphp
<div id="js_filterForm_dp{{ $code }}" class="card" style="display:{{ $displayDp2 }};">
    <div class="card-header border-bottom">
        <h4 class="card-title">Thông tin chuyến {{ $code==1 ? 'đi' : 'về' }}</h4>
    </div>
    <div class="card-body">
        <div class="formBox">
            <div class="formBox_full">
                <!-- One Row -->
                <div class="formBox_full_item">
                    <label class="form-label inputRequired" for="date_{{ $code }}">Ngày khởi hành</label>
                    <input type="text" class="form-control flatpickr-basic flatpickr-input active" name="date_{{ $code }}" placeholder="YYYY-MM-DD" value="{{ $item->infoDeparture[$i]->date ?? null }}" readonly="readonly" onChange="loadDeparture({{ $code }}, '', 'admin');">
                </div>
                <!-- One Row -->
                <div class="formBox_full_item">
                    <div class="flexBox">
                        <div class="flexBox_item">
                            <label class="form-label inputRequired" for="ship_port_departure_id_{{ $code }}">Điểm khởi hành</label>
                            <select class="select2 form-select select2-hidden-accessible" id="js_loadShipLocationByShipDeparture_departure_{{ $code }}" name="ship_port_departure_id_{{ $code }}" aria-hidden="true" onChange="loadShipLocationByShipDeparture({{ $code }}, this, 'js_loadShipLocationByShipDeparture_location_{{ $code }}')">
                                <option value="0">- Lựa chọn -</option>
                                @if(!empty($ports))
                                    @foreach($ports as $port)
                                        @php
                                            $select     = null;
                                            if(!empty($item->infoDeparture[$i]->port_departure)&&$port->name==$item->infoDeparture[$i]->port_departure) $select = 'selected';
                                            $portFull   = \App\Helpers\Build::buildFullShipPort($port);
                                        @endphp
                                        <option value="{{ $port->id }}" {{ $select }}>{{ $portFull }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="flexBox_item" style="margin-left:1rem;">
                            <label class="form-label inputRequired" for="ship_port_location_id">Điểm đến</label>
                            <select class="select2 form-select select2-hidden-accessible" id="js_loadShipLocationByShipDeparture_location_{{ $code }}" name="ship_port_location_id_{{ $code }}" aria-hidden="true"  onChange="loadDeparture({{ $code }}, '', 'admin');">
                                <option value="0">- Lựa chọn -</option>
                                <!-- load ajax -->
                            </select>
                        </div>
                    </div>
                </div>
                <!-- One Row -->
                @php
                    $yearNow = date('Y', time());
                @endphp
                <div class="formBox_full_item">
                    <div class="flexBox">
                        <div class="flexBox_item">
                            <label class="form-label">Người lớn ({{ $yearNow - 59 }} - {{ $yearNow - 12 }})</label>
                            <input type="number" min="0" class="form-control" name="quantity_adult_{{ $code }}" placeholder="0" value="{{ $item->infoDeparture[$i]->quantity_adult ?? null }}">
                        </div>
                        <div class="flexBox_item">
                            <label class="form-label">Trẻ em ({{ $yearNow - 11 }} - {{ $yearNow - 6 }})</label>
                            <input type="number" min="0" class="form-control" name="quantity_child_{{ $code }}" placeholder="0" value="{{ $item->infoDeparture[$i]->quantity_child ?? null }}">
                        </div>
                        <div class="flexBox_item">
                            <label class="form-label">Cao tuổi ({{ $yearNow - 60 }})</label>
                            <input type="number" min="0" class="form-control" name="quantity_old_{{ $code }}" placeholder="0" value="{{ $item->infoDeparture[$i]->quantity_old ?? null }}">
                        </div>
                    </div>
                </div>
                <!-- One Row -->
                <div id="js_loadDeparture_dp{{ $code }}" class="formBox_full_item"></div>
            </div>
        </div>
    </div>
</div>
@endfor
@push('scripts-custom')
    <script type="text/javascript">

        $(document).ready(function(){
            loadShipLocationByShipDeparture(1, $('#js_loadShipLocationByShipDeparture_departure_1'), 'js_loadShipLocationByShipDeparture_location_1', '{{ $item->infoDeparture[0]->port_location ?? null }}');
            loadShipLocationByShipDeparture(2, $('#js_loadShipLocationByShipDeparture_departure_2'), 'js_loadShipLocationByShipDeparture_location_2', '{{ $item->infoDeparture[1]->port_location ?? null }}');

            loadDeparture(1, '{{ $idPortShipLocation[0] ?? null }}', 'admin');
            loadDeparture(2, '{{ $idPortShipLocation[1] ?? null }}', 'admin');
        });

        function chooseDeparture(elemt, code, idShipPrice, timeDeparture, timeArrive, typeTicket, partner){
            $('#js_chooseDeparture_dp'+code).val(idShipPrice+'|'+timeDeparture+'|'+timeArrive+'|'+typeTicket+'|'+partner);
            $(elemt).parent().parent().parent().find('.option').removeClass('active');
            $(elemt).addClass('active');
        }

        function loadShipLocationByShipDeparture(code, element, idWrite, namePortActive = null){
            const idShipPort = $(element).val();
            $.ajax({
                url         : '{{ route("main.shipBooking.loadShipLocation") }}',
                type        : 'post',
                dataType    : 'html',
                data        : {
                    '_token'            : '{{ csrf_token() }}',
                    ship_port_id        : idShipPort,
                    name_port_active    : namePortActive
                },
                success     : function(data){
                    $('#'+idWrite).html(data);
                    loadDeparture(code, '', 'admin');
                }
            });
        }

        function loadDeparture(code, idPortShipLocation, theme = 'main'){
            const idShipBooking         = $('#ship_booking_id').val();
            const idPortShipDeparture   = $(document).find('[name=ship_port_departure_id_'+code+']').val();
            if(idPortShipLocation==''){
                idPortShipLocation   = $(document).find('[name=ship_port_location_id_'+code+']').val();
            }
            const date                  = $(document).find('[name=date_'+code+']').val();
            if(typeof date!=='undefined'&&idPortShipDeparture!=0&&idPortShipLocation!=0){
                $.ajax({
                    url         : '{{ route("main.shipBooking.loadDeparture") }}',
                    type        : 'post',
                    dataType    : 'json',
                    data        : {
                        '_token'                : '{{ csrf_token() }}',
                        ship_booking_id         : idShipBooking,
                        code                    : code,
                        ship_port_departure_id  : idPortShipDeparture,
                        ship_port_location_id   : idPortShipLocation,
                        date,
                        theme
                    },
                    success     : function(data){
                        $('#js_loadDeparture_dp'+code).html(data);
                    }
                });
            }
        }

        function changeValueTypeBooking(elemtBtn, valueNew){
            const parent = $(elemtBtn).parent();
            /* bỏ checked và class active tất cả */
            parent.children().each(function(){
                $(this).removeClass('active');
            })
            /* thêm lại checked và class active cho button được chọn */
            $(elemtBtn).addClass('active');
            $('#type_booking').val(valueNew);
            
            if(valueNew==1){
                /* filter form */
                filterForm('oneTrip');
                loadDeparture(1, '', 'admin');
                /* loadDeparture */
            }else {
                /* filter form */
                filterForm('roundTrip');
                loadTwoDeparture();
            }
        }

        function loadTwoDeparture(){
            loadDeparture(1, '', 'admin');
            loadDeparture(2, '', 'admin');
        }

        function filterForm(typeBooking){
            if(typeBooking=='oneTrip'){
                // $('#js_filterForm_dateRound').hide();
                $('#js_filterForm_dp2').hide();
            }else {
                // $('#js_filterForm_dateRound').show();
                $('#js_filterForm_dp2').show();
            }
        }

    </script>

@endpush
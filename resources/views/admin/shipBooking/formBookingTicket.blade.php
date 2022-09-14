<!-- lấy id của cảng chuyến về để tải ajax lần đầu -->
@php
    $idPortShipLocation = 0;
    foreach($ports as $port){
        if(!empty($item->infoDeparture[0]->port_location)&&$item->infoDeparture[0]->port_location==$port->name) $idPortShipLocation = $port->id;
    }
@endphp
<div class="card">
    <div class="card-header border-bottom">
        <h4 class="card-title">Thông tin chuyến tàu & số lượng</h4>
    </div>
    <div class="card-body">
        <div class="formBox">
            <div class="formBox_full">
                <!-- One Row -->
                <div class="formBox_full_item">
                    <div class="flexBox">
                        <div class="flexBox_item">
                            <label class="form-label inputRequired" for="ship_port_departure_id">Điểm khởi hành</label>
                            <select class="select2 form-select select2-hidden-accessible" id="js_loadShipLocationByShipDeparture_departure" name="ship_port_departure_id" aria-hidden="true" onChange="loadShipLocationByShipDeparture(this, 'js_loadShipLocationByShipDeparture_location')">
                                <option value="0">- Lựa chọn -</option>
                                @if(!empty($ports))
                                    @foreach($ports as $port)
                                        @php
                                            $select     = null;
                                            if($port->name==$item->infoDeparture[0]->port_departure) $select = 'selected';
                                            $portFull   = \App\Helpers\Build::buildFullShipPort($port);
                                        @endphp
                                        <option value="{{ $port->id }}" {{ $select }}>{{ $portFull }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="flexBox_item" style="margin-left:1rem;">
                            <label class="form-label inputRequired" for="ship_port_location_id">Điểm đến</label>
                            <select class="select2 form-select select2-hidden-accessible" id="js_loadShipLocationByShipDeparture_location" name="ship_port_location_id" aria-hidden="true" onChange="loadTwoDeparture();">
                                <option value="0">- Lựa chọn -</option>
                                <!-- load ajax -->
                            </select>
                        </div>
                    </div>
                </div>
                <!-- One Row -->
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
                <!-- One Row -->
                <div class="formBox_full_item">
                    <div class="flexBox">
                        <div class="flexBox_item">
                            <label class="form-label inputRequired" for="date">Ngày khởi hành</label>
                            <input type="text" class="form-control flatpickr-basic flatpickr-input active" name="date" placeholder="YYYY-MM-DD" value="{{ $item->infoDeparture[0]->date ?? null }}" readonly="readonly">
                        </div>
                        <div class="flexBox_item" style="margin-left:1rem;">
                            <div id="js_filterForm_dateRound" style="display:{{ $item->infoDeparture->count()==2 ? 'block' : 'none' }};">
                                <label class="form-label" for="date_round">Ngày về</label>
                                <input type="text" class="form-control flatpickr-basic flatpickr-input active" name="date_round" placeholder="YYYY-MM-DD" value="{{ $item->infoDeparture[1]->date ?? null }}" onChange="loadTwoDeparture();" readonly="readonly">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Thông tin chuyến đi -->
<div class="card">
    <div class="card-header border-bottom">
        <h4 class="card-title">Thông tin chuyến đi</h4>
    </div>
    <div class="card-body">
        <div class="formBox">
            <div class="formBox_full">
                <!-- One Row -->
                @php
                    $yearNow = date('Y', time());
                @endphp
                <div class="formBox_full_item">
                    <label class="form-label">Người lớn ({{ $yearNow - 59 }} - {{ $yearNow - 12 }})</label>
                    <input type="number" min="0" class="form-control" name="quantity_adult_1" placeholder="0" value="{{ $item->infoDeparture[0]->quantity_adult ?? null }}">
                </div>
                <!-- One Row -->
                <div class="formBox_full_item">
                    <label class="form-label">Trẻ em 6-11 tuổi ({{ $yearNow - 11 }} - {{ $yearNow - 6 }})</label>
                    <input type="number" min="0" class="form-control" name="quantity_child_1" placeholder="0" value="{{ $item->infoDeparture[0]->quantity_child ?? null }}">
                </div>
                <!-- One Row -->
                <div class="formBox_full_item">
                    <label class="form-label">Người trên 60 tuổi ({{ $yearNow - 60 }})</label>
                    <input type="number" min="0" class="form-control" name="quantity_old_1" placeholder="0" value="{{ $item->infoDeparture[0]->quantity_old ?? null }}">
                </div>
                <!-- One Row -->
                <div id="js_loadDeparture_dp1" class="formBox_full_item"></div>
            </div>
        </div>
    </div>
</div>

<!-- Thông tin chuyến về -->
@php
    $displayDp2     = !empty($item->infoDeparture[1]) ? 'block' : 'none';
@endphp
<div id="js_filterForm_dp2" class="card" style="display:{{ $displayDp2 }};">
    <div class="card-header border-bottom">
        <h4 class="card-title">Thông tin chuyến về</h4>
    </div>
    <div class="card-body">
        <div class="formBox">
            <div class="formBox_full">
                <div class="formBox_full_item">
                    <label class="form-label">Người lớn ({{ $yearNow - 59 }} - {{ $yearNow - 12 }})</label>
                    <input type="number" min="0" class="form-control" name="quantity_adult_2" placeholder="0" value="{{ $item->infoDeparture[1]->quantity_adult ?? null }}">
                </div>
                <!-- One Row -->
                <div class="formBox_full_item">
                    <label class="form-label">Trẻ em 6-11 tuổi ({{ $yearNow - 11 }} - {{ $yearNow - 6 }})</label>
                    <input type="number" min="0" class="form-control" name="quantity_adult_2" placeholder="0" value="{{ $item->infoDeparture[1]->quantity_child ?? null }}">
                </div>
                <!-- One Row -->
                <div class="formBox_full_item">
                    <label class="form-label">Người trên 60 tuổi ({{ $yearNow - 60 }})</label>
                    <input type="number" min="0" class="form-control" name="quantity_adult_2" placeholder="0" value="{{ $item->infoDeparture[1]->quantity_old ?? null }}">
                </div>
                <!-- One Row -->
                <div id="js_loadDeparture_dp2" class="formBox_full_item"></div>
            </div>
        </div>
    </div>
</div>
@push('scripts-custom')
    <script type="text/javascript">

        $(document).ready(function(){
            loadShipLocationByShipDeparture($('#js_loadShipLocationByShipDeparture_departure'), 'js_loadShipLocationByShipDeparture_location', '{{ $item->infoDeparture[0]->port_location }}');
            loadDeparture(1, '{{ $idPortShipLocation }}', 'admin');
            loadDeparture(2, '{{ $idPortShipLocation }}', 'admin');
        });

        function chooseDeparture(elemt, code, idShipPrice, timeDeparture, timeArrive, typeTicket, partner){
            $('#js_chooseDeparture_dp'+code).val(idShipPrice+'|'+timeDeparture+'|'+timeArrive+'|'+typeTicket+'|'+partner);
            $(elemt).parent().parent().parent().find('.option').removeClass('active');
            $(elemt).addClass('active');
        }

        function loadShipLocationByShipDeparture(element, idWrite, namePortActive = null){
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
                }
            });
        }

        function loadDeparture(code, idPortShipLocation, theme = 'main'){
            const idShipBooking         = $('#ship_booking_id').val();
            const idPortShipDeparture   = $(document).find('[name=ship_port_departure_id]').val();
            var date                    = '';
            if(code==1){
                date                    = $(document).find('[name=date]').val();
            }else {
                date                    = $(document).find('[name=date_round]').val();
            }
            if(date!=''&&idPortShipDeparture!=0&&idPortShipLocation!=0){
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
                loadDeparture(1, '{{ $idPortShipLocation }}', 'admin');
                /* loadDeparture */
            }else {
                /* filter form */
                filterForm('roundTrip');
                loadTwoDeparture();
            }
        }

        function loadTwoDeparture(){
            loadDeparture(1, '{{ $idPortShipLocation }}', 'admin');
            loadDeparture(2, '{{ $idPortShipLocation }}', 'admin');
        }

        function filterForm(typeBooking){
            if(typeBooking=='oneTrip'){
                $('#js_filterForm_dateRound').hide();
                $('#js_filterForm_dp2').hide();
            }else {
                $('#js_filterForm_dateRound').show();
                $('#js_filterForm_dp2').show();
            }
        }

    </script>

@endpush
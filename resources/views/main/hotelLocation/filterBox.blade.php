<div class="hide-767">
    <div class="filterBox">
        <div class="filterBox_label">
            Lọc theo:
        </div>
        {{-- config('main.hotel_type') --}}
        @php
            $arrayFilter    = [];
            foreach($item->hotels as $hotel){
                if(!in_array($hotel->type_name, $arrayFilter)){
                    $arrayFilter[] = $hotel->type_name;
                }
            }
        @endphp
        <div class="filterBox_filter">
            <div class="filterBox_filter_item active" onClick="filter(this, 'tat-ca');">
                <div>Tất cả</div>
            </div>
            @foreach($arrayFilter as $filter)
                <div class="filterBox_filter_item" onClick="filter(this, '{{ \App\Helpers\Charactor::convertStrToUrl($filter) }}');">
                    <h3>{{ $filter }}</h3>
                </div>
            @endforeach
        </div>
        <div class="filterBox_view">
            <div class="filterBox_view_item active" onClick="filterView(this, 'list');">
                <i class="fa-solid fa-table-list"></i>
            </div>
            {{-- <div class="filterBox_view_item active" onClick="filterView(this, 'grid');">
                <i class="fa-solid fa-table-cells"></i>
            </div> --}}
        </div>
    </div>
</div>
@push('scripts-custom')
    <script type="text/javascript">
        function filter(elementButton, type){
            /* active button vừa click */
            $(elementButton).parent().children().each(function(){
                $(this).removeClass('active');
            })
            $(elementButton).addClass('active');
            /* hiện loading - ẩn child box chính */
            $('.loadingGridBox').css('display', 'flex');
            $('.loadingGridBox_note').css('display', 'none');
            const parent    = $('#js_filter_parent');
            const hidden    = $('#js_filter_hidden');
            parent.children().each(function(){
                $(this).css('display', 'none');
            })
            /* lọc phần tử */
            let data                = [];
            let dataHidden          = [];
            if(type=="tat-ca"){
                $(document).find("[data-filter-type]").each(function(){ /* dùng document để lấy cả trong hidden */
                    data.push($(this));
                })
            }else {
                $(document).find("[data-filter-type]").each(function(){
                    const valueFilter  = $(this).data('filter-type');
                    if(valueFilter==type) {
                        data.push($(this));
                    }else {
                        dataHidden.push($(this));
                    }
                })
            }
            /* ẩn loading - hiện lại kết quả */
            setTimeout(() => {
                /* ẩn loading */
                $('.loadingGridBox').css('display', 'none');
                if(data.length==0){
                    /* trường hợp không có phần tử => thông báo */
                    $('.loadingGridBox_note').css('display', 'block');
                }else {
                    /* hiện lại kết quả */
                    parent.html('');
                    for(let i=0;i<data.length;++i){
                        parent.append(data[i].attr('style', '').clone());
                    }
                    hidden.html('');
                    for(let i=0;i<dataHidden.length;++i){
                        hidden.append(dataHidden[i].clone());
                    }
                }
            }, 1000);
        }
        function filterView(elementButton, type){
            /* active button */
            $(elementButton).parent().children().each(function(){
                $(this).removeClass('active');
            })
            $(elementButton).addClass('active');
            /* đổi giao diện */
            if(type=='list'){
                $('.tourGrid').addClass('tourList');
                $('.loadingGridBox').addClass('loadingListBox');
            }else if(type=='grid'){
                $('.tourGrid').removeClass('tourList');
                $('.loadingGridBox').removeClass('loadingListBox');
            }
        }
    </script>
@endpush
<div class="hide-767">
    <div class="filterBox">
        <div class="filterBox_label">
            Lọc theo:
        </div>
        <div class="filterBox_filter">
            <div class="filterBox_filter_item active" onClick="filterTour(this, 'tat-ca-tour');">
                <div>Tất cả</div>
            </div>
            <div class="filterBox_filter_item" onClick="filterTour(this, 'tour-trong-ngay');">
                <h3>Tour trong ngày</h3>
            </div>
            <div class="filterBox_filter_item" onClick="filterTour(this, 'tour-nhieu-ngay');">
                <h3>Tour nhiều ngày</h3>
            </div>
        </div>
        <div class="filterBox_view">
            <div class="filterBox_view_item" onClick="filterView(this, 'list');">
                <i class="fa-solid fa-table-list"></i>
            </div>
            <div class="filterBox_view_item active" onClick="filterView(this, 'grid');">
                <i class="fa-solid fa-table-cells"></i>
            </div>
        </div>
    </div>
</div>
@push('scripts-custom')
    <script type="text/javascript">
        function filterTour(elementButton, type){
            /* active button vừa click */
            $(elementButton).parent().children().each(function(){
                $(this).removeClass('active');
            })
            $(elementButton).addClass('active');
            /* hiện loading - ẩn child box chính */
            $('.loadingGridBox').css('display', 'flex');
            $('.loadingGridBox_note').css('display', 'none');
            const parent    = $('#js_filterTour_parent');
            const hidden    = $('#js_filterTour_hidden');
            parent.children().each(function(){
                $(this).css('display', 'none');
            })
            /* lọc phần tử */
            let data                = [];
            let dataHidden          = [];
            if(type=="tat-ca-tour"){
                $(document).find("[data-filter-day]").each(function(){
                    const valueDay  = $(this).data('filter-day'); /* dùng document để lấy cả trong hidden */
                    data.push($(this));
                })
            }else if(type=="tour-nhieu-ngay"){
                $(document).find("[data-filter-day]").each(function(){
                    const valueDay  = $(this).data('filter-day');
                    if(parseInt(valueDay)>1) {
                        data.push($(this));
                    }else {
                        dataHidden.push($(this));
                    }
                })
            }else if(type=="tour-trong-ngay"){
                $(document).find("[data-filter-day]").each(function(){
                    const valueDay  = $(this).data('filter-day');
                    if(parseInt(valueDay)==1) {
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
<div class="megaMenu">
    <div class="megaMenu_title" style="max-width:240px;">
    <ul>
        @if(!empty($hotelMB))
            <li id="menuHotel_1" onmouseover="openMegaMenu(this.id);" class="selected">
                <div>Tour Miền Bắc<i class="fas fa-angle-right"></i></div>
            </li>
        @endif
        @if(!empty($hotelMT))
            <li id="menuHotel_2" onmouseover="openMegaMenu(this.id);">
                <div>Tour Miền Trung<i class="fas fa-angle-right"></i></div>
            </li>
        @endif
        @if(!empty($hotelMN))
            <li id="menuHotel_3" onmouseover="openMegaMenu(this.id);">
                <div>Tour Miền Nam<i class="fas fa-angle-right"></i></div>
            </li>
        @endif
        @if(!empty($hotelBD))
            <li id="menuHotel_4" onmouseover="openMegaMenu(this.id);">
                <div>Tour Biển Đảo<i class="fas fa-angle-right"></i></div>
            </li>
        @endif
    </ul>
    </div>
    <div class="megaMenu_content">
        <!-- Danh sách Tour Miền Bắc -->
        @if(!empty($hotelMB))
            <ul data-menu="menuHotel_1">
                @foreach($hotelMB as $item)
                    @if($loop->index==0||$loop->index%7==0)
                        <li>
                            <ul>
                    @endif
                    <li><a href="/{{ $item->seo->slug_full ?? null }}" title="{{ $item->name ?? $item->seo->title ?? null }}">{{ $item->name ?? $item->seo->title ?? null }}</a></li>
                    @if(($loop->index+1)%7==0||($loop->index+1)==count($hotelMB))
                            </ul>
                        </li>
                    @endif
                @endforeach
            </ul>
        @endif
        <!-- Danh sách Tour Miền Trung -->
        @if(!empty($hotelMT))
            <ul data-menu="menuHotel_2">
                @foreach($hotelMT as $item)
                    @if($loop->index==0||$loop->index%7==0)
                        <li>
                            <ul>
                    @endif
                    <li><a href="/{{ $item->seo->slug_full ?? null }}" title="{{ $item->name ?? $item->seo->title ?? null }}">{{ $item->name ?? $item->seo->title ?? null }}</a></li>
                    @if(($loop->index+1)%7==0||($loop->index+1)==count($hotelMT))
                            </ul>
                        </li>
                    @endif
                @endforeach
            </ul>
        @endif
        <!-- Danh sách Tour Miền Nam -->
        @if(!empty($hotelMN))
            <ul data-menu="menuHotel_3">
                @foreach($hotelMN as $item)
                    @if($loop->index==0||$loop->index%7==0)
                        <li>
                            <ul>
                    @endif
                    <li><a href="/{{ $item->seo->slug_full ?? null }}" title="{{ $item->name ?? $item->seo->title ?? null }}">{{ $item->name ?? $item->seo->title ?? null }}</a></li>
                    @if(($loop->index+1)%7==0||($loop->index+1)==count($hotelMN))
                            </ul>
                        </li>
                    @endif
                @endforeach
            </ul>
        @endif
        <!-- Danh sách Tour Biển Đảo -->
        @if(!empty($hotelBD))
            <ul data-menu="menuHotel_4">
                @foreach($hotelBD as $item)
                    @if($loop->index==0||$loop->index%7==0)
                        <li>
                            <ul>
                    @endif
                    <li><a href="/{{ $item->seo->slug_full }}" title="{{ $item->name ?? $item->seo->title ?? null }}">{{ $item->name ?? $item->seo->title ?? null }}</a></li>
                    @if(($loop->index+1)%7==0||($loop->index+1)==count($hotelBD))
                            </ul>
                        </li>
                    @endif
                @endforeach
            </ul>
        @endif
    </div>
</div>

{{-- @push('scripts-custom') ===== dùng cache nên đoạn script này đặt ở script_default
    <script type="text/javascript">
        function openMegaMenu(id){
            var elemt	= $('#'+id);
            elemt.siblings().removeClass('selected');
            elemt.addClass('selected');
            $('[data-menu]').each(function(){
                var key	= $(this).attr('data-menu');
                if(key==id){
                $(this).css('display', 'flex');
                }else {
                    $(this).css('display', 'none');
                }
            });
        }
    </script>
@endpush --}}
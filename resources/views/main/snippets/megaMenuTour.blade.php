<div class="megaMenu">
    <div class="megaMenu_title" style="max-width:240px;">
    <ul>
        @if(!empty($dataMB))
            <li id="menu_1" onmouseover="openMegaMenu(this.id);" class="selected">
                <div>Tour Miền Bắc<i class="fas fa-angle-right"></i></div>
            </li>
        @endif
        @if(!empty($dataMT))
            <li id="menu_2" onmouseover="openMegaMenu(this.id);">
                <div>Tour Miền Trung<i class="fas fa-angle-right"></i></div>
            </li>
        @endif
        @if(!empty($dataMN))
            <li id="menu_3" onmouseover="openMegaMenu(this.id);">
                <div>Tour Miền Nam<i class="fas fa-angle-right"></i></div>
            </li>
        @endif
        @if(!empty($dataBD))
            <li id="menu_4" onmouseover="openMegaMenu(this.id);">
                <div>Tour Biển Đảo<i class="fas fa-angle-right"></i></div>
            </li>
        @endif
    </ul>
    </div>
    <div class="megaMenu_content">
        <!-- Danh sách Tour Miền Bắc -->
        @if(!empty($dataMB))
            <ul data-menu="menu_1">
                @foreach($dataMB as $item)
                    @if($loop->index==0||$loop->index%7==0)
                        <li>
                            <ul>
                    @endif
                    <li><a href="/{{ $item->slug_full ?? null }}" title="{{ $item->name ?? $item->seo->title ?? null }}">{{ $item->name ?? $item->seo->title ?? null }}</a></li>
                    @if(($loop->index+1)%7==0||($loop->index+1)==count($dataMB))
                            </ul>
                        </li>
                    @endif
                @endforeach
            </ul>
        @endif
        <!-- Danh sách Tour Miền Trung -->
        @if(!empty($dataMT))
            <ul data-menu="menu_2">
                @foreach($dataMT as $item)
                    @if($loop->index==0||$loop->index%7==0)
                        <li>
                            <ul>
                    @endif
                    <li><a href="/{{ $item->slug_full ?? null }}" title="{{ $item->name ?? $item->seo->title ?? null }}">{{ $item->name ?? $item->seo->title ?? null }}</a></li>
                    @if(($loop->index+1)%7==0||($loop->index+1)==count($dataMT))
                            </ul>
                        </li>
                    @endif
                @endforeach
            </ul>
        @endif
        <!-- Danh sách Tour Miền Nam -->
        @if(!empty($dataMN))
            <ul data-menu="menu_3">
                @foreach($dataMN as $item)
                    @if($loop->index==0||$loop->index%7==0)
                        <li>
                            <ul>
                    @endif
                    <li><a href="/{{ $item->slug_full ?? null }}" title="{{ $item->name ?? $item->seo->title ?? null }}">{{ $item->name ?? $item->seo->title ?? null }}</a></li>
                    @if(($loop->index+1)%7==0||($loop->index+1)==count($dataMN))
                            </ul>
                        </li>
                    @endif
                @endforeach
            </ul>
        @endif
        <!-- Danh sách Tour Biển Đảo -->
        @if(!empty($dataBD))
            <ul data-menu="menu_4">
                @foreach($dataBD as $item)
                    @if($loop->index==0||$loop->index%7==0)
                        <li>
                            <ul>
                    @endif
                    <li><a href="/{{ $item->slug_full }}" title="{{ $item->name ?? $item->seo->title ?? null }}">{{ $item->name ?? $item->seo->title ?? null }}</a></li>
                    @if(($loop->index+1)%7==0||($loop->index+1)==count($dataBD))
                            </ul>
                        </li>
                    @endif
                @endforeach
            </ul>
        @endif
    </div>
</div>

@push('scripts-custom')
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
@endpush
<div class="megaMenu">
    <div class="megaMenu_title" style="max-width:240px;">
    <ul>
        @if(!empty($combosMB))
            @php
                $selected       = empty($flagSelected) ? 'class=selected' : '';
                $flagSelected   = true;
            @endphp
            <li id="menu_1" onmouseover="openMegaMenu(this.id);" {{ $selected }}>
                <div>Combo Miền Bắc<i class="fas fa-angle-right"></i></div>
            </li>
        @endif
        @if(!empty($combosMT))
            @php
                $selected       = empty($flagSelected) ? 'class=selected' : '';
                $flagSelected   = true;
            @endphp
            <li id="menu_2" onmouseover="openMegaMenu(this.id);" {{ $selected }}>
                <div>Combo Miền Trung<i class="fas fa-angle-right"></i></div>
            </li>
        @endif
        @if(!empty($combosMN))
            @php
                $selected       = empty($flagSelected) ? 'class=selected' : '';
                $flagSelected   = true;
            @endphp
            <li id="menu_3" onmouseover="openMegaMenu(this.id);" {{ $selected }}>
                <div>Combo Miền Nam<i class="fas fa-angle-right"></i></div>
            </li>
        @endif
        @if(!empty($combosBD))
            @php
                $selected       = empty($flagSelected) ? 'class=selected' : '';
                $flagSelected   = true;
            @endphp
            <li id="menu_4" onmouseover="openMegaMenu(this.id);" {{ $selected }}>
                <div>Combo Biển Đảo<i class="fas fa-angle-right"></i></div>
            </li>
        @endif
    </ul>
    </div>
    <div class="megaMenu_content">
        <!-- Danh sách Tour Miền Bắc -->
        @if(!empty($combosMB))
            <ul data-menu="menu_1">
                @foreach($combosMB as $item)
                    @if($loop->index==0||$loop->index%7==0)
                        <li>
                            <ul>
                    @endif
                    <li><a href="/{{ $item->seo->slug_full ?? null }}" title="{{ $item->name ?? $item->seo->title ?? null }}">{{ $item->name ?? $item->seo->title ?? null }}</a></li>
                    @if(($loop->index+1)%7==0||($loop->index+1)==count($combosMB))
                            </ul>
                        </li>
                    @endif
                @endforeach
            </ul>
        @endif
        <!-- Danh sách Tour Miền Trung -->
        @if(!empty($combosMT))
            <ul data-menu="menu_2">
                @foreach($combosMT as $item)
                    @if($loop->index==0||$loop->index%7==0)
                        <li>
                            <ul>
                    @endif
                    <li><a href="/{{ $item->seo->slug_full ?? null }}" title="{{ $item->name ?? $item->seo->title ?? null }}">{{ $item->name ?? $item->seo->title ?? null }}</a></li>
                    @if(($loop->index+1)%7==0||($loop->index+1)==count($combosMT))
                            </ul>
                        </li>
                    @endif
                @endforeach
            </ul>
        @endif
        <!-- Danh sách Tour Miền Nam -->
        @if(!empty($combosMN))
            <ul data-menu="menu_3">
                @foreach($combosMN as $item)
                    @if($loop->index==0||$loop->index%7==0)
                        <li>
                            <ul>
                    @endif
                    <li><a href="/{{ $item->seo->slug_full ?? null }}" title="{{ $item->name ?? $item->seo->title ?? null }}">{{ $item->name ?? $item->seo->title ?? null }}</a></li>
                    @if(($loop->index+1)%7==0||($loop->index+1)==count($combosMN))
                            </ul>
                        </li>
                    @endif
                @endforeach
            </ul>
        @endif
        <!-- Danh sách Tour Biển Đảo -->
        @if(!empty($combosBD))
            <ul data-menu="menu_4">
                @foreach($combosBD as $item)
                    @if($loop->index==0||$loop->index%7==0)
                        <li>
                            <ul>
                    @endif
                    <li><a href="/{{ $item->seo->slug_full }}" title="{{ $item->name ?? $item->seo->title ?? null }}">{{ $item->name ?? $item->seo->title ?? null }}</a></li>
                    @if(($loop->index+1)%7==0||($loop->index+1)==count($combosBD))
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
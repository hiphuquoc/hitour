@php
    /* Tàu cao tốc */
    $dataShip   = \App\Models\ShipLocation::select('*')
                        ->with('seo', 'ships.seo')
                        ->get();
    /* Tour du lịch */
    $infoMenuByRegion   = \Illuminate\Support\Facades\DB::table('tour_location as tl')
                            ->join('region_info as ri', 'ri.id', '=', 'tl.region_id')
                            ->join('seo', 'seo.id', '=', 'tl.seo_id')
                            ->select('ri.id as region_id', 'ri.name as region', 'tl.name', 'tl.island', 'seo.slug', 'seo.level', 'seo.parent')
                            ->get()
                            ->toArray();
    $infoMenuByRegion   = \App\Helpers\Url::buildFullLinkArray($infoMenuByRegion);
    $dataMB             = [];
    $dataMT             = [];
    $dataMN             = [];
    $dataBD             = [];
    foreach($infoMenuByRegion as $item){
        /* vùng miền */
        switch($item->region_id){
            case 1:
                $dataMB[]   = $item;
                break;
            case 2:
                $dataMT[]   = $item;
                break;
            case 3:
                $dataMN[]   = $item;
                break;
            default:
                break;
        }
        /* biển đảo */
        if($item->island==true) $dataBD[] = $item;
    }
@endphp
<div class="headerMain">
    <div class="container">
        <div class="headerMain_item">
            <ul>
                <li>
                    <a href="/">
                        <img src="/images/main/svg/home-fff.svg" alt="icon trang chủ Hitour" title="icon trang chủ Hitour" />
                    </a>
                </li>
                <li>
                    <div>
                        <div>Tour du lịch</div>
                    </div>
                    @include('main.snippets.megaMenuTour', compact('dataMB', 'dataMT', 'dataMN', 'dataBD'))
                </li>
                <li>
                    <div>
                        <div>Tàu cao tốc</div>
                    </div>
                    @if($dataShip->isNotEmpty())
                    <div class="normalMenu">
                        <ul>
                            @foreach($dataShip as $shipLocation)
                            <li>
                                <a class="max-line_1" href="/{{ $shipLocation->seo->slug_full }}">{{ $shipLocation->name }}<i class="fas fa-angle-right"></i></a>
                                @if(!empty($shipLocation->ships))
                                    <ul>
                                    @foreach($shipLocation->ships as $ship)
                                        <li class="max-line_1">
                                            <a href="/{{ $ship->seo->slug_full }}">
                                                <div>{{ $ship->name }}</div>
                                            </a>
                                        </li>
                                    @endforeach
                                    </ul>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </li>
                <li>
                    <div>
                        <div>Vé máy bay</div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="headerMain_item" style="flex:0 0 70px;">
            <div class="logoSquare"></div>
        </div>
        <div class="headerMain_item">
            <ul style="justify-content:flex-end;">
                <li>
                    <a href="#">
                        <div>Khách sạn</div>
                    </a>
                </li>
                <li>
                    <a href="/">
                        <div>Vé giải trí</div>
                    </a>
                </li>
                <li>
                    <a href="/">
                        <div>Cẩm nang</div>
                    </a>
                </li>
                <li>
                    <a href="/">
                        <i class="fa-solid fa-bars" style="font-size:1.4rem;margin-top:0.25rem;"></i>
                    </a>
                    <div class="normalMenu" style="margin-right:1.5rem;right:0;">
                        <ul>
                            <li>
                                <a href="#">
                                    <div>Cho thuê xe</div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div>Tin tức</div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div>Liên hệ</div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="header">
    <div class="container">
        @if(!Request::is('/'))
            <div class="header_arrow">
                <a href="#">
                    <i class="fa-solid fa-arrow-left-long"></i>
                </a>
            </div>
        @endif
        <div class="header_logo">
            <a href="/" class="logo">
                <!-- Background Image -->
                <h1 style="display:none;">Trang chủ Hitour - Kênh du lịch trực tuyến hàng đầu Việt Nam</h1>
            </a>
        </div>
        <!-- Menu Mobile -->
        <div class="header_menuMobile">
            {{-- <div class="header_menuMobile_item">
                <form id="formSearchMobile" method="get" action="/tim-kiem">
                    <div class="searchHeaderMobile">
                        <input type="text" name="search_name" placeholder="Tìm kiếm...">
                        <i class="fa-solid fa-magnifying-glass noBackground" onclick="submitForm('formSearchMobile');"></i>
                    </div>
                </form>
            </div> --}}
            <div class="header_menuMobile_item" onclick="javascript:openCloseElemt('nav-mobile');">
                {{-- <img src="/images/svg/icon-menu-mobile.svg" alt="Menu Chuyến tàu văn học" title="Menu Chuyến tàu văn học"> --}}
                <i class="fa-solid fa-bars" style="font-size:1.5rem;margin-top:0.25rem;color:#fff;"></i>
            </div>
        </div>
    </div>
</div>

<div id="nav-mobile" style="display:none;">
    <div class="nav-mobile">
        <div class="nav-mobile_bg" onclick="javascript:openCloseElemt('nav-mobile');"></div>
        <div class="nav-mobile_main customScrollBar-y">
            <div class="nav-mobile_main__exit" onclick="javascript:openCloseElemt('nav-mobile');">
                <i class="fas fa-times"></i>
            </div>
            <a href="/" style="display:flex;justify-content:center;margin-top:5px;margin-bottom:-10px;">
                <div class="logoSquare"></div>
            </a>
            <ul>
                <li>
                    <a href="/">
                        <div><i class="fas fa-home"></i>Trang chủ</div>
                        <div class="right-icon"></div>
                    </a>
                </li>
                @if(!empty($dataBD))
                    <li>
                        <div>
                            <i class="fas fa-umbrella-beach"></i>
                            Tour biển đảo
                        </div>
                        <span class="right-icon" onclick="javascript:showHideListMenuMobile(this);"><i class="fas fa-chevron-right"></i></span>
                        <ul style="display:none;">
                        @foreach($dataBD as $tourBD)
                            <li>
                                <a href="/{{ $tourBD->slug_full }}">
                                    <div>{{ $tourBD->name }}</div>
                                </a>
                            </li>
                        @endforeach
                        </ul>
                    </li>
                @endif
                @if(!empty($dataMB))
                    <li>
                        <div>
                            <i class="fas fa-umbrella-beach"></i>
                            Tour miền bắc
                        </div>
                        <span class="right-icon" onclick="javascript:showHideListMenuMobile(this);"><i class="fas fa-chevron-right"></i></span>
                        <ul style="display:none;">
                        @foreach($dataMB as $tourMB)
                            <li>
                                <a href="/{{ $tourMB->slug_full }}">
                                    <div>{{ $tourMB->name }}</div>
                                </a>
                            </li>
                        @endforeach
                        </ul>
                    </li>
                @endif
                @if(!empty($dataMT))
                    <li>
                        <div>
                            <i class="fas fa-umbrella-beach"></i>
                            Tour miền trung
                        </div>
                        <span class="right-icon" onclick="javascript:showHideListMenuMobile(this);"><i class="fas fa-chevron-right"></i></span>
                        <ul style="display:none;">
                        @foreach($dataMT as $tourMT)
                            <li>
                                <a href="/{{ $tourMT->slug_full }}">
                                    <div>{{ $tourMT->name }}</div>
                                </a>
                            </li>
                        @endforeach
                        </ul>
                    </li>
                @endif
                @if(!empty($dataMN))
                    <li>
                        <div>
                            <i class="fas fa-umbrella-beach"></i>
                            Tour miền nam
                        </div>
                        <span class="right-icon" onclick="javascript:showHideListMenuMobile(this);"><i class="fas fa-chevron-right"></i></span>
                        <ul style="display:none;">
                        @foreach($dataMN as $tourMN)
                            <li>
                                <a href="/{{ $tourMN->slug_full }}">
                                    <div>{{ $tourMN->name }}</div>
                                </a>
                            </li>
                        @endforeach
                        </ul>
                    </li>
                @endif
                @if(!empty($dataShip))
                    <li>
                        <div>
                            <i class="fas fa-ship"></i>
                            Vé tàu cao tốc
                        </div>
                        <span class="right-icon" onclick="javascript:showHideListMenuMobile(this);"><i class="fas fa-chevron-right"></i></span>
                        <ul style="display:none;">
                        @foreach($dataShip as $shipLocation)
                            <li>
                                <a href="/{{ $shipLocation->seo->slug_full }}">
                                    <div>{{ $shipLocation->name }}</div>
                                </a>
                            </li>
                        @endforeach
                        </ul>
                    </li>
                @endif
                <li>
                    <a href="#">
                        <i class="fa-solid fa-plane-departure"></i>
                        <div>Vé máy bay</div>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fa-solid fa-hotel"></i>
                        <div>Khách sạn</div>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fa-solid fa-star"></i>
                        <div>Vui chơi giải trí</div>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fa-solid fa-book"></i>
                        <div>Cẩm nang du lịch</div>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fa-solid fa-phone"></i>
                        <div>Liên hệ</div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

@push('scripts-custom')
    <script type="text/javascript">
        $(window).on('load', function () {
            /* fixed headerMobile khi scroll */
            const elemt                 = $('.header');
            const positionTopElemt      = elemt.offset().top;
            $(window).scroll(function(){
                const positionScrollbar = $(window).scrollTop();
                // const scrollHeight      = $('body').prop('scrollHeight');
                // const heightLimit       = parseInt(scrollHeight - heightFooter - elemt.outerHeight());
                if(positionScrollbar>parseInt(positionTopElemt+50)){
                    elemt.css({
                        'top'       : '0',
                        'position'  : 'fixed',
                        'left'      : 0
                    });
                }else {
                    elemt.css({
                        'top'       : '0',
                        'position'  : 'relative',
                        'left'      : 0
                    });
                }
            });
        });

        function showHideListMenuMobile(thisD){
            let elemtC      = $(thisD).parent().find('ul');
            let displayC    = elemtC.css('display');
            if(displayC=='none'){
                elemtC.css('display', 'block');
                $(thisD).html('<i class="fas fa-chevron-down"></i>');
            }else {
                elemtC.css('display', 'none');
                $(thisD).html('<i class="fas fa-chevron-right"></i>');
            }
        }
        function openCloseElemt(idElemt){
            let displayE    = $('#' + idElemt).css('display');
            if(displayE=='none'){
                $('#' + idElemt).css('display', 'block');
                $('body').css('overflow', 'hidden');
            }else {
                $('#' + idElemt).css('display', 'none');
                $('body').css('overflow', 'unset');
            }
        }
    </script>
@endpush
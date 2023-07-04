@php
    /* Vé máy bay */
    $dataAir            = \App\Models\AirLocation::select('*')
                            ->with('seo', 'airs.seo')
                            ->get();
    /* Tàu cao tốc */
    $dataShip           = \App\Models\ShipLocation::select('*')
                            ->with('seo', 'ships.seo')
                            ->get();
    /* Vé vui chơi */
    $dataService        = \App\Models\ServiceLocation::select('*')
                            ->whereHas('services', function(){
                                
                            })
                            ->with('seo')
                            ->get();
    /* Tour trong nước */
    $infoMenuByRegion   = \App\Models\TourLocation::select('*')
                            ->with('seo', 'region')
                            ->get();
    $dataMB             = [];
    $dataMT             = [];
    $dataMN             = [];
    $dataBD             = [];
    foreach($infoMenuByRegion as $item){
        /* vùng miền */
        switch($item->region->id){
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
    /* Tour nước ngoài */
    $dataTourContinent  = \App\Models\TourContinent::select('*')
                            ->with('tourCountries', function($query){
                                $query->whereHas('tours', function($q){

                                });
                            })
                            ->get();
    /* Combo du lịch */
    $dataComboLocation  = \App\Models\ComboLocation::select('*')
                            ->whereHas('combos', function($query){
                                
                            })
                            ->with('seo', 'region', 'combos')
                            ->get();
    $combosMB           = [];
    $combosMT           = [];
    $combosMN           = [];
    $combosBD           = [];
    foreach($dataComboLocation as $item){
        /* vùng miền */
        switch($item->region->id){
            case 1:
                $combosMB[] = $item;
                break;
            case 2:
                $combosMT[] = $item;
                break;
            case 3:
                $combosMN[] = $item;
                break;
            default:
                break;
        }
        /* biển đảo */
        if($item->island==true) $combosBD[] = $item;
    }
@endphp

<!-- START:: Menu Desktop -->
<div class="headerMain">
    <div class="container">
        <div class="headerMain_item">
            <ul>
                <li>
                    <a href="/" title="Trang chủ {{ config('company.sortname') }}">
                        <img src="/images/main/svg/home-fff.svg" alt="Trang chủ {{ config('company.sortname') }}" title="Trang chủ {{ config('company.sortname') }}" />
                    </a>
                </li>
                <li>
                    <div>
                        <div>Tour trong nước</div>
                    </div>
                    @include('main.snippets.megaMenuTour', compact('dataMB', 'dataMT', 'dataMN', 'dataBD'))
                </li>
                <li>
                    <div>
                        <div>Tour nước ngoài</div>
                    </div>
                    @include('main.snippets.megaMenuTourContinent', compact('dataTourContinent'))
                </li>
                {{-- <li>
                    <div>
                        <a href="https://www.booking.com" title="Đặt phòng khách sạn" rel="nofollow" style="padding-right:0;">Khách sạn</a>
                    <div>
                </li> --}}
                @if(!empty($dataComboLocation)&&$dataComboLocation->isNotEmpty())
                    <li>
                        <div>
                            <div>Combo</div>
                        </div>
                        @include('main.snippets.megaMenuCombo', compact('combosMB', 'combosMT', 'combosMN', 'combosBD'))
                    </li>
                @endif
            </ul>
        </div>
        <div class="headerMain_item" style="flex:0 0 70px;">
            @if(Request::is('/'))
                <a href="/" title="Trang chủ {{ config('company.sortname') }}" class="logoSquare"><h1 style="display:none;">{{ config('main.description') }}</h1></a>
            @else 
                <a href="/" title="Trang chủ {{ config('company.sortname') }}" class="logoSquare"></a>
            @endif
        </div>
        <div class="headerMain_item">
            <ul style="justify-content:flex-end;">
                <li>
                    <div>
                        <div>Vé máy bay</div>
                    </div>
                    @if(!empty($dataAir)&&$dataAir->isNotEmpty())
                        <div class="megaMenu">
                            <div class="megaMenu_content" style="width:100%;">
                                <ul>
                                    @php
                                        $i = 0;
                                        $xhtml = null;
                                        foreach($dataAir as $air){
                                            if($i==0) $xhtml = '<li><ul>';
                                            if($i!=0&&$i%7==0) $xhtml .= '</ul></li><li><ul>';
                                            $xhtml .= '<li><a href="/'.$air->seo->slug_full.'" title="'.$air->name.'">'.$air->name.'</a></li>';
                                            if($i==($dataAir->count()-1)) $xhtml .= '</ul></li>';
                                            ++$i;
                                        }
                                        echo $xhtml;
                                    @endphp
                                </ul>
                            </div>
                        </div>
                    @endif
                </li>
                <li>
                    <div>
                        <div>Vé tàu</div>
                    </div>
                    @if(!empty($dataShip)&&$dataShip->isNotEmpty())
                    <div class="normalMenu">
                        <ul>
                            @foreach($dataShip as $shipLocation)
                            <li>
                                <a class="max-line_1" href="/{{ $shipLocation->seo->slug_full ?? null }}" title="{{ $shipLocation->name ?? $shipLocation->seo->title ?? null }}">{{ $shipLocation->name ?? $shipLocation->seo->title ?? null }}<i class="fas fa-angle-right"></i></a>
                                @if(!empty($shipLocation->ships))
                                    <ul style="left:unset;right:250px;box-shadow: 0 2px 3px #adb5bd;">
                                    @foreach($shipLocation->ships as $ship)
                                        <li class="max-line_1">
                                            <a href="/{{ $ship->seo->slug_full ?? null }}" title="{{ $ship->name ?? $ship->seo->title ?? null }}">
                                                <div>{{ $ship->name ?? $ship->seo->title ?? null }}</div>
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
                        <div>Vé vui chơi</div>
                    </div>
                    <div class="normalMenu">
                        <ul>
                            @foreach($dataService as $serviceLocation)
                            <li>
                                <a class="max-line_1" href="/{{ $serviceLocation->seo->slug_full ?? null }}" title="{{ $serviceLocation->name ?? $serviceLocation->seo->title ?? null }}">{{ $serviceLocation->display_name ?? null }}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </li>
                
                <li>
                    <div>
                        <i class="fa-solid fa-bars" style="font-size:1.4rem;margin-top:0.25rem;"></i>
                    </div>
                    <div class="normalMenu" style="margin-right:1.5rem;right:0;">
                        <ul>
                            {{-- <li>
                                <a href="#">
                                    <div>Cho thuê xe</div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div>Cẩm nang du lịch</div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div>Điểm đến</div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div>Đặc sản</div>
                                </a>
                            </li> --}}
                            <li>
                                <a href="/lien-he-hitour" title="Liên hệ {{ config('company.sortname') }}">
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
<!-- Background Hover -->
{{-- <div class="backgroundHover"></div> --}}
<!-- END:: Menu Desktop -->

<!-- START:: Header Mobile -->
<div class="header">
    <div class="container">
        @if(Request::is('/'))
            <div class="header_logo">
                <a href="/" title="Trang chủ {{ config('company.sortname') }}" class="logo">
                    <h1 style="display:none;">{{ config('main.description') }}</h1>
                </a>
            </div>
        @else 
            <div class="header_arrow">
                <a href="javascript:history.back()" title="Quay lại trang trước">
                    <i class="fa-solid fa-arrow-left-long"></i>
                </a>
            </div>
            <div class="header_logo">
                <a href="/" title="Trang chủ {{ config('company.sortname') }}" class="logo"></a>
            </div>
        @endif
        <!-- Menu Mobile -->
        <div class="header_menuMobile">
            <div class="header_menuMobile_item" onclick="javascript:openCloseElemt('nav-mobile');">
                <i class="fa-solid fa-bars" style="font-size:1.5rem;margin-top:0.25rem;color:#fff;"></i>
            </div>
        </div>
    </div>
</div>
<!-- END:: Header Mobile -->

<!-- START:: Menu Mobile -->
<div id="nav-mobile" style="display:none;">
    <div class="nav-mobile">
        <div class="nav-mobile_bg" onclick="javascript:openCloseElemt('nav-mobile');"></div>
        <div class="nav-mobile_main customScrollBar-y">
            <div class="nav-mobile_main__exit" onclick="javascript:openCloseElemt('nav-mobile');">
                <i class="fas fa-times"></i>
            </div>
            <a href="/" title="Trang chủ {{ config('company.sortname') }}" style="display:flex;justify-content:center;margin-top:5px;margin-bottom:-10px;">
                <div class="logoSquare"></div>
            </a>
            <ul>
                <li>
                    <a href="/" title="Trang chủ {{ config('company.sortname') }}">
                        <div><i class="fas fa-home"></i>Trang chủ</div>
                        <div class="right-icon"></div>
                    </a>
                </li>
                <li>
                    <div>
                        <i class="fas fa-umbrella-beach"></i>
                        Tour nước ngoài
                    </div>
                    <span class="right-icon" onclick="javascript:showHideListMenuMobile(this);"><i class="fas fa-chevron-right"></i></span>
                    <ul style="display:none;">
                    @foreach($dataTourContinent as $tourContinent)
                        <li>
                            <a href="/{{ $tourContinent->seo->slug_full ?? null}}" title="{{ $tourContinent->name ?? $tourContinent->seo->title ?? null }}">
                                <div>{{ $tourContinent->name ?? $tourContinent->seo->title ?? null }}</div>
                            </a>
                        </li>
                    @endforeach
                    </ul>
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
                                <a href="/{{ $tourBD->seo->slug_full ?? null }}" title="{{ $tourBD->name ?? $tourBD->seo->title ?? null }}">
                                    <div>{{ $tourBD->name ?? $tourBD->seo->title ?? null }}</div>
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
                                <a href="/{{ $tourMB->seo->slug_full ?? null }}" title="{{ $tourMB->name ?? $tourMB->seo->title ?? null }}">
                                    <div>{{ $tourMB->name ?? $tourMB->seo->title ?? null }}</div>
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
                                <a href="/{{ $tourMT->seo->slug_full ?? null }}" title="{{ $tourMT->name ?? $tourMT->seo->title ?? null }}">
                                    <div>{{ $tourMT->name ?? $tourMT->seo->title ?? null }}</div>
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
                                <a href="/{{ $tourMN->seo->slug_full ?? null }}" title="{{ $tourMN->name ?? $tourMN->seo->title ?? null }}">
                                    <div>{{ $tourMN->name ?? $tourMN->seo->title ?? null }}</div>
                                </a>
                            </li>
                        @endforeach
                        </ul>
                    </li>
                @endif
                @if(!empty($dataShip)&&$dataShip->isNotEmpty())
                    <li>
                        <div>
                            <i class="fas fa-ship"></i>
                            Vé tàu cao tốc
                        </div>
                        <span class="right-icon" onclick="javascript:showHideListMenuMobile(this);"><i class="fas fa-chevron-right"></i></span>
                        <ul style="display:none;">
                        @foreach($dataShip as $shipLocation)
                            <li>
                                <a href="/{{ $shipLocation->seo->slug_full ?? null }}" title="{{ $shipLocation->name ?? $shipLocation->seo->title ?? null }}">
                                    <div>{{ $shipLocation->name ?? $shipLocation->seo->title ?? null }}</div>
                                </a>
                            </li>
                        @endforeach
                        </ul>
                    </li>
                @endif
                <li>
                    <div>
                        <i class="fa-solid fa-plane-departure"></i>
                        <div>Vé máy bay</div>
                    </div>
                    @if(!empty($dataAir)&&$dataAir->isNotEmpty())
                        <span class="right-icon" onclick="javascript:showHideListMenuMobile(this);"><i class="fas fa-chevron-right"></i></span>
                        <ul style="display:none;">
                        @foreach($dataAir as $airLocation)
                            <li>
                                <a href="/{{ $airLocation->seo->slug_full ?? null }}" title="{{ $airLocation->name ?? $airLocation->seo->title ?? null }}">
                                    <div>{{ $airLocation->name ?? $airLocation->seo->title ?? null }}</div>
                                </a>
                            </li>
                        @endforeach
                        </ul>
                    @endif
                </li>
                <li>
                    <a href="https://www.booking.com" title="Đặt phòng khách sạn">
                        <i class="fa-solid fa-hotel"></i>
                        <div>Khách sạn</div>
                    </a>
                </li>
                <li>
                    <div>
                        <i class="fa-solid fa-star"></i>
                        <div>Vé vui chơi</div>
                    </div>
                    @if(!empty($dataService)&&$dataService->isNotEmpty())
                        <span class="right-icon" onclick="javascript:showHideListMenuMobile(this);"><i class="fas fa-chevron-right"></i></span>
                        <ul style="display:none;">
                        @foreach($dataService as $serviceLocation)
                            <li>
                                <a href="/{{ $serviceLocation->seo->slug_full ?? null }}" title="{{ $serviceLocation->display_name ?? null }}">
                                    <div>{{ $serviceLocation->display_name ?? null }}</div>
                                </a>
                            </li>
                        @endforeach
                        </ul>
                    @endif
                </li>
                {{-- <li>
                    <div>
                        <i class="fa-solid fa-book"></i>
                        <div>Cẩm nang du lịch</div>
                    </div>
                </li> --}}
                <li>
                    <a href="/lien-he-hitour" title="Liên hệ {{ config('company.sortname') }}">
                        <i class="fa-solid fa-phone"></i>
                        <div>Liên hệ</div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- END:: Menu Mobile -->

{{-- @push('scripts-custom') ===== dùng cache nên đoạn script này đặt ở script_default
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
@endpush --}}
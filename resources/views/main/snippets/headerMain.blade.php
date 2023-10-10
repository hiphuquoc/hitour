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
    // /* Tour nước ngoài */
    // $dataTourContinent  = \App\Models\TourContinent::select('*')
    //                         ->with('tourCountries', function($query){
    //                             $query->whereHas('tours', function($q){

    //                             });
    //                         })
    //                         ->get();
    /* khách sạn */
    $dataHotelLocation  = \App\Models\HotelLocation::select('*')
                            ->with('hotels', function($query){

                            })
                            ->get();
    $hotelMB            = [];
    $hotelMT            = [];
    $hotelMN            = [];
    $hotelBD            = [];
    foreach($dataHotelLocation as $item){
        /* vùng miền */
        switch($item->region->id){
            case 1:
                $hotelMB[]  = $item;
                break;
            case 2:
                $hotelMT[]  = $item;
                break;
            case 3:
                $hotelMN[]  = $item;
                break;
            default:
                break;
        }
        /* biển đảo */
        if($item->island==true) $hotelBD[] = $item;
    }
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
     /* Tour trong nước */
     $guideMenuByRegion = \App\Models\Guide::select('*')
                            ->with('seo', 'region')
                            ->get();
    $guideMB            = [];
    $guideMT            = [];
    $guideMN            = [];
    foreach($guideMenuByRegion as $item){
        /* vùng miền */
        switch($item->region->id){
            case 1:
                $guideMB[]   = $item;
                break;
            case 2:
                $guideMT[]   = $item;
                break;
            case 3:
                $guideMN[]   = $item;
                break;
            default:
                break;
        }
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
                {{-- <li>
                    <div>
                        <div>Tour nước ngoài</div>
                    </div>
                    @include('main.snippets.megaMenuTourContinent', compact('dataTourContinent'))
                </li> --}}
                <li>
                    <div>
                        <div>Khách sạn</div>
                    </div>
                    @include('main.snippets.megaMenuHotel', compact('hotelMB', 'hotelMT', 'hotelMN', 'hotelBD'))
                </li>
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
                <a href="history.back()" title="Quay lại trang trước">
                    <i class="fa-solid fa-arrow-left-long"></i>
                </a>
            </div>
            <div class="header_logo">
                <a href="/" title="Trang chủ {{ config('company.sortname') }}" class="logo"></a>
            </div>
        @endif
        <!-- Menu Mobile -->
        <div class="header_menuMobile">
            <div class="header_menuMobile_item" onclick="openCloseElemt('nav-mobile');">
                <i class="fa-solid fa-bars" style="font-size:1.5rem;margin-top:0.25rem;color:#fff;"></i>
            </div>
        </div>
    </div>
</div>
<!-- END:: Header Mobile -->

<!-- START:: Menu Mobile -->
<div id="nav-mobile" style="display:none;">
    <div class="nav-mobile">
        <div class="nav-mobile_bg" onclick="openCloseElemt('nav-mobile');"></div>
        <div class="nav-mobile_main customScrollBar-y">
            <div class="nav-mobile_main__exit" onclick="openCloseElemt('nav-mobile');">
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
                {{-- <li>
                    <div>
                        <i class="fas fa-umbrella-beach"></i>
                        Tour nước ngoài
                    </div>
                    <span class="right-icon" onclick="showHideListMenuMobile(this);"><i class="fas fa-chevron-right"></i></span>
                    <ul style="display:none;">
                    @foreach($dataTourContinent as $tourContinent)
                        <li>
                            <a href="/{{ $tourContinent->seo->slug_full ?? null}}" title="{{ $tourContinent->name ?? $tourContinent->seo->title ?? null }}">
                                <div>{{ $tourContinent->name ?? $tourContinent->seo->title ?? null }}</div>
                            </a>
                        </li>
                    @endforeach
                    </ul>
                </li> --}}
                <li>
                    <div onclick="showHideListMenuMobile(this);">
                        <i class="fas fa-umbrella-beach"></i>
                        <span class="nav-mobile_main__title">Tour Du Lịch</span>
                        <span class="nav-mobile_main__arrow"><i class="fas fa-chevron-right"></i></span>
                    </div>
                    <ul style="display:none;">
                        @if(!empty($dataBD))
                            <li>
                                <div onclick="showHideListMenuMobile(this);">
                                    <span class="nav-mobile_main__title">Tour Biển Đảo</span>
                                    <span class="nav-mobile_main__arrow"><i class="fas fa-chevron-right"></i></span>
                                </div>
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
                                <div onclick="showHideListMenuMobile(this);">
                                    <span class="nav-mobile_main__title">Tour Miền Bắc</span>
                                    <span class="nav-mobile_main__arrow"><i class="fas fa-chevron-right"></i></span>
                                </div>
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
                                <div onclick="showHideListMenuMobile(this);">
                                    <span class="nav-mobile_main__title">Tour Miền Trung</span>
                                    <span class="nav-mobile_main__arrow"><i class="fas fa-chevron-right"></i></span>
                                </div>
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
                                <div onclick="showHideListMenuMobile(this);">
                                    <span class="nav-mobile_main__title">Tour Miền Nam</span>
                                    <span class="nav-mobile_main__arrow"><i class="fas fa-chevron-right"></i></span>
                                </div>
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
                    </ul>
                </li>
                
                
                @if(!empty($dataShip)&&$dataShip->isNotEmpty())
                    <li>
                        <div onclick="showHideListMenuMobile(this);">
                            <i class="fas fa-ship"></i>
                            <span class="nav-mobile_main__title">Vé Tàu Cao Tốc</span>
                            <span class="nav-mobile_main__arrow"><i class="fas fa-chevron-right"></i></span>
                        </div>
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
                    <div onclick="showHideListMenuMobile(this);">
                        <i class="fa-solid fa-bed"></i>
                        <span class="nav-mobile_main__title">Khách Sạn</span>
                        <span class="nav-mobile_main__arrow"><i class="fas fa-chevron-right"></i></span>
                    </div>
                    <ul style="display:none;">
                        @if(!empty($hotelBD))
                            <li>
                                <div onclick="showHideListMenuMobile(this);">
                                    <span class="nav-mobile_main__title">Khách Sạn Biển Đảo</span>
                                    <span class="nav-mobile_main__arrow"><i class="fas fa-chevron-right"></i></span>
                                </div>
                                <ul style="display:none;">
                                @foreach($hotelBD as $h)
                                    <li>
                                        <a href="/{{ $h->seo->slug_full ?? null }}" title="{{ $h->name ?? $h->seo->title ?? null }}">
                                            <div>{{ $h->name ?? $h->seo->title ?? null }}</div>
                                        </a>
                                    </li>
                                @endforeach
                                </ul>
                            </li>
                        @endif
                        @if(!empty($hotelMB))
                            <li>
                                <div onclick="showHideListMenuMobile(this);">
                                    <span class="nav-mobile_main__title">Khách Sạn Miền Bắc</span>
                                    <span class="nav-mobile_main__arrow"><i class="fas fa-chevron-right"></i></span>
                                </div>
                                <ul style="display:none;">
                                @foreach($hotelMB as $h)
                                    <li>
                                        <a href="/{{ $h->seo->slug_full ?? null }}" title="{{ $h->name ?? $h->seo->title ?? null }}">
                                            <div>{{ $h->name ?? $h->seo->title ?? null }}</div>
                                        </a>
                                    </li>
                                @endforeach
                                </ul>
                            </li>
                        @endif
                        @if(!empty($hotelMT))
                            <li>
                                <div onclick="showHideListMenuMobile(this);">
                                    <span class="nav-mobile_main__title">Khách Sạn Miền Trung</span>
                                    <span class="nav-mobile_main__arrow"><i class="fas fa-chevron-right"></i></span>
                                </div>
                                <ul style="display:none;">
                                @foreach($hotelMT as $h)
                                    <li>
                                        <a href="/{{ $h->seo->slug_full ?? null }}" title="{{ $h->name ?? $h->seo->title ?? null }}">
                                            <div>{{ $h->name ?? $h->seo->title ?? null }}</div>
                                        </a>
                                    </li>
                                @endforeach
                                </ul>
                            </li>
                        @endif
                        @if(!empty($hotelMN))
                            <li>
                                <div onclick="showHideListMenuMobile(this);">
                                    <span class="nav-mobile_main__title">Khách Sạn Miền Nam</span>
                                    <span class="nav-mobile_main__arrow"><i class="fas fa-chevron-right"></i></span>
                                </div>
                                <ul style="display:none;">
                                @foreach($hotelMN as $h)
                                    <li>
                                        <a href="/{{ $h->seo->slug_full ?? null }}" title="{{ $h->name ?? $h->seo->title ?? null }}">
                                            <div>{{ $h->name ?? $h->seo->title ?? null }}</div>
                                        </a>
                                    </li>
                                @endforeach
                                </ul>
                            </li>
                        @endif
                    </ul>
                </li>

                <li>
                    <div onclick="showHideListMenuMobile(this);">
                        <i class="fa-solid fa-star"></i>
                        <span class="nav-mobile_main__title">Vé Vui Chơi</span>
                        <span class="nav-mobile_main__arrow"><i class="fas fa-chevron-right"></i></span>
                    </div>
                    <ul style="display:none;">
                        @foreach($dataService as $serviceLocation)
                            <li>
                                <a href="/{{ $serviceLocation->seo->slug_full ?? null }}" title="{{ $serviceLocation->display_name ?? null }}">
                                    <div>{{ $serviceLocation->display_name ?? null }}</div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
                
                <li>
                    <div onclick="showHideListMenuMobile(this);">
                        <i class="fa-solid fa-plane-departure"></i>
                        <span class="nav-mobile_main__title">Vé Máy Bay</span>
                        <span class="nav-mobile_main__arrow"><i class="fas fa-chevron-right"></i></span>
                    </div>
                    <ul style="display:none;">
                    @foreach($dataAir as $airLocation)
                        <li>
                            <a href="/{{ $airLocation->seo->slug_full ?? null }}" title="{{ $airLocation->name ?? $airLocation->seo->title ?? null }}">
                                <div>{{ $airLocation->name ?? $airLocation->seo->title ?? null }}</div>
                            </a>
                        </li>
                    @endforeach
                    </ul>
                </li>
                
                <li>
                    <div onclick="showHideListMenuMobile(this);">
                        <i class="fa-solid fa-book"></i>
                        <span class="nav-mobile_main__title">Cẩm Nang Du Lịch</span>
                        <span class="nav-mobile_main__arrow"><i class="fas fa-chevron-right"></i></span>
                    </div>
                    <ul style="display:none;">
                        @if(!empty($guideMB))
                            <li>
                                <div onclick="showHideListMenuMobile(this);">
                                    <span class="nav-mobile_main__title">Cảm Nang Miền Bắc</span>
                                    <span class="nav-mobile_main__arrow"><i class="fas fa-chevron-right"></i></span>
                                </div>
                                <ul style="display:none;">
                                @foreach($guideMB as $h)
                                    <li>
                                        <a href="/{{ $h->seo->slug_full ?? null }}" title="{{ $h->name ?? $h->seo->title ?? null }}">
                                            <div>Cẩm Nang {{ $h->display_name ?? null }}</div>
                                        </a>
                                    </li>
                                @endforeach
                                </ul>
                            </li>
                        @endif
                        @if(!empty($guideMT))
                            <li>
                                <div onclick="showHideListMenuMobile(this);">
                                    <span class="nav-mobile_main__title">Cẩm Nang Miền Trung</span>
                                    <span class="nav-mobile_main__arrow"><i class="fas fa-chevron-right"></i></span>
                                </div>
                                <ul style="display:none;">
                                @foreach($guideMT as $h)
                                    <li>
                                        <a href="/{{ $h->seo->slug_full ?? null }}" title="{{ $h->name ?? $h->seo->title ?? null }}">
                                            <div>Cẩm Nang {{ $h->display_name ?? null }}</div>
                                        </a>
                                    </li>
                                @endforeach
                                </ul>
                            </li>
                        @endif
                        @if(!empty($guideMN))
                            <li>
                                <div onclick="showHideListMenuMobile(this);">
                                    <span class="nav-mobile_main__title">Cẩm Nang Miền Nam</span>
                                    <span class="nav-mobile_main__arrow"><i class="fas fa-chevron-right"></i></span>
                                </div>
                                <ul style="display:none;">
                                @foreach($guideMN as $h)
                                    <li>
                                        <a href="/{{ $h->seo->slug_full ?? null }}" title="{{ $h->name ?? $h->seo->title ?? null }}">
                                            <div>Cẩm Nang {{ $h->display_name ?? null }}</div>
                                        </a>
                                    </li>
                                @endforeach
                                </ul>
                            </li>
                        @endif
                    </ul>
                </li>

                <li>
                    <a href="/lien-he-hitour" title="Liên hệ {{ config('company.sortname') }}">
                        <i class="fa-solid fa-phone"></i>
                        <span class="nav-mobile_main__title">Liên Hệ</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- END:: Menu Mobile -->
@php
    $dataShipMenu   = \App\Models\ShipLocation::select('*')
                        ->with('seo', 'ships.seo')
                        ->get();
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
                    @include('main.snippets.megaMenuTour')
                </li>
                <li>
                    <div>
                        <div>Tàu cao tốc</div>
                    </div>
                    @if($dataShipMenu->isNotEmpty())
                    <div class="normalMenu">
                        <ul>
                            @foreach($dataShipMenu as $shipLocation)
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
            </ul>
        </div>
        <div class="headerMain_item" style="flex:0 0 70px;">
            <div class="logoSquare"></div>
        </div>
        <div class="headerMain_item">
            <ul style="justify-content:flex-end;">
                <li>
                    <a href="/">
                        <div>Vé Vinpearl</div>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <div>Khách sạn</div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
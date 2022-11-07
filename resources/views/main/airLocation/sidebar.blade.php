<div class="js_scrollFixed">

   <div class="callBookTour">
      @include('main.template.callbook', ['button' => 'Đặt vé tàu', 'flagButton' => false])
   </div>

   @php
       $flagMargin   = null;
   @endphp
   @if(!empty($item->tourLocations)&&$item->tourLocations->isNotEmpty())
      @php
         $flagMargin = 'margin-top:1.5rem;';
      @endphp
      <div class="serviceRelatedSidebarBox" style="margin-top:1.5rem;">
         <div class="serviceRelatedSidebarBox_title">
            <h2>{{ config('main.title_list_service_sidebar') }}</h2>
         </div>
         <div class="serviceRelatedSidebarBox_box">

            <!-- tour du lịch -->
            @foreach($item->tourLocations as $tourLocation)
               <a href="/{{ $tourLocation->infoTourLocation->seo->slug_full ?? null }}" title="{{ $tourLocation->infoTourLocation->name ?? $tourLocation->infoTourLocation->seo->title ?? null }}" class="serviceRelatedSidebarBox_box_item">
                  <i class="fa-solid fa-person-hiking"></i><h3>{{ $tourLocation->infoTourLocation->name ?? $tourLocation->infoTourLocation->seo->title ?? null }}</h3>
               </a>
            @endforeach

            <!-- tàu cao tốc -->
            @foreach($item->tourLocations as $tourLocation)
               @if($tourLocation->infoTourLocation->shipLocations->isNotEmpty())
                  @foreach($tourLocation->infoTourLocation->shipLocations as $shipLocation)
                     <a href="/{{ $shipLocation->infoShipLocation->seo->slug_full ?? null }}" title="{{ $shipLocation->infoShipLocation->name ?? $shipLocation->infoShipLocation->seo->title ?? null }}" class="serviceRelatedSidebarBox_box_item">
                        <i class="fa-solid fa-ship"></i><h3>{{ $shipLocation->infoShipLocation->name ?? $shipLocation->infoShipLocation->seo->title ?? null }}</h3>
                     </a>
                  @endforeach
               @endif
            @endforeach

            <!-- vé vui chơi -->
            @foreach($item->tourLocations as $tourLocation)
               @if($tourLocation->infoTourLocation->serviceLocations->isNotEmpty())
                  @foreach($tourLocation->infoTourLocation->serviceLocations as $serviceLocation)
                     <a href="/{{ $serviceLocation->infoServiceLocation->seo->slug_full ?? null }}" title="{{ $serviceLocation->infoServiceLocation->name ?? $serviceLocation->infoServiceLocation->seo->title ?? null }}" class="serviceRelatedSidebarBox_box_item">
                        <i class="fa-solid fa-star"></i><h3>{{ $serviceLocation->infoServiceLocation->name ?? $serviceLocation->infoServiceLocation->seo->title ?? null }}</h3>
                     </a>
                  @endforeach
               @endif
            @endforeach

            <!-- cho thuê xe -->
            @foreach($item->tourLocations as $tourLocation)
               @if($tourLocation->infoTourLocation->carrentalLocations->isNotEmpty())
                  @foreach($tourLocation->infoTourLocation->carrentalLocations as $carrentalLocation)
                     <a href="/{{ $carrentalLocation->infoCarrentalLocation->seo->slug_full ?? null }}" title="{{ $carrentalLocation->infoCarrentalLocation->name ?? $carrentalLocation->infoCarrentalLocation->seo->title ?? null }}" class="serviceRelatedSidebarBox_box_item">
                        <i class="fa-solid fa-car-side"></i><h3>{{ $carrentalLocation->infoCarrentalLocation->name ?? $carrentalLocation->infoCarrentalLocation->seo->title ?? null }}</h3>
                     </a>
                  @endforeach
               @endif
            @endforeach

            <!-- cẩm nang du lịch -->
            @foreach($item->tourLocations as $tourLocation)
               @if($tourLocation->infoTourLocation->guides->isNotEmpty())
                  @foreach($tourLocation->infoTourLocation->guides as $guide)
                     <a href="/{{ $guide->infoGuide->seo->slug_full ?? null }}" title="{{ $guide->infoGuide->name ?? $guide->infoGuide->seo->title ?? null }}" class="serviceRelatedSidebarBox_box_item">
                        <i class="fa-solid fa-book"></i><h3>{{ $guide->infoGuide->name ?? $guide->infoGuide->seo->title ?? null }}</h3>
                     </a>
                  @endforeach
               @endif
            @endforeach

            {{-- <a href="#" class="serviceRelatedSidebarBox_box_item">
               <i class="fa-solid fa-building"></i><h3>Khách sạn Phú Quốc</h3>
            </a>
            <a href="#" class="serviceRelatedSidebarBox_box_item">
               <i class="fa-solid fa-plane-departure"></i><h3>Vé máy bay</h3>
            </a> --}}
         </div>
      </div>
   @endif

   <div id="js_buildTocContentSidebar_idWrite" class="tocContentTour customScrollBar-y" style="{{ $flagMargin }}">
      <!-- loadTocContent ajax -->
   </div>

</div>
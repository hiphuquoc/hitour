<div class="js_scrollFixed">
   <div class="callBookTour">
      @include('main.template.callbook', [
         'flagButton'    => true,
         'button'        => 'Đặt vé',
         'linkFull'      => $linkBooking
      ])
   </div>

   @php
       $flagMargin = null;
   @endphp
   @if(!empty($item->serviceLocation->tourLocations)&&$item->serviceLocation->tourLocations->isNotEmpty())
      @php
         $flagMargin = 'margin-top:1.5rem;';
      @endphp
      <div class="serviceRelatedSidebarBox" style="margin-top:1.5rem;">
         <div class="serviceRelatedSidebarBox_title">
            <h2>{{ config('main.title_list_service_sidebar') }}</h2>
         </div>
         <div class="serviceRelatedSidebarBox_box">
            <!-- tour du lịch -->
            @foreach($item->serviceLocation->tourLocations as $tourLocation)
               <a href="/{{ $tourLocation->infoTourLocation->seo->slug_full ?? null }}" title="{{ $tourLocation->infoTourLocation->name ?? $tourLocation->infoTourLocation->seo->title ?? null }}" class="serviceRelatedSidebarBox_box_item">
                  <i class="fa-solid fa-person-hiking"></i><h3>{{ $tourLocation->infoTourLocation->name ?? $tourLocation->infoTourLocation->seo->title ?? null }}</h3>
               </a>
            @endforeach

            <!-- vé máy bay -->
            @foreach($item->serviceLocation->tourLocations as $tourLocation)
               @if($tourLocation->infoTourLocation->airLocations->isNotEmpty())
                  @foreach($tourLocation->infoTourLocation->airLocations as $airLocation)
                     <a href="/{{ $airLocation->infoAirLocation->seo->slug_full ?? null }}" title="{{ $airLocation->infoAirLocation->name ?? $airLocation->infoAirLocation->seo->title ?? null }}" class="serviceRelatedSidebarBox_box_item">
                        <i class="fa-solid fa-paper-plane"></i><h3>{{ $airLocation->infoAirLocation->name ?? $airLocation->infoAirLocation->seo->title ?? null }}</h3>
                     </a>
                  @endforeach
               @endif
            @endforeach

            <!-- tàu cao tốc -->
            @foreach($item->serviceLocation->tourLocations as $tourLocation)
               @if($tourLocation->infoTourLocation->shipLocations->isNotEmpty())
                  @foreach($tourLocation->infoTourLocation->shipLocations as $shipLocation)
                     <a href="/{{ $shipLocation->infoShipLocation->seo->slug_full ?? null }}" title="{{ $shipLocation->infoShipLocation->name ?? $shipLocation->infoShipLocation->seo->title ?? null }}" class="serviceRelatedSidebarBox_box_item">
                        <i class="fa-solid fa-ship"></i><h3>{{ $shipLocation->infoShipLocation->name ?? $shipLocation->infoShipLocation->seo->title ?? null }}</h3>
                     </a>
                  @endforeach
               @endif
            @endforeach

            <!-- vé vui chơi -->
            @if(!empty($item->serviceLocation))
               <a href="/{{ $item->serviceLocation->seo->slug_full ?? null }}" title="{{ $item->serviceLocation->name ?? $item->serviceLocation->seo->title ?? null }}" class="serviceRelatedSidebarBox_box_item">
                  <i class="fa-solid fa-star"></i><h3>{{ $item->serviceLocation->name ?? $item->serviceLocation->seo->title ?? null }}</h3>
               </a>
            @endif
            
            <!-- cho thuê xe -->
            @foreach($item->serviceLocation->tourLocations as $tourLocation)
               @if($tourLocation->infoTourLocation->carrentalLocations->isNotEmpty())
                  @foreach($tourLocation->infoTourLocation->carrentalLocations as $carrentalLocation)
                     <a href="/{{ $carrentalLocation->infoCarrentalLocation->seo->slug_full ?? null }}" title="{{ $carrentalLocation->infoCarrentalLocation->name ?? $carrentalLocation->infoCarrentalLocation->seo->title ?? null }}" class="serviceRelatedSidebarBox_box_item">
                        <i class="fa-solid fa-car-side"></i><h3>{{ $carrentalLocation->infoCarrentalLocation->name ?? $carrentalLocation->infoCarrentalLocation->seo->title ?? null }}</h3>
                     </a>
                  @endforeach
               @endif
            @endforeach

            <!-- cẩm nang du lịch -->
            @foreach($item->serviceLocation->tourLocations as $tourLocation)
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
            </a> --}}
            
         </div>
      </div>
   @endif

   <div id="js_buildTocContentSidebar_idWrite" class="tocContentTour customScrollBar-y" style="{{ $flagMargin }}">
      <!-- loadTocContent ajax -->
   </div>

</div>
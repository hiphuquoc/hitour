<div class="stickyBox">
   <div class="callBookTour">
      @include('main.template.callbook', ['button' => 'Đặt vé tàu', 'flagButton' => true, 'linkFull' => route('main.shipBooking.form')])
   </div>

   @php
       $flagMargin = null;
   @endphp
   @if(!empty($item->tourLocations)&&$item->tourLocations->isNotEmpty())
      @php
         $flagMargin = 'margin-top:1.25rem;';
      @endphp
      <div class="serviceRelatedSidebarBox" style="margin-top:1.25rem;">
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

            <!-- combo -->
            @foreach($item->tourLocations as $tourLocation)
               @if($tourLocation->infoTourLocation->comboLocations->isNotEmpty())
                  @foreach($tourLocation->infoTourLocation->comboLocations as $comboLocation)
                     <a href="/{{ $comboLocation->infoComboLocation->seo->slug_full ?? null }}" title="{{ $comboLocation->infoComboLocation->name ?? $comboLocation->infoComboLocation->seo->title ?? null }}" class="serviceRelatedSidebarBox_box_item">
                        <i class="fa-solid fa-gift"></i><h3>{{ $comboLocation->infoComboLocation->name ?? $comboLocation->infoComboLocation->seo->title ?? null }}</h3>
                     </a>
                  @endforeach
               @endif
            @endforeach

            <!-- khách sạn -->
            @foreach($item->tourLocations as $tourLocation)
               @if($tourLocation->infoTourLocation->hotelLocations->isNotEmpty())
                  @foreach($tourLocation->infoTourLocation->hotelLocations as $hotelLocation)
                     <a href="/{{ $hotelLocation->infoHotelLocation->seo->slug_full ?? null }}" title="{{ $hotelLocation->infoHotelLocation->name ?? $hotelLocation->infoHotelLocation->seo->title ?? null }}" class="serviceRelatedSidebarBox_box_item">
                        <i class="fa-solid fa-bed"></i><h3>{{ $hotelLocation->infoHotelLocation->name ?? $hotelLocation->infoHotelLocation->seo->title ?? null }}</h3>
                     </a>
                  @endforeach
               @endif
            @endforeach

            <!-- vé máy bay -->
            @foreach($item->tourLocations as $tourLocation)
               @if($tourLocation->infoTourLocation->airLocations->isNotEmpty())
                  @foreach($tourLocation->infoTourLocation->airLocations as $airLocation)
                     <a href="/{{ $airLocation->infoAirLocation->seo->slug_full ?? null }}" title="{{ $airLocation->infoAirLocation->name ?? $airLocation->infoAirLocation->seo->title ?? null }}" class="serviceRelatedSidebarBox_box_item">
                        <i class="fa-solid fa-paper-plane"></i><h3>{{ $airLocation->infoAirLocation->name ?? $airLocation->infoAirLocation->seo->title ?? null }}</h3>
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
            </a> --}}
            
         </div>
      </div>
   @endif

   <div id="js_buildTocContentSidebar_idWrite" class="tocContentTour customScrollBar-y" style="{{ $flagMargin }}">
      <!-- loadTocContent ajax -->
   </div>

</div>
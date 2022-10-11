<div class="js_scrollFixed">

   <div class="serviceRelatedSidebarBox">
      <div class="serviceRelatedSidebarBox_title">
         <h2>Chuyên mục liên quan</h2>
      </div>
      <div class="serviceRelatedSidebarBox_box">
         <!-- tour du lịch -->
         @if(!empty($item->tourLocations)&&$item->tourLocations->isNotEmpty())
            @foreach($item->tourLocations as $tourLocation)
               <a href="/{{ $tourLocation->infoTourLocation->seo->slug_full }}" class="serviceRelatedSidebarBox_box_item">
                  <i class="fa-solid fa-person-hiking"></i><h3>{{ $tourLocation->infoTourLocation->name }}</h3>
               </a>
            @endforeach
         @endif
         <!-- tàu cao tốc -->
         @if(!empty($item->tourLocations)&&$item->tourLocations->isNotEmpty())
            @foreach($item->tourLocations as $tourLocation)
               @if($tourLocation->infoTourLocation->shipLocations->isNotEmpty())
                  @foreach($tourLocation->infoTourLocation->shipLocations as $shipLocation)
                     <a href="/{{ $shipLocation->infoShipLocation->seo->slug_full }}" class="serviceRelatedSidebarBox_box_item">
                        <i class="fa-solid fa-ship"></i><h3>{{ $shipLocation->infoShipLocation->name }}</h3>
                     </a>
                  @endforeach
               @endif
            @endforeach
         @endif
         <!-- vé vui chơi -->
         @if(!empty($item->tourLocations)&&$item->tourLocations->isNotEmpty())
            @foreach($item->tourLocations as $tourLocation)
               @if($tourLocation->infoTourLocation->serviceLocations->isNotEmpty())
                  @foreach($tourLocation->infoTourLocation->serviceLocations as $serviceLocation)
                     <a href="/{{ $serviceLocation->infoServiceLocation->seo->slug_full }}" class="serviceRelatedSidebarBox_box_item">
                        <i class="fa-solid fa-star"></i><h3>{{ $serviceLocation->infoServiceLocation->name }}</h3>
                     </a>
                  @endforeach
               @endif
            @endforeach
         @endif
         <!-- cẩm nang du lịch -->
         @if(!empty($item->tourLocations)&&$item->tourLocations->isNotEmpty())
            @foreach($item->tourLocations as $tourLocation)
               @if($tourLocation->infoTourLocation->guides->isNotEmpty())
                  @foreach($tourLocation->infoTourLocation->guides as $guide)
                     <a href="/{{ $guide->infoGuide->seo->slug_full }}" class="serviceRelatedSidebarBox_box_item">
                        <i class="fa-solid fa-book"></i><h3>{{ $guide->infoGuide->name }}</h3>
                     </a>
                  @endforeach
               @endif
            @endforeach
         @endif
         {{-- <!-- cho thuê xe -->
         @if($item->serviceLocation->tourLocations->isNotEmpty())
            @foreach($item->serviceLocation->tourLocations as $tourLocation)
               @if($tourLocation->infoTourLocation->carrentalLocations->isNotEmpty())
                  @foreach($tourLocation->infoTourLocation->carrentalLocations as $carrentalLocation)
                     <a href="/{{ $carrentalLocation->infoCarrentalLocation->seo->slug_full }}" class="serviceRelatedSidebarBox_box_item">
                        <i class="fa-solid fa-car-side"></i><h3>{{ $carrentalLocation->infoCarrentalLocation->name }}</h3>
                     </a>
                  @endforeach
               @endif
            @endforeach
         @endif --}}
         {{-- <a href="#" class="serviceRelatedSidebarBox_box_item">
            <i class="fa-solid fa-building"></i><h3>Khách sạn Phú Quốc</h3>
         </a>
         <a href="#" class="serviceRelatedSidebarBox_box_item">
            <i class="fa-solid fa-plane-departure"></i><h3>Vé máy bay</h3>
         </a> --}}
      </div>
   </div>

   <div id="js_autoLoadTocContentWithIcon_idWrite" class="tocContentTour customScrollBar-y" style="margin-top:1.5rem;">
      <!-- loadTocContent ajax -->
   </div>

</div>
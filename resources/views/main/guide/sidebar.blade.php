<div class="js_scrollFixed">

   <div id="js_autoLoadTocContentWithIcon_idWrite" class="tocContentTour customScrollBar-y">
      <!-- loadTocContent ajax -->
   </div>
   
   @if(!empty($item->tourLocations)&&$item->tourLocations->isNotEmpty())
   <div class="serviceRelatedSidebarBox" style="margin-top:1.5rem;">
      <div class="serviceRelatedSidebarBox_title">
         <h2>Chuyên mục liên quan</h2>
      </div>
      <div class="serviceRelatedSidebarBox_box">
            <!-- tour du lịch -->
            @foreach($item->tourLocations as $tourLocation)
               <a href="/{{ $tourLocation->infoTourLocation->seo->slug_full }}" class="serviceRelatedSidebarBox_box_item">
                  <i class="fa-solid fa-person-hiking"></i><h3>{{ $tourLocation->infoTourLocation->name }}</h3>
               </a>
            @endforeach

            <!-- vé máy bay -->
            @foreach($item->tourLocations as $tourLocation)
               @if($tourLocation->infoTourLocation->airLocations->isNotEmpty())
                  @foreach($tourLocation->infoTourLocation->airLocations as $airLocation)
                     <a href="/{{ $airLocation->infoAirLocation->seo->slug_full }}" class="serviceRelatedSidebarBox_box_item">
                        <i class="fa-solid fa-paper-plane"></i><h3>{{ $airLocation->infoAirLocation->name }}</h3>
                     </a>
                  @endforeach
               @endif
            @endforeach

            <!-- dịch vụ tàu -->
            @foreach($item->tourLocations as $tourLocation)
               @foreach($tourLocation->infoTourLocation->shipLocations as $shipLocation)
                  <a href="/{{ $shipLocation->infoShipLocation->seo->slug_full }}" class="serviceRelatedSidebarBox_box_item">
                     <i class="fa-solid fa-ship"></i><h3>{{ $shipLocation->infoShipLocation->name }}</h3>
                  </a>
               @endforeach
            @endforeach

            <!-- dịch vụ hoạt động vui chơi giải trí -->
            @foreach($item->tourLocations as $tourLocation)
               @foreach($tourLocation->infoTourLocation->serviceLocations as $serviceLocation)
                  <a href="/{{ $serviceLocation->infoServiceLocation->seo->slug_full }}" class="serviceRelatedSidebarBox_box_item">
                     <i class="fa-solid fa-star"></i><h3>{{ $serviceLocation->infoServiceLocation->name }}</h3>
                  </a>
               @endforeach
            @endforeach

            <!-- cho thuê xe -->
            @foreach($item->tourLocations as $tourLocation)
               @foreach($tourLocation->infoTourLocation->carrentalLocations as $carrentalLocation)
                  <a href="/{{ $carrentalLocation->infoCarrentalLocation->seo->slug_full }}" class="serviceRelatedSidebarBox_box_item">
                     <i class="fa-solid fa-car-side"></i><h3>{{ $carrentalLocation->infoCarrentalLocation->name }}</h3>
                  </a>
               @endforeach
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
   
</div>
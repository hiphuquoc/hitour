<div class="js_scrollFixed">
   <div class="serviceRelatedSidebarBox">
      <div class="serviceRelatedSidebarBox_title">
         <h2>Dịch vụ Phú Quốc liên quan</h2>
      </div>
      <div class="serviceRelatedSidebarBox_box">
         @if($item->tourLocations->isNotEmpty())
            @foreach($item->tourLocations as $tourLocation)
               <a href="/{{ $tourLocation->infoTourLocation->seo->slug_full }}" class="serviceRelatedSidebarBox_box_item">
                  <i class="fa-solid fa-person-hiking"></i><h3>{{ $tourLocation->infoTourLocation->name }}</h3>
               </a>
            @endforeach
         @endif
         @if($item->tourLocations)
            @foreach($item->tourLocations as $tourLocation)
               <!-- dịch vụ tàu -->
               @foreach($tourLocation->infoTourLocation->shipLocations as $shipLocation)
                  <a href="/{{ $shipLocation->infoShipLocation->seo->slug_full }}" class="serviceRelatedSidebarBox_box_item">
                     <i class="fa-solid fa-ship"></i><h3>{{ $shipLocation->infoShipLocation->name }}</h3>
                  </a>
               @endforeach
               <!-- dịch vụ hoạt động vui chơi giải trí -->
               @foreach($tourLocation->infoTourLocation->services as $service)
                  <a href="/{{ $service->seo->slug_full }}" class="serviceRelatedSidebarBox_box_item">
                     <i class="fa-solid fa-star"></i><h3>{{ $service->name }}</h3>
                  </a>
               @endforeach
            @endforeach
         @endif
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
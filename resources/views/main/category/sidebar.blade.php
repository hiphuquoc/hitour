<div class="js_scrollFixed">

   @php
       $flagMargin  = null;
   @endphp
   @if(!empty($item->tourLocations)&&$item->tourLocations->isNotEmpty())
   @php
       $flagMargin  = 'margin-top:1.5rem;';
   @endphp
   <div class="serviceRelatedSidebarBox">
      <div class="serviceRelatedSidebarBox_title">
         <h2>Có thể bạn cần?</h2>
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

         <!-- tàu cao tốc -->
         @foreach($item->tourLocations as $tourLocation)
            @if($tourLocation->infoTourLocation->shipLocations->isNotEmpty())
               @foreach($tourLocation->infoTourLocation->shipLocations as $shipLocation)
                  <a href="/{{ $shipLocation->infoShipLocation->seo->slug_full }}" class="serviceRelatedSidebarBox_box_item">
                     <i class="fa-solid fa-ship"></i><h3>{{ $shipLocation->infoShipLocation->name }}</h3>
                  </a>
               @endforeach
            @endif
         @endforeach

         <!-- vé vui chơi -->
         @foreach($item->tourLocations as $tourLocation)
            @if($tourLocation->infoTourLocation->serviceLocations->isNotEmpty())
               @foreach($tourLocation->infoTourLocation->serviceLocations as $serviceLocation)
                  <a href="/{{ $serviceLocation->infoServiceLocation->seo->slug_full }}" class="serviceRelatedSidebarBox_box_item">
                     <i class="fa-solid fa-star"></i><h3>{{ $serviceLocation->infoServiceLocation->name }}</h3>
                  </a>
               @endforeach
            @endif
         @endforeach

         <!-- cẩm nang du lịch -->
         @foreach($item->tourLocations as $tourLocation)
            @if($tourLocation->infoTourLocation->guides->isNotEmpty())
               @foreach($tourLocation->infoTourLocation->guides as $guide)
                  <a href="/{{ $guide->infoGuide->seo->slug_full }}" class="serviceRelatedSidebarBox_box_item">
                     <i class="fa-solid fa-book"></i><h3>{{ $guide->infoGuide->name }}</h3>
                  </a>
               @endforeach
            @endif
         @endforeach

         <!-- cẩm nang du lịch -->
         @foreach($item->tourLocations as $tourLocation)
            @if($tourLocation->infoTourLocation->carrentalLocations->isNotEmpty())
               @foreach($tourLocation->infoTourLocation->carrentalLocations as $carrentalLocation)
                  <a href="/{{ $carrentalLocation->infoCarrentalLocation->seo->slug_full }}" class="serviceRelatedSidebarBox_box_item">
                     <i class="fa-solid fa-car-side"></i><h3>{{ $carrentalLocation->infoCarrentalLocation->name }}</h3>
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

   @if(!empty($listCategoryLv1)&&$listCategoryLv1->isNotEmpty())
      <div class="serviceRelatedSidebarBox" style="{{ $flagMargin }}">
         <div class="serviceRelatedSidebarBox_title">
            <h2>Chuyên mục</h2>
         </div>
         <div class="serviceRelatedSidebarBox_box customScrollBar-y" style="max-height:250px;">
            
            @foreach($listCategoryLv1 as $category)
               <a href="/{{ $category->seo->slug_full ?? null }}" class="serviceRelatedSidebarBox_box_item">
                  <i class="fa-solid fa-arrow-right"></i><h3>{{ $category->name ?? $category->seo->title ?? $category->seo->seo_title ?? null }}</h3>
               </a>
            @endforeach

         </div>
      </div>
   @endif
</div>
@if(!empty($breadcrumb))
   <!-- === START:: Breadcrumb === -->
   <div class="breadcrumbBox">
      <div class="container maxLine_1">
         <a href="/" class="breadcrumbBox_home">Trang chủ</a>
         @for($i=0;$i<count($breadcrumb);++$i)
            @if($i!=(count($breadcrumb)-1))
               <a href="{{ url($breadcrumb[$i]->slug_full) }}">{{ $breadcrumb[$i]->title }}</a>
            @else
               <span>{{ $breadcrumb[$i]->title }}</span>
            @endif
         @endfor
      </div>
   </div>
   <!-- === END:: Breadcrumb === -->
@endif
@if(!empty($breadcrumb))
   <!-- === START:: Breadcrumb === -->
   <div class="breadcrumbBox">
      <div class="container maxLine_1">
         <a href="/" title="Trang chủ" class="breadcrumbBox_home">Trang chủ</a>
         @for($i=0;$i<count($breadcrumb);++$i)
            @if($i!=(count($breadcrumb)-1))
               <a href="/{{ $breadcrumb[$i]->slug_full ?? null }}" title="{{ $breadcrumb[$i]->title }}">{{ $breadcrumb[$i]->title ?? null }}</a>
            @else
               <span>{{ $breadcrumb[$i]->title }}</span>
            @endif
         @endfor
      </div>
   </div>
   <!-- === END:: Breadcrumb === -->
@endif
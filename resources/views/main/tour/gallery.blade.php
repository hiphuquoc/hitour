@if(!empty($item))
<div class="galleryBox">
    <div class="galleryBox_image">
        @if(!empty($item->seo->image))
            <img src="{{ config('main.svg.loading_main') }}" data-src="{{ $item->seo->image ?? config('admin.images.default_750x460') }}" alt="{{ $item->name ?? $item->seo->title ?? $item->seo->seo_title ?? null }}" title="{{ $item->name ?? $item->seo->title ?? $item->seo->seo_title ?? null }}" />
        @endif
        @if(!empty($item->files))
            @foreach($item->files as $file)
                @if($file->file_type==='gallery')
                    <img src="{{ config('main.svg.loading_main') }}" data-src="{{ $file->file_path ?? config('admin.images.default_750x460') }}" alt="{{ $item->name ?? $item->seo->title ?? $item->seo->seo_title ?? null }}" title="{{ $item->name ?? $item->seo->title ?? $item->seo->seo_title ?? null }}" />
                @endif
            @endforeach
        @endif
    </div>
    <div class="galleryBox_button notPrint">
        <div class="galleryBox_button_item" onClick="printContent('elementPrint')">
            <i class="fa-solid fa-print"></i>
        </div>
        {{-- <div class="galleryBox_button_item">
            <i class="fa-solid fa-envelope"></i>
        </div> --}}
    </div>
</div>
@endif

@push('scripts-custom')
    <script type="text/javascript">
        $('.galleryBox_image').slick({
            infinite: false,
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            prevArrow: '<button class="slick-arrow slick-prev" aria-label="Slide trước"><i class="fa-solid fa-angle-left"></i></button>',
                nextArrow: '<button class="slick-arrow slick-next" aria-label="Slide tiếp theo"><i class="fa-solid fa-angle-right"></i></button>',
            dots: false,
            autoplaySpeed: 1000,
            // responsive: [
            //     {
            //         breakpoint: 991,
            //         settings: {
            //             infinite: false,
            //             slidesToShow: 3.2,
            //             slidesToScroll: 1,
            //             arrows: false,
            //         }
            //     }
            // ]
        });
    </script>
@endpush
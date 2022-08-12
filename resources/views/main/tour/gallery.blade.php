@if(!empty($item))
<div class="galleryBox">
    <div class="galleryBox_image">
        @if(!empty($item->files))
            @foreach($item->files as $file)
                @if($file->file_type==='gallery')
                    <img src="{{ $file->file_path }}" alt="" title="" />
                @endif
            @endforeach
        @endif
    </div>
    <div class="galleryBox_button">
        <div class="galleryBox_button_item">
            <i class="fa-solid fa-print"></i>
        </div>
        <div class="galleryBox_button_item">
            <i class="fa-solid fa-envelope"></i>
        </div>
    </div>
</div>
@endif

@push('scripts-custom')
    <script type="text/javascript">
        $('.galleryBox_image').slick({
            infinite: false,
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
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
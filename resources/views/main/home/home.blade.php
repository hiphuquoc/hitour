@extends('main.layouts.main')
@section('content')

    <!-- START: Home slider -->
    <div class="sliderHome">
        @for($i=0;$i<3;++$i)
            <div class="sliderHome_item">
                <img src="/images/main/du-lich-bien-dao-hitour-1.webp" alt="du lịch biển đảo Hitour" title="du lịch biển đảo Hitour" />
            </div>
        @endfor
    </div>
    <!-- END: Home slider -->

    <div class="pageHome">
        <div class="container">
            Nội dung test
        </div>

        @include('main.home.blogListNoImage')
    </div>

    
    
@endsection
@push('scripts-custom')
    <script type="text/javascript">
        $(window).on('load', function () {
            setupSlick();
            $(window).resize(function(){
                setupSlick();
            })

            $('.sliderHome').slick({
                dots: true,
                arrows: true,
                autoplay: true,
                infinite: true,
                autoplaySpeed: 5000,
                lazyLoad: 'ondemand',
                responsive: [
                    {
                        breakpoint: 567,
                        settings: {
                            arrows: false,
                        }
                    }
                ]
            });

            function setupSlick(){
                setTimeout(function(){
                    $('.sliderHome .slick-prev').html('<i class="fa-solid fa-arrow-left-long"></i>');
                    $('.sliderHome .slick-next').html('<i class="fa-solid fa-arrow-right-long"></i>');
                    $('.sliderHome .slick-dots button').html('');
                }, 0);
            }
        });
    </script>
@endpush
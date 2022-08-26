@extends('main.layouts.main')
@section('content')

    @include('main.snippets.breadcrumb')

    <div class="pageContent">
        <div class="container">
            <!-- title -->
            <h1 class="titlePage">Du lịch Côn Đảo - Tour du lịch Côn Đảo</h1>
            <!-- rating -->
            <div class="ratingBox">
                <div class="ratingBox_star">
                    <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
                    <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
                    <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
                    <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
                    <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
                </div>
                <div class="ratingBox_text maxLine_1" style="margin-left:2px;font-size:14px;">
                        4.5 sao / <a href="/sp/dau-rua-mat-3s#product-reviews">213 đánh giá từ khách du lịch</a>
                </div>
            </div>
            <!-- content box -->
            <div class="contentBox">
                <p>
                    Du lịch Hà Nội - Kinh đô Thăng Long một thời, là vùng đất được nhiều Triều đại phong kiến chọn làm kinh đô, lập ấp đắp đê trị vì đất nước. Hà Nội ngày nay nổi tiếng với 36 phố phường cùng nhiều danh lam thắng cảnh và nhiều di tích lịch sử lâu đời trở thành điểm đến của nhiều tour du lịch trong nước được du khách lựa chọn. Đến với tour du lịch Hà Nội, du khách được tìm hiểu quá trình hình thành ngàn năm Văn Hiến, được du ngoạn quanh 36 phố phường Hà Nội cổ kính. Hứa hẹn đây sẽ là một chuyến du lịch thú vị cho quý khách trong kỳ nghỉ của mình. Để tìm được cho mình một tour Hà Nội phù hợp nhất, du khách vui lòng đăng ký tour trực tuyến tại Website của Du Lịch Việt để chọn được tour với giá tốt nhất nhé!
                </p>
            </div>
            <!-- tour box -->
            @include('main.tourLocation.tourGrid')
        </div>
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
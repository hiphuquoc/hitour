<?php

return [
    'name'              => 'Hitour',
    'webname'           => 'Hitour.vn',
    'description'       => 'Trang Tour du lịch trực tuyến hàng đầu Việt Nam ®Hitour',
    /* Thông báo validate form */
    'message_validate'  => [
        'not_empty'     => 'Không để trống trường này!',
    ],
    'unit_currency'     => 'đ',
    'logo_square'       => 'https://hitour.vn/storage/images/upload/logo-share-type-manager-upload.png',
    'icon-arrow-email'  => 'https://hitour.vn/images/main/icon-arrow-email.png',
    'avatar_home'       => 'https://hitour.vn/storage/images/upload/banner-hitour-1-type-manager-upload.webp',
    'svg'               => [
        'loading_main'      => '/storage/images/svg/loading_plane_bge9ecef.svg',
        'loading_main_nobg' => '/storage/images/svg/loading_plane_transparent.svg'
    ],
    'title_list_service_sidebar'        => 'Có thể bạn cần?',
    /* Background hỗ trợ loading */
    'background_slider_home'            => '/images/main/background-slider-home.jpg',
    'cache'     => [
        'folderSave'    => 'public/caches/',
        'extension'     => 'html',
    ],
    'rating_rule'       => [
        [
            'text'  => 'Rất tuyệt',
            'score' => '9'
        ],
        [
            'text'  => 'Tuyệt vời',
            'score' => '8'
        ],
        [
            'text'  => 'Rất tốt',
            'score' => '7'
        ],
        [
            'text'  => 'Tốt',
            'score' => '6'
        ],
        [
            'text'  => 'Tạm được',
            'score' => '5'
        ],
        [
            'text'  => 'Hơi tệ',
            'score' => '3'
        ],
        [
            'text'  => 'Rất tệ',
            'score' => '0'
        ]
    ],
    'hotel_type'    => [
        'Khách sạn', 'Khu nghỉ dưỡng', 'Homestay', 'Nhà nghỉ', 'Căn hộ', 'Nhà khách gia đình', 'Biệt thự', 'Nhà riêng','Khác'
    ],
    'hotel_time_receive' => [
        'Tôi chưa biết', '14h00 - 15h00', '15h00 - 16h00', '16h00 - 17h00', '17h00 - 18h00', '18h00 - 19h00', '20h00 - 21h00', '21h00 - 22h00', '22h00 - 23h00', '23h00 - 00h00', '00h00 - 01h00 (hôm sau)', '01h00 - 02h00 (hôm sau)'
    ]
];
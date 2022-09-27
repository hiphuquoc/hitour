<?php
return [
    'header-main-menu'  => [
        [
            'name'  => 'Nghị luận xã hội',
            'icon'  => '<i class="fa-solid fa-feather"></i>',
            'url'   => 'nghi-luan-xa-hoi'
        ],
        [
            'name'  => 'Nghị luận văn học',
            'icon'  => '<i class="fa-solid fa-feather"></i>',
            'url'   => 'nghi-luan-van-hoc',
            'child' => [
                [
                    'name'  => 'Văn Trung học Cơ Sở',
                    'icon'  => '',
                    'url'   => 'nghi-luan-van-hoc/van-trung-hoc-co-so',
                    'child' => [
                        [
                            'name'  => 'Lớp 6',
                            'icon'  => '',
                            'url'   => 'nghi-luan-van-hoc/van-trung-hoc-co-so/ngu-van-lop-6'
                        ],
                        [
                            'name'  => 'Lớp 7',
                            'icon'  => '',
                            'url'   => 'nghi-luan-van-hoc/van-trung-hoc-co-so/ngu-van-lop-7'
                        ],
                        [
                            'name'  => 'Lớp 8',
                            'icon'  => '',
                            'url'   => 'nghi-luan-van-hoc/van-trung-hoc-co-so/ngu-van-lop-8'
                        ],
                        [
                            'name'  => 'Lớp 9',
                            'icon'  => '',
                            'url'   => 'nghi-luan-van-hoc/van-trung-hoc-co-so/ngu-van-lop-9'
                        ]
                    ]
                ],
                [
                    'name'  => 'Văn Trung học Phổ Thông',
                    'icon'  => '',
                    'url'   => 'nghi-luan-van-hoc/van-trung-hoc-pho-thong',
                    'child' => [
                        [
                            'name'  => 'Lớp 10',
                            'icon'  => '',
                            'url'   => 'nghi-luan-van-hoc/van-trung-hoc-pho-thong/ngu-van-lop-10'
                        ],
                        [
                            'name'  => 'Lớp 11',
                            'icon'  => '',
                            'url'   => 'nghi-luan-van-hoc/van-trung-hoc-pho-thong/ngu-van-lop-11'
                        ],
                        [
                            'name'  => 'Lớp 12',
                            'icon'  => '',
                            'url'   => 'nghi-luan-van-hoc/van-trung-hoc-pho-thong/ngu-van-lop-12'
                        ]
                    ]
                ],
            ]
        ],
        [
            'name'  => 'Đọc - hiểu',
            'icon'  => '<i class="fa-solid fa-book-open"></i>',
            'url'   => 'doc-hieu-van-hoc'
        ],
        [
            'name'  => 'Tài liệu',
            'icon'  => '<i class="fa-regular fa-award"></i>',
            'url'   => 'tai-lieu-van-hoc',
            'child' => [
                [
                    'name'  => 'Lý Luận Văn Học',
                    'icon'  => '',
                    'url'   => 'tai-lieu-van-hoc/tai-lieu-ly-luan-van-hoc'
                ],
                [
                    'name'  => 'Tác giả',
                    'icon'  => '',
                    'url'   => 'tai-lieu-van-hoc/tai-lieu-tac-gia'
                ],
                [
                    'name'  => 'Tác phẩm',
                    'icon'  => '',
                    'url'   => 'tai-lieu-van-hoc/tai-lieu-tac-pham'
                ]
            ]
        ],
        [
            'name'  => 'Đề thi',
            'icon'  => '<i class="fa-solid fa-clipboard-check"></i>',
            'url'   => 'de-thi-van-hoc',
            'child' => [
                [
                    'name'  => 'Lớp 6',
                    'icon'  => '',
                    'url'   => 'de-thi-van-hoc/de-thi-ngu-van-lop-6'
                ],
                [
                    'name'  => 'Lớp 7',
                    'icon'  => '',
                    'url'   => 'de-thi-van-hoc/de-thi-ngu-van-lop-7'
                ],
                [
                    'name'  => 'Lớp 8',
                    'icon'  => '',
                    'url'   => 'de-thi-van-hoc/de-thi-ngu-van-lop-8'
                ],
                [
                    'name'  => 'Lớp 9',
                    'icon'  => '',
                    'url'   => 'de-thi-van-hoc/de-thi-ngu-van-lop-9'
                ],
                [
                    'name'  => 'Lớp 10',
                    'icon'  => '',
                    'url'   => 'de-thi-van-hoc/de-thi-ngu-van-lop-10'
                ],
                [
                    'name'  => 'Lớp 11',
                    'icon'  => '',
                    'url'   => 'de-thi-van-hoc/de-thi-ngu-van-lop-11'
                ],
                [
                    'name'  => 'Lớp 12',
                    'icon'  => '',
                    'url'   => 'de-thi-van-hoc/de-thi-ngu-van-lop-12'
                ]
            ]
        ],
    ],
    'left-menu-admin'   => [
        [
            'name'      => 'Quản lí Tour',
            'route'     => '',
            'icon'      => '<i data-feather=\'briefcase\'></i>',
            'child'     => [
                [
                    'name'  => '1. Điểm khởi hành',
                    'route' => 'admin.tourDeparture.list',
                    'icon'  => '<i data-feather=\'circle\'></i>'
                ],
                [
                    'name'  => '2. Điểm đến',
                    'route' => 'admin.tourLocation.list',
                    'icon'  => '<i data-feather=\'circle\'></i>'
                ],
                [
                    'name'  => '3. Đối tác Tour',
                    'route' => 'admin.tourPartner.list',
                    'icon'  => '<i data-feather=\'circle\'></i>'
                ],
                [
                    'name'  => '4. Chi tiết Tour',
                    'route' => 'admin.tour.list',
                    'icon'  => '<i data-feather=\'circle\'></i>'
                ],
                [
                    'name'  => '5. Booking Tour',
                    'route' => 'admin.tourBooking.list',
                    'icon'  => '<i data-feather=\'circle\'></i>'
                ]
            ]
        ],
        [
            'name'      => 'Quản lí Tàu',
            'route'     => '',
            'icon'      => '<i data-feather=\'anchor\'></i>',
            'child'     => [
                [
                    'name'  => '1. Bến cảng',
                    'route' => 'admin.shipPort.list',
                    'icon'  => '<i data-feather=\'circle\'></i>'
                ],
                
                [
                    'name'  => '2. Điểm khởi hành',
                    'route' => 'admin.shipDeparture.list',
                    'icon'  => '<i data-feather=\'circle\'></i>'
                ],
                [
                    'name'  => '3. Điểm đến',
                    'route' => 'admin.shipLocation.list',
                    'icon'  => '<i data-feather=\'circle\'></i>'
                ],
                [
                    'name'  => '4. Đối tác Tàu',
                    'route' => 'admin.shipPartner.list',
                    'icon'  => '<i data-feather=\'circle\'></i>'
                ],
                [
                    'name'  => '5. Chi tiết Tàu',
                    'route' => 'admin.ship.list',
                    'icon'  => '<i data-feather=\'circle\'></i>'
                ],
                [
                    'name'  => '6. Booking Tàu',
                    'route' => 'admin.shipBooking.list',
                    'icon'  => '<i data-feather=\'circle\'></i>'
                ]
            ]
        ],
        [
            'name'      => 'Cảm nang du lịch',
            'route'     => 'admin.guide.list',
            'icon'      => '<i data-feather=\'book-open\'></i>',    
        ],
        [
            'name'  => 'Nhân viên tư vấn',
            'route' => 'admin.staff.list',
            'icon'  => '<i data-feather=\'user\'></i>'
        ],
        [
            'name'      => 'Quản lí Ảnh',
            'route'     => 'admin.image.list',
            'icon'      => '<i data-feather=\'image\'></i>'
        ],
    ]
];
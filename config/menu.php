<?php
return [
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
            'name'      => 'Quản lí Bay',
            'route'     => '',
            'icon'      => '<i class="fa-solid fa-paper-plane"></i>',
            'child'     => [
                [
                    'name'  => '1. Sân bay',
                    'route' => 'admin.airPort.list',
                    'icon'  => '<i data-feather=\'circle\'></i>'
                ],
                [
                    'name'  => '2. Điểm khởi hành',
                    'route' => 'admin.airDeparture.list',
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
            'name'      => 'Vé vui chơi',
            'route'     => '',
            'icon'      => '<i data-feather=\'star\'></i>',
            'child'     => [
                [
                    'name'  => '1. Điểm đến',
                    'route' => 'admin.serviceLocation.list',
                    'icon'  => '<i data-feather=\'circle\'></i>'
                ],
                
                [
                    'name'  => '2. Chi tiết dịch vụ',
                    'route'     => 'admin.service.list',
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
            'name'      => 'Cho thuê xe',
            'route'     => 'admin.carrentalLocation.list',
            'icon'      => '<i class="fa-solid fa-car-side"></i>',    
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
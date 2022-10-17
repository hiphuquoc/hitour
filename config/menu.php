<?php
return [
    'left-menu-admin'   => [
        [
            'name'      => 'Tour du lịch',
            'route'     => '',
            'icon'      => '<i class="fa-solid fa-suitcase-rolling"></i>',
            'child'     => [
                [
                    'name'  => '1. Trong nước',
                    'route' => '',
                    'icon'  => '<i data-feather=\'circle\'></i>',
                    'child' => [
                        [
                            'name'  => '1.1. Điểm đến',
                            'route' => 'admin.tourLocation.list',
                            'icon'  => '<i data-feather=\'circle\'></i>'
                        ],
                        [
                            'name'  => '1.2. Chi tiết Tour',
                            'route' => 'admin.tour.list',
                            'icon'  => '<i data-feather=\'circle\'></i>'
                        ]
                    ]
                ],
                [
                    'name'  => '2. Nước ngoài',
                    'route' => '',
                    'icon'  => '<i data-feather=\'circle\'></i>',
                    'child' => [
                        [
                            'name'  => '2.1. Châu lục',
                            'route' => 'admin.tourContinent.list',
                            'icon'  => '<i data-feather=\'circle\'></i>'
                        ],
                        [
                            'name'  => '2.2. Quốc gia',
                            'route' => 'admin.tourCountry.list',
                            'icon'  => '<i data-feather=\'circle\'></i>'
                        ],
                        [
                            'name'  => '2.3. Chi tiết Tour',
                            'route' => 'admin.tourInfoForeign.list',
                            'icon'  => '<i data-feather=\'circle\'></i>'
                        ],
                    ]
                ],
                [
                    'name'  => '3. Điểm khởi hành',
                    'route' => 'admin.tourDeparture.list',
                    'icon'  => '<i data-feather=\'circle\'></i>'
                ],
                [
                    'name'  => '4. Đối tác Tour',
                    'route' => 'admin.tourPartner.list',
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
                ],
                [
                    'name'  => '3. Điểm đến',
                    'route' => 'admin.airLocation.list',
                    'icon'  => '<i data-feather=\'circle\'></i>'
                ],
                [
                    'name'  => '4. Đối tác',
                    'route' => 'admin.airPartner.list',
                    'icon'  => '<i data-feather=\'circle\'></i>'
                ],
                [
                    'name'  => '5. Chi tiết bay',
                    'route' => 'admin.air.list',
                    'icon'  => '<i data-feather=\'circle\'></i>'
                ]
            ]
        ],
        [
            'name'      => 'Quản lí Tàu',
            'route'     => '',
            'icon'      => '<i class="fa-solid fa-ship"></i>',
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
                    'name'  => '4. Hãng Tàu',
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
            'icon'      => '<i class="fa-solid fa-ticket"></i>',
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
            'name'      => 'Cẩm nang du lịch',
            'route'     => 'admin.guide.list',
            'icon'      => '<i class="fa-solid fa-book"></i>',    
        ],
        [
            'name'      => 'Cho thuê xe',
            'route'     => 'admin.carrentalLocation.list',
            'icon'      => '<i class="fa-solid fa-car-side"></i>',    
        ],
        [
            'name'  => 'Nhân viên tư vấn',
            'route' => 'admin.staff.list',
            'icon'  => '<i class="fa-solid fa-users"></i>'
        ],
        [
            'name'      => 'Quản lí Ảnh',
            'route'     => 'admin.image.list',
            'icon'      => '<i class="fa-solid fa-image"></i>'
        ],
    ]
];
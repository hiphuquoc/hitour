<?php

return [
    /* Thông báo validate form */
    'massage_validate'  => [
        'not_empty' => 'Không để trống trường này!',
    ],

    /* Định dạng hình ảnh Upload */
    'images'    => [
        'folderUpload'          => 'public/images/upload/',
        'normalResize_width'    => 750,
        'normalResize_height'   => 460,
        'smallResize_width'     => 400,
        'smallResize_height'    => 250,
        'default_750x460'       => '/images/admin/image-default-750x460.png',
        'default_660x660'       => '/images/admin/image-default-660x660.png',
        /* danh sách action: copy_url, change_name, change_image, delete */
        'keyType'               => '-type-',
        'type'                  => [
            'default'           => ['copy_url', 'change_image'],
            'manager-upload'    => ['copy_url', 'change_name', 'change_image', 'delete']
        ],
        'extension'             => 'webp',
        'quality'               => '90'
    ],
    
    /* Vùng miền trong bảng region_info */
    'region'            => [
        [
            'id'            => 1,
            'name'          => 'Miền Bắc',
            'name_other'    => 'Bắc Bộ'
        ],
        [
            'id'            => 2,
            'name'          => 'Miền Trung',
            'name_other'    => 'Trung Bộ'
        ],
        [
            'id'            => 3,
            'name'          => 'Miền Nam',
            'name_other'    => 'Nam Bộ'
        ]
    ],

    'prefix_name'       => [
        'Mr', 'Ms'
    ],

    'prefix_name_customer'  => [
        'Bác', 'Cô', 'Chú', 'Anh', 'Chị', 'Em'
    ],

    'message_data_empty'    => 'Không có dữ liệu phù hợp!',

    'tour_option_apply_day' => [
        [
            'name'          => 'Tất cả các ngày', 
            'apply_day'     => [
                '1', '2', '3', '4', '5', '6', '0' /* Thứ 2 đến Chủ nhật */
            ]
        ],
        [
            'name'          => 'Thứ 2 - Thứ 5',
            'apply_day'     => [
                '1', '2', '3', '4'
            ]
        ],
        [
            'name'          => 'Thứ 6 - Chủ nhật',
            'apply_day'     => [
                '5', '6', '0'
            ]
        ],
        [
            'name'          => 'Thứ 3 5 7',
            'apply_day'     => [
                '2', '4', '6'
            ]
        ],
        [
            'name'          => 'Thứ 2 4 6 Chủ nhật',
            'apply_day'     => [
                '1', '3', '5', '0'
            ]
        ]
    ],

    'type_add_cost_booking'    => [
        '+'     => 'Tăng',
        '-'     => 'Giảm'
    ],

    'storage'   => [
        'contentTour'           => 'public/contents/tours/',
        'contentTourLocation'   => 'public/contents/tourLocations/',
        'contentTourDeparture'  => 'public/contents/tourDepartures/',
        'contentShip'           => 'public/contents/ships/',
        'contentShipLocation'   => 'public/contents/shipLocations/',
        'contentShipDeparture'  => 'public/contents/shipDepartures/',
        'contentShipPartner'    => 'public/contents/shipPartners/',
        'contentGuide'          => 'public/contents/guides/',
        'contentService'        => 'public/contents/services/'
    ],

    'alert'     => [
        'confirmRemove'     => 'Bạn có chắc muốn thực hiện thao tác này!'
    ]
];
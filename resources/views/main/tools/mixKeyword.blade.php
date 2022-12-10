@php

    $keywords   = [
        'no_utf8'   => [
            've <name> <local>',
            've di <name> <local>',
            've vao <name> <local>',
            've vao cong <name> <local>',
            've vui choi <name> <local>',
            've cap treo <name> <local>',
            've du lich <name> <local>',
            've tham quan <name> <local>',
            've cap treo ra <name> <local>',
            've ra dao <name> <local>',
            've <name> <local> sau 16h',
            've tour <name> <local>',
            've <name> <local> tet',
            've tron goi <name> <local>',
            've choi <name> <local>'
        ],
        'utf8'  => [
            'vé <name> <local>',
            'vé đi <name> <local>',
            'vé vào <name> <local>',
            'vé vào cổng <name> <local>',
            'vé vui chơi <name> <local>',
            'vé cáp treo <name> <local>',
            'vé du lịch <name> <local>',
            'vé tham quan <name> <local>',
            'vé cáp treo ra <name> <local>',
            'vé ra đảo <name> <local>',
            'vé <name> <local> sau 16h',
            'vé tour <name> <local>',
            'vé <name> <local> tết',
            'vé trọn gói <name> <local>',
            'vé chơi <name> <local>'
        ],
        'all'   => [
            '<name> <local>'
        ]
    ];

    $actionAddBefore = [
        'no_utf8'   => [
            'gia', 'ban', 'phong ban', 'phong', 'bang gia', 'dia chi ban', 'dia diem ban', 'san', 'cach mua', 'cach dat', 'dat', 'uu dai'
        ],
        'utf8'      => [
            'giá', 'bán', 'phòng bán', 'phòng', 'bảng giá', 'địa chỉ bán', 'địa điểm bán', 'săn', 'cách mua', 'cách đặt', 'đặt', 'ưu đãi'
        ],
        'all'       => [
            'mua', 'voucher', 'combo'
        ]
    ];

    $actionAddAfter = [
        'all'   => [
            '2023'
        ]
    ];


    $run = [
        'type'  => 'utf8',
        'name'  => 'vinpearl',
        'local' => 'nha trang'
    ];

    /* thực thi */
    $type               = $run['type'];
    $local              = $run['local'];
    $name               = $run['name'];
    $result             = [];
    foreach($keywords[$type] as $keyword){
        /* thay thế */
        $realKeyword    = str_replace('<local>', $local, $keyword);
        $realKeyword    = str_replace('<name>', $name, $realKeyword);
        /* đưa từ khóa chính vào mảng */
        $result[]       = $realKeyword;
        /* đưa từ khóa được "thêm trước" vào mảng */
        foreach($actionAddBefore[$type] as $before){
            $result[]   = $before.' '.$realKeyword;
        }
        foreach($actionAddBefore['all'] as $key){
            $result[]   = $key.' '.$realKeyword;
        }
        /* đưa từ khóa được "thêm sau" vào mảng */
        foreach($actionAddAfter['all'] as $after){
            $result[]   = $realKeyword.' '.$after;
        }
    }

    dd($result);

@endphp
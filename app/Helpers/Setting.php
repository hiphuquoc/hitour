<?php

namespace App\Helpers;

class Setting {

    public static function settingView($name, $listItem = [20, 50, 100, 200, 500], $default, $total){
        
        return view('admin.template.settingView', compact('name', 'listItem', 'default', 'total'))->render();
    }

}
<?php
/**
 **********************************************************
 * @author : YiChaobao [ yichaobao@163.com ]
 * @time : 2019/01/01 00:00
 * @copyright : (c) 2019 YiChaobao All rights reserved.
 **********************************************************
 * @name : User.php
 * @description :
 **/
namespace common\helpers;
class User{
    public static function get_sex_text($sex){
        $sex_text = [
            0 => '未知',
            1 => '男',
            2 => '女'
        ];
        return $sex_text[$sex];
    }
}
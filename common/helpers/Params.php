<?php
namespace common\helpers;
/**
 * Created by PhpStorm.
 * User: FOCUS
 * Date: 2018/12/19
 * Time: 18:25
 */
class  Params
{
    /**
     * 检查参数是否完整
     * @param array $standard 自己定义需要检查参数['a','b']
     * @param array $request []
     * @return bool
     */
    public static function checkParams(array $standard, array $request)
    {
        $array = [];
        foreach ($standard as $value) {
            if (empty($request[$value])) {
                $array[]= $value ;
            }
        }
        if (!empty($array)) {
            return $array;
        }
        return true;
    }


    public static function setNull(array $array, array $data)
    {
        foreach ($array as $row) {
            if (empty($data[$row])) {
                $data[$row] = null;
            }
        }
        return $data;
    }
}
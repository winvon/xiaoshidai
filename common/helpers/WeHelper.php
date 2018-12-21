<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/18
 * Time: 14:22
 */

namespace common\helpers;

class WeHelper{
    /**
     * 获得服务
     * @param string $name name
     * @return object
     */
    public static function getService($name)
    {
        return \Yii::$container->get($name.'Service');
    }

    /**
     * 通用返回结构
     * @param null $data 数据
     * @param int $status 状态
     * @return array | string
     */
    public static function comReturn($data=null,$status=0,$errors=null){
        $data=[
            'code' => $status,
            'msg' => ($status !== -1) ? self::getRespStatus($status) : $errors,
            'data' => $data,
        ];
        return $data;
    }

    /**
     * 获取全局状态信息
     * @param int $status
     * @return string
     */
    public static function getRespStatus($status = 0){
        if($status == 0)
        {
            return 'OK!';
        }
        return '失败，错误号：'.$status.' '.ErrorCode::errorInfo($status);
    }
}
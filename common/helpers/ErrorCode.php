<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/18
 * Time: 15:05
 */

namespace common\helpers;


class ErrorCode
{
    /** OK */
    const ERR_SUCCESS = 0;
    /** 表单验证错误 */
    const ERR_MODEL_VALIDATE = -1;
    /** 数据库异常 */
    const ERR_DB = 1000;
    /** 数据关联错误 */
    const ERR_RELATION = 1001;
    /* 更新失败 */
    const ERR_UPDATE_FAILURE = 1002;
    /** 产品分类下没有对应的产品属性 */
    const ERR_GOODS_NO_CATEGORY = 10000;

    public static function errorInfo($errorCode)
    {
        switch ($errorCode) {
            case self::ERR_MODEL_VALIDATE:
                return '表单验证错误';
            case self::ERR_DB:
                return '数据库操作异常';
            case self::ERR_RELATION:
                return '数据关联错误';
            case self::ERR_UPDATE_FAILURE:
                return '更新失败';
            case self::ERR_GOODS_NO_CATEGORY:
                return '产品分类下没有对应的产品属性';

            default:
                return '未知错误';
        }
    }
}
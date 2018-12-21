<?php
/**
 * Created by PhpStorm.
 * User: FOCUS
 * Date: 2018/12/19
 * Time: 18:15
 */

namespace common\helpers;

class BackendErrorCode extends ErrorCode
{
    /** 登陆失败 */
    const ERR_LOGIN_FALSE = 1003;
    /** 缺少参数 */
    const ERR_PARAM_LOSE = 1004;
    /** 删除 */
    const ERR_USER_DELETED = 1005;
    /** 冻结 */
    const ERR_USER_LOCKED = 1006;
    /** 对象为空 */
    const ERR_OBJECT_NON = 1007;
    /** 身份错误 */
    const ERR_IDENTITY = 1008;

    public static function errorInfo($errorCode)
    {
        if (parent::errorInfo($errorCode) !== '未知错误') {
            return parent::errorInfo($errorCode);
        }
        switch ($errorCode) {
            case self::ERR_LOGIN_FALSE:
                return '用户名或者密码错误';

            case self::ERR_PARAM_LOSE:
                return '缺少参数';

            default:
                return '未知错误';
        }
    }

}
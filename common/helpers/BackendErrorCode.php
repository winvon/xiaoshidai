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
    /** 登陆失败 **/
    const ERR_LOGIN_FALSE = 20002;
    /** 参数错误 **/
    const ERR_PARAM_LOSE = -2;
    /**请求失败**/
    const ERR_REQUEST_FAILED = 1;
    /** 删除 **/
    const ERR_USER_DELETED = 1005;
    /** 冻结 **/
    const ERR_USER_LOCKED = 1006;
    /** 对象为空 **/
    const ERR_OBJECT_NON = 1007;
    /** 对象为空 **/
    const ERR_LOCK = 1008;
    /** 身份错误 **/
    const ERR_IDENTITY = 1001;
    /** 身份错误 **/
    const ERR_DELETE = 1009;
    /** 数量错误 **/
    const ERR_NUMBER = 1010;
    /** 表单重复提交 **/
    const ERR_FORM_VALIDATE = 400;
    /**冻结账户 **/
    const ERR_LOCKED_ACCOUNTS = -7;
    /**无效账户 **/
    const ERR_INVALID_ACCOUNTS = -8;
    /** token 无效 **/
    const ERR_TOKEN_OUT_TIME = -9;
    /** 表单验证错误 */
    const ERR_MODEL_VALIDATE = -3;

}
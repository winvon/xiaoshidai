<?php
/**
 * Created by PhpStorm.
 * User: Enson
 * Date: 2018/12/27
 * Time: 15:30
 */

namespace common\helpers;

/**
 * Class CodeHelper
 * 全局Code提示信息
 * @package common\helpers
 */
class CodeHelper
{
    private static $codes;

    public static function getCodeText($code)
    {
        if (is_null(self::$codes)) {
            self::$codes = self::init();
        }
        return self::$codes[$code];
    }

    public static function get_Array_Key_Exists($key)
    {
        return array_key_exists($key, self::init());
    }

    private static function init()
    {
        return [
            /** 全局信息 **/
            '-1' => '系统繁忙，请稍候再试',
            '0' => '请求成功',
            '1' => '请求失败',
            '-2' => '请求参数不正确',
            '-3' => '表单验证错误',
            '-7' => '冻结账号',
            '-8' => '无效账号',
            '-9' => 'Token无效',
            '400' => '表单验证失败',
            '1000' => '数据库异常',
            '1001' => '用户鉴权失败',
            '1002' => '体验时间已过期，请认证',
            '1003' => '表单数据持久化时字段验证不通过',
            '1007' => '数据不存在',

            /** 1-公共信息 **/
            '10001' => '短信验证码已过期',
            '10002' => '短信验证码不正确',
            '10003' => '发送验证码手机号不能为空',
            '10004' => '发送验证码手机号不存在',
            '10005' => '验证码不能为空',

            /** 2-员工信息 **/
            '20001' => '账户或手机号已存在',
            '20002' => '无效的账户或密码',
            '20003' => '无效的用户名',

            /** 3-产品信息 **/
            '3001' => '产品发布失败',
            '3002' => '产品已发布，待审核',
            '3003' => '产品审核不通过',
            '3004' => '产品不存在',
            '3010' => '请上传产品图片',
            '3050' => '产品分集不存在',

            /**优惠券信息**/
            '40001' => '优惠券不存在',
            '40002' => '优惠券发放已超量',
        ];
    }
}
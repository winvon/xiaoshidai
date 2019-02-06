<?php
/**
 * Created by PhpStorm.
 * User: Enson
 * Date: 2018/12/27
 * Time: 12:47
 */

namespace common\helpers;

/**
 * Class ConstantHelper
 * 常用常量的定义和获取
 * @package common\helpers
 */
class ConstantHelper
{
    /******************系统通用属性******************/
    /* 是否冻结（0-未冻结；1-冻结） */
    CONST IS_LOCK_FALSE = 0;
    CONST IS_LOCK_TRUE = 1;

    /* 是否删除（0-未删除；1-删除） */
    CONST IS_DELETE_FALSE = 0;
    CONST IS_DELETE_TRUE = 1;

    /* 是否删除（0显示；1隐藏） */
    CONST IS_SHOW_FALSE = 1;
    CONST IS_SHOW_TRUE = 0;

    /* 支付方式（weixin-微信支付；alipay-支付宝） */
    CONST PAY_WAY_WEIXIN = 'weixin';
    CONST PAY_WAY_ALIPAY = 'alipay';

    /* 分页参数键名 */
    CONST PAGE = 'page';//当前页码
    CONST COUNT = 'count';//数据总量
    CONST PAGE_SIZE = 'page_size';//分页数据条数
    CONST PAGE_COUNT = 'page_count';//当前页数量
    CONST LISTS = 'list';//数据列表

    /***********************************************/
    /* 产品类别（1-视频；2-文档；3-音频） */
    CONST GOODS_TYPE_VIDEO = 1;
    CONST GOODS_TYPE_DOC = 2;
    CONST GOODS_TYPE_MP3 = 3;

    /* 渠道来源（1-微信；2-ios；3-android；4-pc） */
    CONST SOURCE_WEIXIN = 1;
    CONST SOURCE_IOS = 2;
    CONST SOURCE_ANDROID = 3;
    CONST SOURCE_PC = 4;

    /* Banner属性（1-绑定商品；2-绑定网址） */
    CONST BANNER_ITEM_TYPE_BY_GOODS = 2;
    CONST BANNER_ITEM_TYPE_BY_URL = 1;

    /* 优惠券类型（1-无门槛；2-满减；3-折扣） */
    CONST COUPON_TYPE_OPEN = 1;
    CONST COUPON_TYPE_REDUCE = 2;
    CONST COUPON_TYPE_DISCOUNT = 3;

    /* 用户优惠券使用（0未使用，1使用） */
    CONST COUPON_IS_CONSUME_FALSE = 0;
    CONST COUPON_IS_CONSUME_TRUE = 1;

    /**web左侧菜单显示(是否显示.0显示,1隐藏)**/
    CONST MENU_IS_DISPLAY_TRUE = 0;
    CONST MENU_IS_DISPLAY_FALSE = 1;

    /**菜单分类('manage','ios','android')**/
    CONST MENU_SOURCE_MANAGE = 'manage';
    CONST MENU_SOURCE_IOS = 'ios';
    CONST MENU_SOURCE_ANDROID = 'android';


    /**推荐分类('weixin','ios','android')**/
    CONST CATEGORY_RELEASE_SOURCE_WEIXIN = 'weixin';
    CONST CATEGORY_RELEASE_SOURCE_IOS = 'ios';
    CONST CATEGORY_RELEASE_SOURCE_ANDROID = 'android';

    /**广告位显示(是否显示.0否,1是)**/
    CONST AD_IS_SHOW_TRUE = 0;
    CONST AD_IS_SHOW_FALSE = 1;

    /**产品分类type(1产品课程、2商城)**/
    CONST CATEGORY_TYPE_COURSE = 1;
    CONST CATEGORY_TYPE_STORE = 2;

    /**产品分类类别type(1文字分类，2图标)**/
    CONST CATEGORY_CATEGORY_TYPE_WORD = 1;
    CONST CATEGORY_CATEGORY_TYPE_ICON = 2;

    /**营销活动type(1优惠券,2积分)**/
    CONST MARKET_ACTIVE_TYPE_COUPON = 1;
    CONST MARKET_ACTIVE_TYPE_POINTS = 2;


    /**
     * 翻译 广告渠道来源
     * @auth Von
     * @param $type
     * @return string
     */
    public static function translationBannerSource($type)
    {
        switch ($type) {
            case self::SOURCE_WEIXIN :
                $type = "微信";
                break;
            case self::SOURCE_IOS :
                $type = "ios";
                break;
            case self::SOURCE_ANDROID :
                $type = "android";
                break;
            case self::SOURCE_PC :
                $type = "pc";
                break;
            default :
                $type = "未知";
        }
        return $type;
    }

    /**
     * 翻译 支付方式
     * @auth Enson
     * @param $type
     * @return string
     */
    public static function translationPayWay($type)
    {
        switch ($type) {
            case self::PAY_WAY_WEIXIN :
                $type = "微信";
                break;
            case self::PAY_WAY_ALIPAY :
                $type = "支付宝";
                break;
            default :
                $type = "未知";
        }
        return $type;
    }

    /**
     * 翻译 订单渠道来源
     * @auth Enson
     * @param $type
     * @return string
     */
    public static function translationOrderSource($type)
    {
        switch ($type) {
            case self::SOURCE_ANDROID :
                $type = "Android客户端";
                break;
            case self::SOURCE_IOS :
                $type = "ios客户端";
                break;
            case self::SOURCE_PC :
                $type = "PC网页端";
                break;
            case self::SOURCE_WEIXIN :
                $type = "微信客户端";
                break;
            default :
                $type = "未知";
        }
        return $type;
    }
}
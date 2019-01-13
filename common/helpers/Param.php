<?php

namespace common\helpers;

/**
 * Created by PhpStorm.
 * User: FOCUS
 * Date: 2018/12/19
 * Time: 12:10
 */
use Yii;

class Param
{

    /**
     * 检查哪些参数不存在
     * @param array $standard
     * @param array $request
     * @return array|bool
     * @author von
     */
    public static function checkParams(array $standard, array $request)
    {
        $array = [];
        foreach ($standard as $value) {
            if (empty($request[$value])) {
                $array[] = $value;
            }
        }
        if (!empty($array)) {
            return $array;
        }
        return true;
    }

    /**
     * 设置不存在参数为null或者过滤空格
     * @param array $array
     * @param array $data
     * @return array
     * @author von
     */
    public static function setNull(array $array, array $data)
    {
        foreach ($array as $row) {
            if (!isset($data[$row])) {
                $data[$row] = null;
            }else{
                $data[$row]=trim($data[$row]," ");
            }
        }
        return $data;
    }

    /**
     * 从get或者param配置获取参数，分页参数使用
     * @param $key
     * @return array|bool|mixed
     * @author von
     */
    public static function getParamFromGet($key)
    {
        $page = Yii::$app->request->get($key);
        if (!empty($page)) {
            return $page;
        }
        if (!empty(Yii::$app->params[$key])) {
            return Yii::$app->params[$key];
        }
        return false;
    }

    /**
     * 获取page_size配置参数
     * @return array|bool|mixed
     * @author von
     */
    public static function getPageSize()
    {
        $page = Yii::$app->request->get('page_size');
        if (!empty($page)) {
            return $page;
        }
        if (!empty(Yii::$app->params['page_size'])) {
            return Yii::$app->params['page_size'];
        }
        return false;
    }

    /**
     * 从header或者param配置获取参数
     * @param $key
     * @return array|bool|string
     * @author von
     */
    public static function getHeaders($key)
    {
        $header = Yii::$app->request->headers;
        if (!empty($header->get($key))) {
            return $header->get($key);
        }
        if (!empty(Yii::$app->params[$key])) {
            return Yii::$app->params[$key];
        }
        return false;
    }

    /**
     * 获取请求参数，处理数据格式
     * @return array|mixed
     * @author von
     */
    public static function getParam()
    {
        $headers = Yii::$app->request->headers;
        $type = $headers->get('Content-Type');
//        if (strpos($type, 'json')) {
//            $post = file_get_contents("php://input");
//             return json_decode($post,true);
//        }
        if (strpos($type, 'x-www-form-urlencoded')) {
            return Yii::$app->request->post();
        }
        return Yii::$app->request->post();
    }

}
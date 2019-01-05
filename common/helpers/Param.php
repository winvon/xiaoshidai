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

    public static function setNull(array $array, array $data)
    {
        foreach ($array as $row) {
            if (!isset($data[$row])) {
                $data[$row] = null;
            }
        }
        return $data;
    }

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
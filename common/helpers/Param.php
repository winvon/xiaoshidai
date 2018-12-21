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
            if (empty($data[$row])) {
                $data[$row] = null;
            }
        }
        return $data;
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

}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/18
 * Time: 14:18
 */

namespace app\controllers;

use app\models\User;

use yii;
use common\helpers\WeHelper;
class RController extends yii\rest\ActiveController
{
    public $modelClass = User::class;

    /**
     * 获取服务接口
     * @param $servName
     * @return object
     */
    protected function getService($servName, $proxied = false)
    {
        header("Access-Control-Allow-Origin: *");
        $serv = WeHelper::getService($servName);
        if ($proxied) {
            $servProxy = new ServiceProxy($serv, $this->view);
            $serv = $servProxy;
        }
        return $serv;
    }
}
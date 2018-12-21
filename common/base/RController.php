<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/18
 * Time: 14:18
 */
namespace common\base;

use common\helpers\WeHelper;
use yii\rest\ActiveController;

class RController extends ActiveController
{
    public $modelClass='backend\models\Admin';

    /**
     * 获取服务接口
     * @param $servName
     * @return object
     */
    protected function getService($servName,$proxied=false){
        header("Access-Control-Allow-Origin: *");
        $serv=WeHelper::getService($servName);
        if($proxied){
            $servProxy=new ServiceProxy($serv,$this->view);
            $serv=$servProxy;
        }
        return $serv;
    }
}
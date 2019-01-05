<?php
/**
 * Created by PhpStorm.
 * User: Enson
 * Date: 2018/12/27
 * Time: 15:20
 */

namespace common\base;
use common\helpers\WeHelper;

class ApiController extends \yii\rest\Controller
{
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
<?php

namespace app\modules\test\controllers;

use common\helpers\WeHelper;
use yii\web\Controller;

class DemoController extends \common\base\Controller
{
    public function actionIndex()
    {
        $service = $this->getService('Admins.Demo');
        $data = $service->getAdminList();
        return WeHelper::comReturn($data,0);
        var_dump($data);die();
    }

    public function actionTest(){
        echo 2;exit();
    }
}

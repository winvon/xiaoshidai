<?php
/**
 * Created by PhpStorm.
 * User: FOCUS
 * Date: 2018/12/18
 * Time: 18:17
 */

namespace backend\modules\admin\controllers\banner;

use common\base\RController;
use yii\helpers\Json;

class ItemController extends RController
{
    public $modelClass='backend\models\BannerItemItem';

    public function actions()
    {
        return []; // TODO: Change the autogenerated stub
    }

    public function actionIndex()
    {
        $service = $this->getService('Admins.BannerItem');
        $res = $service->getList();
        echo Json::encode($res);
        exit();
    }

    public function actionView()
    {
        $service = $this->getService('Admins.BannerItem');
        $res = $service->view();
        echo Json::encode($res);
        exit();
    }


    public function actionCreate()
    {
        $service = $this->getService('Admins.BannerItem');
        $res = $service->create();
        echo Json::encode($res);
        exit();
    }

    public function actionUpdate()
    {
        $service = $this->getService('Admins.BannerItem');
        $res = $service->update();
        echo Json::encode($res);
        exit();
    }

    public function actionDelete()
    {
        $service = $this->getService('Admins.BannerItem');
        $res = $service->delete();
        echo Json::encode($res);
        exit();
    }

}
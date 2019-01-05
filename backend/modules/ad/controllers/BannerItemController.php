<?php
/**
 * Created by PhpStorm.
 * User: FOCUS
 * Date: 2018/12/18
 * Time: 18:17
 */

namespace backend\modules\ad\controllers;

use common\base\RController;
use yii\helpers\Json;

class BannerItemController extends RController
{
    public $modelClass='backend\models\BannerItem';

    public function actions()
    {
        $action=parent::actions();
        unset($action['index']);
        unset($action['update']);
        unset($action['create']);
        unset($action['delete']);
        unset($action['view']);
        return $action;
    }


    /**
     * 广告列表
     * @return mixed
     */
    public function actionIndex()
    {
        $service = $this->getService('Ad.BannerItem');
        return $service->getList();
    }

    /**
     * 广告详情
     * @return mixed
     */
    public function actionView()
    {
        $service = $this->getService('Ad.BannerItem');
        return $service->view();
    }

    /**
     * 创建广告
     * @return mixed
     */
    public function actionCreate()
    {
        $service = $this->getService('Ad.BannerItem');
        return $service->create();
    }

    /**
     * 修改广告
     * @return mixed
     */
    public function actionUpdate()
    {
        $service = $this->getService('Ad.BannerItem');
        return $service->update();
    }

    /**
     * 删除广告
     * @return mixed
     */
    public function actionDelete()
    {
        $service = $this->getService('Ad.BannerItem');
        return $service->delete();
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: FOCUS
 * Date: 2018/12/18
 * Time: 18:17
 */

namespace backend\modules\ad\controllers;

use common\base\RController;

class BannerController extends RController
{
    public $modelClass= 'backend\models\Banner0';

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

        $service = $this->getService('Ad.Banner');
        return $service->getList();
    }

    /**
     * 广告详情
     * @return mixed
     */
    public function actionView()
    {
        $service = $this->getService('Ad.Banner');
        return $service->view();
    }

    /**
     * 创建广告
     * @return mixed
     */
    public function actionCreate()
    {
        $service = $this->getService('Ad.Banner');
        return $service->create();
    }

    /**
     * 修改广告
     * @return mixed
     */
    public function actionUpdate()
    {
        $service = $this->getService('Ad.Banner');
        return $service->update();
    }

    /**
     * 删除广告
     * @return mixed
     */
    public function actionDelete()
    {
        $service = $this->getService('Ad.Banner');
        return $service->delete();
    }

    /**
     *  广告冻结|解冻
     * @return mixed
     */
    public function actionLock()
    {
        $service = $this->getService('Ad.Banner');
        return $service->lock();
    }

}
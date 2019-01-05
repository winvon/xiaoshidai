<?php
/**
 * Created by PhpStorm.
 * User: FOCUS
 * Date: 2018/12/18
 * Time: 18:17
 */

namespace backend\modules\ad\controllers;

use common\base\RController;
use common\helpers\Upload;
use yii\helpers\Json;
use Yii;

class BannerController extends RController
{
    public $modelClass = 'backend\models\Banner';

    public function actions()
    {
        $action = parent::actions();
        unset($action['index']);
        unset($action['update']);
        unset($action['create']);
        unset($action['delete']);
        unset($action['view']);
        return $action;
    }


    /**
     * 广告渠道列表
     * @return mixed
     */
    public function actionIndex()
    {
        $service = $this->getService('Ad.Banner');
        return $service->getList();
    }

    /**
     * 广告渠道详情
     * @return mixed
     */
    public function actionView()
    {
        $service = $this->getService('Ad.Banner');
        return $service->view();
    }

    /**
     * 创建广告渠道
     * @return mixed
     */
    public function actionCreate()
    {
        $service = $this->getService('Ad.Banner');
        return $service->create();

    }

    /**
     * 修改广告渠道
     * @return mixed
     */
    public function actionUpdate()
    {
        $service = $this->getService('Ad.Banner');
        return $service->update();
    }

    /**
     * 删除广告渠道
     * @return mixed
     */
    public function actionDelete()
    {
        $service = $this->getService('Ad.Banner');
        return $service->delete();
    }

}
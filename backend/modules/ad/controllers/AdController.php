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

class AdController extends RController
{
    public $modelClass = 'backend\models\Ad';

    public function actions()
    {
        return [];
    }


    /**
     * 广告渠道列表
     * @return mixed
     */
    public function actionIndex()
    {
        $service = $this->getService('Ad.Ad');
        return $service->getList();
    }

    /**
     * 广告渠道详情
     * @return mixed
     */
    public function actionView()
    {
        $service = $this->getService('Ad.Ad');
        return $service->view();
    }

    /**
     * 创建广告渠道
     * @return mixed
     */
    public function actionCreate()
    {
        $service = $this->getService('Ad.Ad');
        return $service->create();

    }

    /**
     * 修改广告渠道
     * @return mixed
     */
    public function actionUpdate()
    {
        $service = $this->getService('Ad.Ad');
        return $service->update();
    }

    /**
     * 删除广告渠道
     * @return mixed
     */
    public function actionDelete()
    {
        $service = $this->getService('Ad.Ad');
        return $service->delete();
    }
    /**
     * 广告位渠道显示与隐藏
     * @return mixed
     */
    public function actionShow()
    {
        $service = $this->getService('Ad.Ad');
        return $service->show();
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: FOCUS
 * Date: 2018/12/18
 * Time: 18:17
 */

namespace backend\modules\goods\controllers;

use common\base\RController;
use yii\helpers\Json;

class CategoryController extends RController
{
    public $modelClass = 'backend\models\Category';

    public function actions()
    {
        $action = parent::actions();
        unset($action['index']);
        unset($action['update']);
        unset($action['create']);
        unset($action['delete']);
        return $action;
    }

    public function actionIndex()
    {
        $service = $this->getService('Goods.Category');
        return $service->getList();
    }

    public function actionIndexTree()
    {
        $service = $this->getService('Goods.Category');
        return $service->getListByTree();
    }

    public function actionView()
    {
        $service = $this->getService('Goods.Category');
        return $service->view();
    }

    public function actionCreate()
    {
        $service = $this->getService('Goods.Category');
        return $service->create();
    }

    public function actionUpdate()
    {
        $service = $this->getService('Goods.Category');
        return $service->update();
    }

    public function actionDelete()
    {
        $service = $this->getService('Goods.Category');
        return $service->delete();
    }

    public function actionShow()
    {
        $service = $this->getService('Goods.Category');
        return $service->show();
    }

}
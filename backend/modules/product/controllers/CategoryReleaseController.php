<?php
/**
 * Created by PhpStorm.
 * User: FOCUS
 * Date: 2018/12/18
 * Time: 18:17
 */

namespace backend\modules\product\controllers;

use common\base\RController;
use yii\helpers\Json;

class CategoryReleaseController extends RController
{
    public $modelClass = 'backend\models\CategoryRelease';

    public function actions()
    {
       return[];
    }

    public function actionIndex()
    {
        $service = $this->getService('Product.CategoryRelease');
        return $service->getList();
    }

    public function actionCreate()
    {
        $service = $this->getService('Product.CategoryRelease');
        return $service->create();
    }

    public function actionUpdate()
    {
        $service = $this->getService('Product.CategoryRelease');
        return $service->update();
    }

    public function actionView()
    {
        $service = $this->getService('Product.CategoryRelease');
        return $service->view();
    }

    public function actionDelete()
    {
        $service = $this->getService('Product.CategoryRelease');
        return $service->delete();
    }


}
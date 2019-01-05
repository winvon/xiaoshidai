<?php
/**
 **********************************************************
 * @author : YiChaobao [ yichaobao@163.com ]
 * @time : 2019/01/01 00:00
 * @copyright : (c) 2019 YiChaobao All rights reserved.
 **********************************************************
 * @name : ProductController.php
 * @description : 产品
 **/
namespace backend\modules\product\controllers;
use common\base\RController;
use Yii;
class ProductController extends RController
{
    public function actions()
    {
        return [];
    }
    public function actionIndex()
    {

    }
    public function actionCreate()
    {
        $data = Yii::$app->request->bodyParams;
        $service = $this->getService('Goods.Product');
        return $service->getProductInfo(1);
    }
}
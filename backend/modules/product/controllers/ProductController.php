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
        $request = Yii::$app->request;
        $page_size = $request->get('page_size',20);//分页数[默认20条]
        $order = $request->get('order','created_at DESC');//排序方式[默认注册时间倒序]
        $where = [];
        $where['type'] = $request->get('type','');//类型
        $where['product_name'] = $request->get('product_name','');//产品名称
        $where['category_id'] = $request->get('category_id','');//所属分类

        $where['mobile'] = $request->get('mobile','');//用户手机号码

        $created_start_time = $request->get('created_start_time');
        $where['created_start_time'] = $created_start_time ? strtotime($created_start_time) : null;
        $created_end_time = $request->get('created_end_time');
        $where['created_end_time'] = $created_end_time ? strtotime($created_end_time) : null;
        $service = $this->getService('Product.Product');
        return $service->getProductList($where,$order,$page_size);
    }

    /**
     * @desc: 创建
     * @name: actionCreate
     * @return mixed
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
    public function actionCreate()
    {
        $data = Yii::$app->request->post();
        $service = $this->getService('Product.Product');
        return $service->addProduct($data);
    }

    /**
     * @desc: 获取商品详情
     * @name: actionView
     * @return mixed
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
    public function actionView(){
        $id = Yii::$app->request->get('id');
        $service = $this->getService('Product.Product');
        return $service->getProductInfo($id);
    }

    /**
     * @desc: 更新商品
     * @name: actionUpdate
     * @return mixed
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
    public function actionUpdate()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $data = $request->post();
        $service = $this->getService('Product.Product');
        return $service->updateProduct($id, $data);
    }

    public function actionDelete()
    {
        $goods_id = Yii::$app->request->get('id');
        $service = $this->getService('Product.Product');
        return $service->deleteProduct($goods_id);
    }
}
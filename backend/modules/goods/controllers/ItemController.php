<?php
/**
 **********************************************************
 * @author : YiChaobao [ yichaobao@163.com ]
 * @time : 2019/01/01 00:00
 * @copyright : (c) 2019 YiChaobao All rights reserved.
 **********************************************************
 * @name : ItemController.php
 * @description : 商品分集
 **/
namespace backend\modules\goods\controllers;
use common\base\RController;
use Yii;
class ItemController extends RController
{
    public function actions()
    {
        return [];
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
        $service = $this->getService('Goods.Item');
        return $service->addGoodsItem($data);
    }

    /**
     * @desc: 查看信息
     * @name: actionView
     * @return mixed
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
    public function actionView(){
        $goods_item_id = Yii::$app->request->get('id');
        $service = $this->getService('Goods.Item');
        return $service->getGoodsItemInfo($goods_item_id);
    }

    /**
     * @desc: 更新
     * @name: actionUpdate
     * @return mixed
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
    public function actionUpdate()
    {
        $request = Yii::$app->request;
        $goods_item_id = $request->get('id');
        $data = $request->post();
        $service = $this->getService('Goods.Item');
        return $service->updateGoodsItem($goods_item_id, $data);
    }

    /**
     * @desc: 删除
     * @name: actionDelete
     * @return mixed
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
    public function actionDelete()
    {
        $goods_item_id = Yii::$app->request->get('id');
        $service = $this->getService('Goods.Item');
        return $service->deleteGoodsItem($goods_item_id);
    }

    public function actionIndex()
    {
        $request = Yii::$app->request;
        $page_size = $request->get('page_size',20);//分页数[默认20条]
        $order = $request->get('order','created_at DESC');//排序方式[默认注册时间倒序]
        $where = [];
        $where['goods_item_name'] = $request->get('goods_item_name','');//商品名称
        $where['goods_id'] = $request->get('goods_id','');//商品编号

        $created_start_time = $request->get('created_start_time');
        $where['created_start_time'] = $created_start_time ? strtotime($created_start_time) : null;
        $created_end_time = $request->get('created_end_time');
        $where['created_end_time'] = $created_end_time ? strtotime($created_end_time) : null;

        $service = $this->getService('Goods.Item');
        return $service->getGoodsItemList($where,$order,$page_size);
    }
}
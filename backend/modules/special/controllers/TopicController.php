<?php
/**
 **********************************************************
 * @author : YiChaobao [ yichaobao@163.com ]
 * @time : 2019/01/16 10:07
 * @copyright : (c) 2019 YiChaobao All rights reserved.
 **********************************************************
 * @name : TopicController.php
 * @description :
 **/
namespace backend\modules\special\controllers;
use common\base\RController;
use Yii;
class TopicController extends RController
{
    public function actions()
    {
        return [];
    }
    public function actionIndex(){
        $request = Yii::$app->request;
        $page_size = $request->get('page_size',20);//分页数[默认20条]
        $order = $request->get('order','created_at DESC');//排序方式[默认注册时间倒序]
        $where = [];
        $where['source'] = $request->get('source','');// 渠道
        $where['client'] = $request->get('client','');// 来源

        $where['topic_name'] = $request->get('topic_name','');//名称

        $created_start_time = $request->get('start_time');
        $where['start_time'] = $created_start_time ? strtotime($created_start_time) : null;
        $created_end_time = $request->get('end_time');
        $where['end_time'] = $created_end_time ? strtotime($created_end_time) : null;
        $service = $this->getService('Special.Topic');

        return $service->getListData($where,$order,$page_size);
    }

    public function actionCreate()
    {
        $data = Yii::$app->request->post();
        $service = $this->getService('Special.Topic');
        return $service->addData($data);
    }

    public function actionView()
    {
        $id = Yii::$app->request->get('id');
        $service = $this->getService('Special.Topic');
        return $service->getInfo($id);
    }

    public function actionDelete()
    {
        $id = Yii::$app->request->get('id');
        $service = $this->getService('Special.Topic');
        return $service->deleteData($id);
    }

    public function actionLock(){
        $id = Yii::$app->request->get('id');
        $service = $this->getService('Special.Topic');
        return $service->lockStatus($id,1);
    }

    public function actionUnlock(){
        $id = Yii::$app->request->get('id');
        $service = $this->getService('Special.Topic');
        return $service->lockStatus($id,0);
    }

    public function actionUpdate(){
        $request = Yii::$app->request;
        $id = $request->get('id');
        $data = $request->post();
        $service = $this->getService('Special.Topic');
        return $service->updateData($id, $data);
    }
}
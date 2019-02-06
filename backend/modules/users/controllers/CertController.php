<?php
/**
 **********************************************************
 * @author : YiChaobao [ yichaobao@163.com ]
 * @time : 2019/01/14 11:54
 * @copyright : (c) 2019 YiChaobao All rights reserved.
 **********************************************************
 * @name : CertController.php
 * @description : 证书管理
 **/
namespace backend\modules\users\controllers;

use common\base\RController;
use Yii;

class CertController extends RController
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
        $where['username'] = $request->get('username','');//类型
        $where['id_card'] = $request->get('id_card','');//产品名称
        $where['user_role_id'] = $request->get('user_role_id','');//所属分类

        $where['mobile'] = $request->get('mobile','');//用户手机号码

        $created_start_time = $request->get('created_start_time');
        $where['created_start_time'] = $created_start_time ? strtotime($created_start_time) : null;
        $created_end_time = $request->get('created_end_time');
        $where['created_end_time'] = $created_end_time ? strtotime($created_end_time) : null;
        $service = $this->getService('User.Cert');
        return $service->getLists($where,$order,$page_size);
    }

    public function actionCreate()
    {
        $data = Yii::$app->request->post();
        $service = $this->getService('User.Cert');
        return $service->addData($data);
    }

    public function actionView()
    {
        $id = Yii::$app->request->get('id');
        $service = $this->getService('User.Cert');
        return $service->getDataInfo($id);
    }

    public function actionUpdate()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $data = $request->post();
        $service = $this->getService('User.Cert');
        return $service->updateData($id, $data);
    }

    public function actionDelete()
    {
        $user_id = Yii::$app->request->get('id');
        $service = $this->getService('User.Cert');
        return $service->deleteData($user_id);
    }

    public function actionLog(){
        $id = Yii::$app->request->get('id');
        $service = $this->getService('User.Cert');
        return $service->getLogLists($id);
    }
}
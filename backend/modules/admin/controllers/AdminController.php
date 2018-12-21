<?php
/**
 * Created by PhpStorm.
 * User: FOCUS
 * Date: 2018/12/18
 * Time: 18:17
 */

namespace backend\modules\admin\controllers;

use common\base\Controller;
use common\base\RController;
use Yii;
use yii\helpers\Json;

class AdminController extends RController
{

    public $except_identity = ['login', 'view'];

    public function beforeAction($action)
    {
        parent::beforeAction($action);
        /*身份检查*/
        if (!in_array($action->id, $this->except_token)) {
            $service = $this->getService('Admins.Admin');
            $res = $service->checkIdentity();
            if (is_array($res)) {
                echo Json::encode($res);
                exit();
            }
        }
        return true;
    }

    public function actions()
    {
        return []; // TODO: Change the autogenerated stub
    }

    public function actionIndex()
    {
        $service = $this->getService('Admins.Admin');
        $res = $service->getList();
        echo Json::encode($res);
        exit();
    }

    public function actionView()
    {
        $service = $this->getService('Admins.Admin');
        $res = $service->view();
        echo Json::encode($res);
        exit();
    }

    public function actionLogin()
    {
        $service = $this->getService('Admins.Admin');
        $res = $service->login();
        echo Json::encode($res);
        exit();
    }

    public function actionCreate()
    {
        $service = $this->getService('Admins.Admin');
        $res = $service->create();
        echo Json::encode($res);
        exit();
    }

    public function actionUpdate()
    {
        $service = $this->getService('Admins.Admin');
        $res = $service->update();
        echo Json::encode($res);
        exit();
    }

    public function actionDelete()
    {
        $service = $this->getService('Admins.Admin');
        $res = $service->delete();
        echo Json::encode($res);
        exit();
    }

    public function actionLock()
    {
        $service = $this->getService('Admins.Admin');
        $res = $service->lock();
        echo Json::encode($res);
        exit();
    }

    public function actionUnLock()
    {
        $service = $this->getService('Admins.Admin');
        $res = $service->unlock();
        echo Json::encode($res);
        exit();
    }


}
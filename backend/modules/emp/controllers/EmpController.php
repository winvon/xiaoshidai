<?php
/**
 * Created by PhpStorm.
 * User: FOCUS
 * Date: 2018/12/18
 * Time: 18:17
 */

namespace backend\modules\emp\controllers;

use common\base\RController;
use common\helpers\BackendErrorCode;
use common\helpers\WeHelper;

class EmpController extends RController
{
    /**身份检查**/
    public $except_identity = ['login', 'view'];

    public function beforeAction($action)
    {
        parent::beforeAction($action);
        /*身份检查*/
//        if (!in_array($action->id, $this->except_token)) {
//            $service = $this->getService('Emp.Emp');
//            $res = $service->checkIdentity();
//            if (is_array($res)) {
//                header("Content-Type:application/json;charset=UTF-8");
//                echo json_encode(WeHelper::jsonReturn(null,BackendErrorCode::ERR_IDENTITY),JSON_UNESCAPED_UNICODE)  ;
//                die();
//            }
//        }
        return true;
    }

    public function actions()
    {
        return [];
    }

    /**
     * 获取列表
     * @return mixed
     */
    public function actionIndex()
    {
        $service = $this->getService('Emp.Emp');
        return $service->getList();
    }

    /**
     * 获取单个数据详情
     * @return mixed
     */
    public function actionView()
    {
        $service = $this->getService('Emp.Emp');
        return $service->view();
    }

    /**
     * 登陆
     * @return mixed
     */
    public function actionLogin()
    {
        $service = $this->getService('Emp.Emp');
        return $service->login();
    }


    /**
     * 登陆
     * @return mixed
     */
    public function actionChangePassword()
    {
        $service = $this->getService('Emp.Emp');
        return $service->changePassword();
    }

    /**
     * 新增员工
     * @return mixed
     */
    public function actionCreate()
    {
        $service = $this->getService('Emp.Emp');
        return $service->create();
    }

    /**
     * 新增
     * @return mixed
     */
    public function actionUpdate()
    {
        $service = $this->getService('Emp.Emp');
        return $service->update();
    }

    /**
     * 删除员工
     * @return mixed
     */
    public function actionDelete()
    {
        $service = $this->getService('Emp.Emp');
        return $service->delete();
    }

    /**
     * 冻结员工
     * @return mixed
     */
    public function actionLock()
    {
        $service = $this->getService('Emp.Emp');
        return $service->lock();
    }

    /**
     * 解冻员工
     * @return mixed
     */
    public function actionUnLock()
    {
        $service = $this->getService('Emp.Emp');
        return $service->unlock();

    }
}
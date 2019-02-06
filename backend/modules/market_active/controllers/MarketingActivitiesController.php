<?php
/**
 * Created by PhpStorm.
 * User: FOCUS
 * Date: 2018/12/18
 * Time: 18:17
 */

namespace backend\modules\market_active\controllers;

use common\base\RController;
use common\helpers\BackendErrorCode;
use common\helpers\WeHelper;

class MarketingActivitiesController extends RController
{
    /*检查数据冻结的路由*/
    public $check_lock_route = ['update', 'sort', 'add-users', 'delete-users'];

    public function actions()
    {
        return [];
    }


    public function beforeAction($action)
    {
        parent::beforeAction($action);
        if (in_array($action->id,$this->check_lock_route)){
            $service = $this->getService('MarketingActivities.MarketingActivities');
            $res = $service->checkLock();
            if ($res===false) {
                header("Content-Type:application/json;charset=UTF-8");
                echo json_encode(WeHelper::jsonReturn(null, BackendErrorCode::ERR_LOCK), JSON_UNESCAPED_UNICODE);
                die();
            }
        }
        return true;
    }

    /**
     * 获取列表
     * @return mixed
     */
    public function actionIndex()
    {
        $service = $this->getService('MarketingActivities.MarketingActivities');
        return $service->index();
    }

    /**
     * 获取单个数据详情
     * @return mixed
     */
    public function actionView($id)
    {
        $service = $this->getService('MarketingActivities.MarketingActivities');
        return $service->view($id);
    }

    /**
     * 新增
     * @return mixed
     */
    public function actionCreate()
    {
        $service = $this->getService('MarketingActivities.MarketingActivities');
        return $service->create();
    }

    /**
     * 修改
     * @return mixed
     */
    public function actionUpdate()
    {
        $service = $this->getService('MarketingActivities.MarketingActivities');
        return $service->update();
    }

    /**
     * 删除
     * @return mixed
     */
    public function actionDelete()
    {
        $service = $this->getService('MarketingActivities.MarketingActivities');
        return $service->delete();
    }

    /**
     * 冻结
     * @return mixed
     */
    public function actionLock()
    {
        $service = $this->getService('MarketingActivities.MarketingActivities');
        return $service->lock();
    }

    /**
     * 活动排序
     * @return mixed
     */
    public function actionSort()
    {
        $service = $this->getService('MarketingActivities.MarketingActivities');
        return $service->sort();
    }

    /**
     * 添加活动用户
     * @return mixed
     */
    public function actionAddUsers()
    {
        $service = $this->getService('MarketingActivities.MarketingActivities');
        return $service->addUsers();
    }


    /**
     * 删除活动用户
     * @return mixed
     */
    public function actionDeleteUsers()
    {
        $service = $this->getService('MarketingActivities.MarketingActivities');
        return $service->deleteUsers();
    }

}
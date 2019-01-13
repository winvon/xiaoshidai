<?php
/**
 * Created by PhpStorm.
 * User: FOCUS
 * Date: 2018/12/18
 * Time: 18:17
 */

namespace backend\modules\coupon\controllers;

use common\base\RController;
use yii\helpers\Json;

class UserCouponController  extends RController
{
    public $modelClass='backend\models\UserCoupon';

    /**
     * @return array
     */
    public function actions()
    {
        $action=parent::actions();
        unset($action['index']);
        unset($action['update']);
        unset($action['create']);
        unset($action['delete']);
        return $action;
    }


    /**
     * 获取用户优惠券列表
     * @return mixed
     */
    public function actionIndex()
    {
        $service = $this->getService('Coupon.UserCoupon');
        return $service->getList();
    }

    /**
     * 获取用户优惠券详情
     * @return mixed
     */
    public function actionView()
    {
        $service = $this->getService('Coupon.UserCoupon');
        return $service->view();
    }

    /**
     * 添加用户优惠券
     * @return mixed
     */
    public function actionCreate()
    {
        $service = $this->getService('Coupon.UserCoupon');
        return $service->create();
    }

    /**
     * 修改用户优惠券
     * @return mixed
     */
    public function actionUpdate()
    {
        $service = $this->getService('Coupon.UserCoupon');
        return $service->update();
    }

    /**
     * 删除用户优惠券
     * @return mixed
     */
    public function actionDelete()
    {
        $service = $this->getService('Coupon.UserCoupon');
        return $service->delete();
    }

    /**
     * 冻结|解冻用户优惠券
     * @return mixed
     */
    public function actionLock()
    {
        $service = $this->getService('Coupon.UserCoupon');
        return $service->lock();
    }

}
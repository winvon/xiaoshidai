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

class CouponController extends RController
{
    public $modelClass = 'backend\models\Coupon';

    public function actions()
    {
        $action = parent::actions();
        unset($action['index']);
        unset($action['update']);
        unset($action['create']);
        unset($action['delete']);
        return $action;
    }


    /**
     * 获取优惠券列表
     * @return mixed
     */
    public function actionIndex()
    {
        $service = $this->getService('Coupon.Coupon');
        return $service->getList();
    }

    /**
     * 查看优惠券详情
     * @return mixed
     */
    public function actionView()
    {
        $service = $this->getService('Coupon.Coupon');
        return $service->view();
    }

    /**
     * 新增优惠券
     * @return mixed
     */
    public function actionCreate()
    {
        $service = $this->getService('Coupon.Coupon');
        return $service->create();
    }

    /**
     * 修改优惠券
     * @return mixed
     */
    public function actionUpdate()
    {
        $service = $this->getService('Coupon.Coupon');
        return $service->update();
    }

    /**
     * 删除优惠券
     * @return mixed
     */
    public function actionDelete()
    {
        $service = $this->getService('Coupon.Coupon');
        return $service->delete();
    }

    /**
     * 冻结或解冻优惠券
     * @return mixed
     */
    public function actionLock()
    {
        $service = $this->getService('Coupon.Coupon');
        return $service->lockCouponById();
    }

    /**
     * 发放优惠券
     * @return mixed
     */
    public function actionGiveout()
    {
        $service = $this->getService('Coupon.Coupon');
        return $service->giveout();
    }

}
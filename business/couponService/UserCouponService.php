<?php
/**
 * Created by PhpStorm.
 * User: FOCUS
 * Date: 2018/12/19
 * Time: 14:57
 */

namespace business\couponService;

use backend\models\Ad;
use backend\models\Coupon;
use backend\models\UserCoupon;
use business\interfaceService\admin\IBannerService;
use common\helpers\ConstantHelper;
use common\helpers\Param;
use common\helpers\BackendErrorCode;
use common\helpers\ErrorCode;
use common\helpers\WeHelper;
use Yii;

class UserCouponService implements IBannerService
{
    private $model;

    public function __construct()
    {
        $this->model = new UserCoupon();
    }

    /**
     * 是否冻结
     * @return array|bool|null
     * @author von
     */
    public function checkLock()
    {
        $id = Yii::$app->request->get('id');
        try {
            $res = $this->model->findOneById($id);
            if ($res) {
                if ($res->is_lock===ConstantHelper::IS_LOCK_TRUE){
                    return false;
                }
                if ($res->is_lock===ConstantHelper::IS_LOCK_TRUE){
                    return true;
                }
            }
        } catch (\Exception $e) {
            Yii::warning($e->getMessage());
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return true;
    }

    public function getList()
    {
        // TODO: Implement getList() method.
    }

    /**
     * 获取用户优惠券详情
     * @param $params
     * @return mixed
     */
    public function getView($id)
    {
        $get = Yii::$app->request->get();
        try {
            $get = Param::setNull(['username', 'coupon_name', 'is_lock', 'is_consume', 'user_id'], $get);
            $get['user_id'] = empty($get['user_id']) ? $id : null;
            $res = $this->model->getList($get);
            return WeHelper::jsonReturn($res, BackendErrorCode::ERR_SUCCESS);
        } catch (\Exception $e) {
            return $e->getMessage();
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return $res;
    }


    /**
     * 获取用户汇总列表
     * @param $params
     * @return mixed
     */
    public function getUserList()
    {
        $get = Yii::$app->request->get();
        try {
            $get = Param::setNull(['username', 'coupon_name', 'user_id', 'is_lock', 'is_consume'], $get);
            $res = $this->model->getUserList($get);
            return WeHelper::jsonReturn($res, BackendErrorCode::ERR_SUCCESS);
        } catch (\Exception $e) {
            return $e->getMessage();
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return $res;
    }

    /**
     * 添加数据
     * @param $params
     * @return boolean
     */
    public function create()
    {
        $post = Param::getParam();
        try {
            $res = $this->model->createUserCoupon($post);
            if ($res === true) {
                return WeHelper::jsonReturn(null, BackendErrorCode::ERR_SUCCESS);
            } else {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_MODEL_VALIDATE);
            }
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return true;

    }

    /**
     * 修改数据
     * @param $params
     * @return mixed
     */
    public function update()
    {
        $post = Param::getParam();
        $get = Yii::$app->request->get();
        try {
            $res = $this->model->findOneById($get['id']);
            if (!$res) {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_OBJECT_NON);
            }
            $this->model = $res;

            if ($this->model->modifyUserCoupon($post) !== true) {
                return WeHelper::jsonReturn(null, BackendErrorCode::ERR_MODEL_VALIDATE);
            }
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_SUCCESS);

        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return true;
    }

    /**
     * 数据详情
     * @param $params
     * @return boolean
     */
    public function view()
    {
        $get = Yii::$app->request->get();
        try {

            $res = $this->model->findOneById($get['id']);
            if (!$res) {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_OBJECT_NON);
            }

            $this->model = $res;

            $res = $this->model->getView();

            return WeHelper::jsonReturn($res, BackendErrorCode::ERR_SUCCESS);

        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return true;
    }

    /**
     * @return array|bool|null|string
     * @author von
     */
    public function delete()
    {
        $get = Yii::$app->request->get();
        try {

            $res = $this->model->findOneById($get['id']);
            if (!$res) {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_OBJECT_NON);
            }
            $this->model = $res;

            if ($this->model->del() === true) {
                return WeHelper::jsonReturn(null, BackendErrorCode::ERR_SUCCESS);
            } elseif ($this->model->del() === false) {
                return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DELETE);
            } else {
                return WeHelper::jsonReturn(null, BackendErrorCode::ERR_MODEL_VALIDATE);
            }

        } catch (\Exception $e) {
            return $e->getMessage();
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return true;
    }

    /**
     * 冻结|解冻
     * @return array|bool|string
     */
    public function lock()
    {
        $get = Yii::$app->request->get();
        try {
            $res = $this->model->findOneById($get['id']);
            if (!$res) {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_OBJECT_NON);
            }
            $this->model = $res;

            if ($this->model->lock() !== true) {
                return WeHelper::jsonReturn(null, BackendErrorCode::ERR_MODEL_VALIDATE);
            }
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_SUCCESS);

        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return true;
    }
}
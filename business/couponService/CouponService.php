<?php
/**
 * Created by PhpStorm.
 * User: FOCUS
 * Date: 2018/12/19
 * Time: 14:57
 */

namespace business\couponService;

use backend\models\Coupon;
use backend\models\UserCoupon;
use business\interfaceService\admin\IBannerService;
use common\helpers\ConstantHelper;
use common\helpers\Param;
use common\helpers\BackendErrorCode;
use common\helpers\ErrorCode;
use common\helpers\WeHelper;
use Yii;

class CouponService implements IBannerService
{
    private $model;

    public function __construct()
    {
        $this->model = new Coupon();
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

    /**
     * 获取列表
     * @param $params
     * @return mixed
     */
    public function getList()
    {
        $get = Yii::$app->request->get();
        try {
            $get = Param::setNull(['coupon_name', 'restriction', 'coupon_value', 'is_lock', 'type'], $get);
            $res = $this->model->getList($get);
            return WeHelper::jsonReturn($res, BackendErrorCode::ERR_SUCCESS);
        } catch (\Exception $e) {
            return $e->getMessage();
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return $res;
    }

    /**
     * @return array|bool|null
     *
     * @author von
     */
    public function create()
    {
        $post = Param::getParam();
        try {
            $res = $this->model->createCoupon($post);
            if ($res === true) {
                return WeHelper::jsonReturn(null, BackendErrorCode::ERR_SUCCESS);
            } else {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_MODEL_VALIDATE);
            }
        } catch (\Exception $e) {
            return WeHelper::jsonReturn($e->getMessage(), BackendErrorCode::ERR_DB);
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
            $res = $this->model->modifyCoupon($post);
            if ($res !== true) {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_MODEL_VALIDATE);
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
     * 删除数据
     * @param $params
     * @return boolean
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
            $res = $this->model->del();
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
     * 冻结或解冻
     * @param $params
     * @return boolean
     */
    public function lockCouponById()
    {
        $get = Yii::$app->request->get();
        try {
            $res = $this->model->findOneById($get['id']);
            if (!$res) {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_OBJECT_NON);
            }
            $this->model = $res;
            $res = $this->model->lockCouponById();
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
     * 发放优惠券
     * @return array|bool|string
     */
    public function giveout()
    {
        $post = Param::getParam();
        $db = Yii::$app->db;
        $transcation = $db->beginTransaction();
        try {
            $res = Param::checkParams(['user_id', 'coupon_id'], $post);
            if ($res !== true) {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_PARAM_LOSE);
            }
            $res = $this->model->findOneById($post['coupon_id']);
            if (!$res || $res->is_delete == ConstantHelper::IS_DELETE_TRUE || $res->is_lock == ConstantHelper::IS_LOCK_TRUE) {
                return WeHelper::jsonReturn(null, 40001);
            }
            $this->model = $res;
            $user_ids = explode(',', $post['user_id']);
            /**已发行优惠券的数量**/
            $had_give_coupon_number = UserCoupon::findAllByCouponId($res->id);
            /**剩下发行的数量**/
            $true_coupon_number = $res->number - count($had_give_coupon_number);
            $success_number = 0;//成功的用户数量
            $failed_number = 0;//失败的用户数量
            $insert = [];
            $success_ids = [];
            foreach ($user_ids as $user_id) {
                $this_users = UserCoupon::findAllByUserIdAndCouponId($user_id, $res->id);//这个用户领取的优惠券数量+成功的计数
                /**检查这个用户领取的优惠券数量+成功的计数**/
                !isset($success_ids[$user_id]) ? $success_ids[$user_id] = 0 : '';
                if ((count($this_users) + (int)$success_ids[$user_id]) >= $this->model->max_get_number) {
                    $failed_number += 1;
                    continue;
                }
                /**检查用户是否合法还没有写**/
                if ((int)($true_coupon_number - $success_number) > 0) {
                    /**组装用户优惠券数据**/
                    $insert[] = [$post['coupon_id'], $user_id, 0, 0, 0, 0, time(), time()];
                    $success_number += 1;
                    $success_ids[$user_id] += 1;
                } else {
                    /**发完跳出循环**/
                    $failed_number = count($user_ids) - $success_number;
                    break;
                }

                /**如果 成功用户 大于 剩下发行的数量 则跳出循环**/
                if (((int)$success_number + (int)$had_give_coupon_number) > (int)$true_coupon_number) {
                    $failed_number = count($user_ids) - $success_number;
                    break;
                }
            }

            /*发放*/
            if (!empty($insert)) {
                $res = $db->createCommand()->batchInsert(UserCoupon::tableName(),
                    ['coupon_id', 'user_id', 'is_lock', 'is_delete', 'is_consume', 'consume_time', 'created_at', 'updated_at'],
                    $insert)->execute();
            }

            if ($res == true) {
                $transcation->commit();
                return WeHelper::jsonReturn(['success' => $success_number, 'failed' => $failed_number], BackendErrorCode::ERR_SUCCESS);
            } else {
                $transcation->rollBack();
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_MODEL_VALIDATE);
            }
        } catch (\Exception $e) {
            $transcation->rollBack();
            Yii::warning('发放优惠券失败');
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return true;
    }

}
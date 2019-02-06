<?php
/**
 **********************************************************
 * @author : YiChaobao [ yichaobao@163.com ]
 * @time : 2019/01/16 10:27
 * @copyright : (c) 2019 YiChaobao All rights reserved.
 **********************************************************
 * @name : ITopicService.php
 * @description :
 **/

namespace business\market_activeService;

use backend\models\MarketingActivities;
use backend\models\MarketingActivitiesUser;
use business\interfaceService\market_active\IMarketingActivitiesService;
use common\helpers\ConstantHelper;
use Yii;
use common\helpers\Param;
use common\helpers\WeHelper;
use common\helpers\BackendErrorCode;

class  MarketingActivitiesService implements IMarketingActivitiesService
{
    private $model;
    private $userModel;

    public function __construct()
    {
        $this->model = new MarketingActivities();
        $this->userModel = new MarketingActivitiesUser();
    }

    public function index()
    {
        $get = Yii::$app->request->get();
        try {
            $get = Param::setNull(['activities_name', 'type', 'is_lock'], $get);
            $res = $this->model->getList($get);
            return WeHelper::jsonReturn($res, BackendErrorCode::ERR_SUCCESS);
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return $res;
    }


    /**
     * 添加数据
     * @return array|bool|null
     *
     * @author von
     */
    public function create()
    {
        $post = Param::getParam();
        try {
            $res = $this->model->createMarketingActivities($post);
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
            $res = $this->model->modifyMarketingActivities($post);
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
    public function view($id)
    {
        $get = Yii::$app->request->get();
        $get = Param::setNull(['username'], $get);
        try {
            $res = $this->model->findOneById($id);
            if (!$res) {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_OBJECT_NON);
            }
            $this->model = $res;
            $res = $this->model->getView();
            $get['activities_id'] = $this->model->id;
            /*获取活动用户详情*/
            $this->userModel->getList($get);
            $res['detail'] = $this->userModel->getList($get);
            $res['coupon_list'] = $this->model->getCouponList();
            return WeHelper::jsonReturn($res, BackendErrorCode::ERR_SUCCESS);
        } catch (\Exception $e) {
            Yii::warning($e->getMessage());
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return true;
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

            $res = $this->model->del();

            if ($res === true) {
                return WeHelper::jsonReturn(null, BackendErrorCode::ERR_SUCCESS);
            } elseif ($res === false) {
                return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DELETE);
            } else {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_MODEL_VALIDATE);
            }

        } catch (\Exception $e) {
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
            if ($this->model->lockCouponById() !== true) {
                return WeHelper::jsonReturn(null, BackendErrorCode::ERR_MODEL_VALIDATE);
            }
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_SUCCESS);

        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return true;
    }


    /**
     * 排序
     * @return array|bool|string
     */
    public function sort()
    {
        $get = Yii::$app->request->get();
        $post = Yii::$app->request->post();
        try {
            $res = $this->model->findOneById($get['id']);
            if (!$res) {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_OBJECT_NON);
            }
            $this->model = $res;
            $res= $this->model->sort($post);
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
     * 添加活动用户
     * @return array|bool|string
     */
    public function addUsers()
    {
        $get = Yii::$app->request->get();
        $post = Yii::$app->request->post();
        try {
            $res = Param::checkParams(['user_id'], $post);
            if ($res !== true) {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_PARAM_LOSE);
            }
            $res = $this->model->findOneById($get['id']);

            if (!$res) {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_OBJECT_NON);
            }

            $this->model = $res;

            $res = $this->userModel->createMarketingActivitiesUser($this->model->id, $post['user_id']);

            return WeHelper::jsonReturn($res, BackendErrorCode::ERR_SUCCESS);

        } catch (\Exception $e) {
            Yii::warning($e->getMessage());
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return true;
    }

    /**
     * 删除活动用户
     * @return array|bool|string
     */
    public function deleteUsers()
    {
        $get = Yii::$app->request->get();
        try {
            $ids = explode(',', $get['active_id']);
            foreach ($ids as $id){
                $this->userModel->delById($id);
            }
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_SUCCESS);
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return true;
    }
}
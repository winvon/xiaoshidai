<?php
/**
 * Created by PhpStorm.
 * User: FOCUS
 * Date: 2018/12/19
 * Time: 14:57
 */

namespace business\adminsService;

use backend\models\Admin;
use business\interfaceService\admin\IAdminService;
use common\helpers\Param;
use common\helpers\BackendErrorCode;
use common\helpers\ErrorCode;
use common\helpers\WeHelper;
use Yii;

class AdminService implements IAdminService
{
    private $model;

    public function __construct()
    {
        $this->model = new Admin();
    }

    public function checkToken()
    {
        $token = Yii::$app->request->headers->get('token');
        try {
            if (empty($token)) {
                return WeHelper::comReturn(['token'], BackendErrorCode::ERR_PARAM_LOSE);
            }

            $res = $this->model->findOneByToken($token);
            if (!$res) {
                return WeHelper::comReturn(null, BackendErrorCode::ERR_OBJECT_NON);
            }
            $this->model = $res;

        } catch (\Exception $e) {
            return WeHelper::comReturn(null, ErrorCode::ERR_DB);
        }
        return true;
    }

    public function checkIdentity()
    {
        try {
            if ($this->model->is_super_admin !== Admin::SUPER_ADMIN_YES) {
                return WeHelper::comReturn(null, BackendErrorCode::ERR_IDENTITY);
            }
        } catch (\Exception $e) {
            return WeHelper::comReturn(null, ErrorCode::ERR_DB);
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
            $get = Param::setNull(['accounts', 'username', 'mobile', 'email', 'is_lock'], $get);
            $res = $this->model->getList($get);
            return WeHelper::comReturn($res, ErrorCode::ERR_SUCCESS);
        } catch (\Exception $e) {
            return WeHelper::comReturn(null, ErrorCode::ERR_DB);
        }
        return $res;
    }


    public function login()
    {
        $post = Yii::$app->request->post();
        try {
            $res = Param::checkParams(['accounts', 'password'], $post);
            if ($res !== true) {
                return WeHelper::comReturn($res, BackendErrorCode::ERR_PARAM_LOSE);
            }

            $res = $this->model->findOneByAccounts($post['accounts']);

            if (!$res) {
                return WeHelper::comReturn(null, BackendErrorCode::ERR_LOGIN_FALSE);
            }

            $this->model = $res;
            /*核对密码*/
            if ($res->password != $this->model->setPassword($post['password'])) {
                return WeHelper::comReturn(null, BackendErrorCode::ERR_LOGIN_FALSE);
            }

            //是否冻结
            if ($res->is_lock !== Admin::LOCK_NOT) {
                return WeHelper::comReturn(null, BackendErrorCode::ERR_USER_LOCKED);
            }

            //是否删除
            if ($res->is_delete !== Admin::DELETE_NOT) {
                return WeHelper::comReturn(null, BackendErrorCode::ERR_USER_DELETED);
            }

            $res->token = $this->model->setToken();
            header('token:' . $res->token);

            $res = $res = $this->model->getView();
            return WeHelper::comReturn($res, ErrorCode::ERR_SUCCESS);
        } catch (\Exception $e) {
            return WeHelper::comReturn(null, ErrorCode::ERR_DB);
        }
    }

    /**
     * 添加数据
     * @param $params
     * @return boolean
     */
    public function create()
    {
        $post = Yii::$app->request->post();
        try {
            $res = Param::checkParams(['password', 'username', 'email', 'mobile'], $post);

            if ($res !== true) {
                return WeHelper::comReturn($res, BackendErrorCode::ERR_PARAM_LOSE);
            }
            $res = $this->model->create($post);

            if ($res === true) {
                return WeHelper::comReturn(null, ErrorCode::ERR_SUCCESS);
            } else {
                return WeHelper::comReturn($res, ErrorCode::ERR_MODEL_VALIDATE);
            }
        } catch (\Exception $e) {
            return WeHelper::comReturn(null, ErrorCode::ERR_DB);
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
        $post = Yii::$app->request->post();
        $get = Yii::$app->request->get();
        try {

            $res = $this->model->findOneById($get['id']);
            if (!$res) {
                return WeHelper::comReturn($res, BackendErrorCode::ERR_OBJECT_NON);
            }
            $this->model = $res;

            if ($this->model->modify($post) !== true) {
                return WeHelper::comReturn(null, BackendErrorCode::ERR_MODEL_VALIDATE);
            }
            return WeHelper::comReturn(null, BackendErrorCode::ERR_SUCCESS);

        } catch (\Exception $e) {
            return WeHelper::comReturn(null, ErrorCode::ERR_DB);
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
                return WeHelper::comReturn($res, BackendErrorCode::ERR_OBJECT_NON);
            }

            $this->model = $res;

            $res = $this->model->getView();

            return WeHelper::comReturn($res, BackendErrorCode::ERR_SUCCESS);

        } catch (\Exception $e) {
            return WeHelper::comReturn(null, ErrorCode::ERR_DB);
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
                return WeHelper::comReturn($res, BackendErrorCode::ERR_OBJECT_NON);
            }
            $this->model = $res;
            if ($this->model->del() !== true) {
                return WeHelper::comReturn(null, BackendErrorCode::ERR_MODEL_VALIDATE);
            }
            return WeHelper::comReturn(null, BackendErrorCode::ERR_SUCCESS);

        } catch (\Exception $e) {
            return WeHelper::comReturn(null, ErrorCode::ERR_DB);
        }
        return true;
    }


    /**
     * 冻结
     * @return array|bool|string
     */
    public function lock()
    {
        $get = Yii::$app->request->get();
        try {
            $res = $this->model->findOneById($get['id']);
            if (!$res) {
                return WeHelper::comReturn($res, BackendErrorCode::ERR_OBJECT_NON);
            }
            $this->model = $res;

            if ($this->model->lock() !== true) {
                return WeHelper::comReturn(null, BackendErrorCode::ERR_MODEL_VALIDATE);
            }
            return WeHelper::comReturn(null, BackendErrorCode::ERR_SUCCESS);

        } catch (\Exception $e) {
            return WeHelper::comReturn(null, ErrorCode::ERR_DB);
        }
        return true;
    }


    /**
     * 解冻冻结
     * @return array|bool|string
     */
    public function unlock()
    {
        $get = Yii::$app->request->get();
        try {
            $res = $this->model->findOneById($get['id']);
            if (!$res) {
                return WeHelper::comReturn($res, BackendErrorCode::ERR_OBJECT_NON);
            }
            $this->model = $res;

            if ($this->model->unlock() !== true) {
                return WeHelper::comReturn(null, BackendErrorCode::ERR_MODEL_VALIDATE);
            }
            return WeHelper::comReturn(null, BackendErrorCode::ERR_SUCCESS);

        } catch (\Exception $e) {
            return WeHelper::comReturn(null, ErrorCode::ERR_DB);
        }
        return true;
    }


}
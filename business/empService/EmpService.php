<?php
/**
 * Created by PhpStorm.
 * User: FOCUS
 * Date: 2018/12/19
 * Time: 14:57
 */

namespace business\empService;

use backend\models\Emp;
use business\interfaceService\emp\IEmpService;
use common\helpers\ConstantHelper;
use common\helpers\Param;
use common\helpers\BackendErrorCode;
use common\helpers\ErrorCode;
use common\helpers\WeHelper;
use Yii;

class EmpService implements IEmpService
{
    private $model;

    public function __construct()
    {
        $this->model = new Emp();
    }

    /**
     * 检查登陆token 除了登陆login接口
     * @return array|bool|string
     */
    public function checkToken()
    {
        $token = Yii::$app->request->headers->get('token');
        try {
            if (empty($token)) {
                $token = Yii::$app->request->get('token');
            }
            if (empty($token)) {
                return WeHelper::jsonReturn(['token'], BackendErrorCode::ERR_PARAM_LOSE);
            }
            $res = $this->model->findOneByToken($token);
            if ($res === false) {
                return WeHelper::jsonReturn(null, BackendErrorCode::ERR_TOKEN_OUT_TIME);
            }
            $this->model = $res;
            //冻结或删除账号停用
            if ($this->model->is_delete === ConstantHelper::IS_DELETE_TRUE) {
                return WeHelper::jsonReturn(null, BackendErrorCode::ERR_INVALID_ACCOUNTS);
            }
            if ($this->model->is_lock === ConstantHelper::IS_LOCK_TRUE) {
                return WeHelper::jsonReturn(null, BackendErrorCode::ERR_LOCKED_ACCOUNTS);
            }
            return true;
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return true;
    }

    /**
     * 检查用户是否是超级管理员
     * @return array|bool|string
     */
    public function checkIdentity()
    {
        try {
            if ($this->model->is_admin !== Emp::SUPER_ADMIN_YES) {
                return WeHelper::jsonReturn(null, BackendErrorCode::ERR_IDENTITY);
            }
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return true;
    }


    /**
     * 修改密码
     * @return array|bool|string
     */
    public function changePassword()
    {
        $get = Yii::$app->request->get();
        $post = Yii::$app->request->post();
        try {
            $post = Param::setNull(['oldpassword', 'password'], $post);
            $res = $this->model->changePassword($post);
            if ($res === true) {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_SUCCESS);
            } elseif (is_array($res)) {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_MODEL_VALIDATE);
            }
        } catch (\Exception $e) {
            return WeHelper::jsonReturn([$e->getMessage()], BackendErrorCode::ERR_DB);
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
            $get = Param::setNull(['job_number', 'username', 'mobile', 'email', 'is_lock'], $get);
            $res = $this->model->getList($get);
            return WeHelper::jsonReturn($res, BackendErrorCode::ERR_SUCCESS);
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return $res;
    }


    /**
     * 登陆
     *
     * @param $params
     * @return array|string
     */
    public function login()
    {
        $post = Param::getParam();
        try {
            $res = Param::checkParams(['job_number', 'password'], $post);
            if ($res !== true) {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_PARAM_LOSE);
            }
            $res = $this->model->findOneByjob_number($post['job_number']);
            if (!$res) {
                return WeHelper::jsonReturn(null, BackendErrorCode::ERR_LOGIN_FALSE);
            }
            if ($res->password != $res->setPassword($post['password'])) {
                $res->addError('password', BackendErrorCode::ERR_LOGIN_FALSE);
                return WeHelper::jsonReturn(null, BackendErrorCode::ERR_LOGIN_FALSE);
            }
            if ($res->is_lock !== Emp::LOCK_NOT) {
                $res->addError('password', BackendErrorCode::ERR_LOGIN_FALSE);
                return WeHelper::jsonReturn(null, BackendErrorCode::ERR_LOGIN_FALSE);
            }
            if ($res->is_delete !== Emp::DELETE_NOT) {
                $res->addError('password', BackendErrorCode::ERR_LOGIN_FALSE);
                return WeHelper::jsonReturn(null, BackendErrorCode::ERR_LOGIN_FALSE);
            }
            $this->model = $res;
            $res->token = $this->model->setToken();
            $auth=$res->auth;
            return WeHelper::jsonReturn(array_merge($this->model->getView(), ['token' => $res->token,'auth'=>$auth]), ErrorCode::ERR_SUCCESS);
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
    }

    /**
     * 添加数据
     * @return array|bool|null
     * @author von
     */
    public function create()
    {
        $post = Param::getParam();
        try {
            $res = $this->model->createEmp($post);
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

            $res = $this->model->modifyEmp($post);
            if ($res=== true) {
                return WeHelper::jsonReturn(null, BackendErrorCode::ERR_SUCCESS);
            } elseif (is_array($res)) {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_MODEL_VALIDATE);
            }

        } catch (\Exception $e) {
            Yii::warning($e->getMessage());
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return true;
    }

    /**
     * 数据详情
     * @return array|bool|null
     * @author von
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
            $res = $this->model->getDetail();
            return WeHelper::jsonReturn($res, BackendErrorCode::ERR_SUCCESS);

        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return true;
    }

    /**
     * 删除数据
     * @return array|bool|null
     *
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
            if ($this->model->del() !== true) {
                return WeHelper::jsonReturn(null, BackendErrorCode::ERR_MODEL_VALIDATE);
            }
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_SUCCESS);

        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
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
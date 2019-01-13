<?php
/**
 **********************************************************
 * @author : YiChaobao [ yichaobao@163.com ]
 * @time : 2019/01/01 00:00
 * @copyright : (c) 2019 YiChaobao All rights reserved.
 **********************************************************
 * @name : UserService.php
 * @description : 用户服务
 **/

namespace business\userService;

use backend\models\User;
use business\interfaceService\user\IUserService;
use common\helpers\Param;
use common\helpers\BackendErrorCode;
use common\helpers\ErrorCode;
use common\helpers\WeHelper;
use Yii;

class UserService implements IUserService
{
    private $model;

    public function __construct()
    {
        $this->model = new User();
    }

    /**
     * @desc:创建
     * @name: addUser
     * @param array $data
     * @return array|string
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
    public function addUser($data = [])
    {
        try {
            $res = $this->model->create_data($data);
            if ($res['status'] === true) {
                $return_data = WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_SUCCESS);
            } else {
                $return_data = WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_MODEL_VALIDATE);
            }
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return $return_data;

    }

    /**
     * @desc: 详情
     * @name: getUserInfo
     * @param string $user_id
     * @return array|string
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
    public function getUserInfo($user_id = '')
    {
        try {
            $res = $this->model->info_data($user_id);
            if ($res['status'] === true) {
                $return_data = WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_SUCCESS);
            } else {
                $return_data = WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_MODEL_VALIDATE);
            }
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return $return_data;
    }

    /**
     * @desc: 更新
     * @name: updateUser
     * @param null $user_id
     * @param array $data
     * @return array|string
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
    public function updateUser($user_id = null, $data = [])
    {
        try {
            $res = $this->model->update_data($user_id, $data);
            if ($res['status'] === true) {
                $return_data = WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_SUCCESS);
            } else {
                $return_data = WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_MODEL_VALIDATE);
            }
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return $return_data;
    }


    /**
     * @desc: 删除
     * @name: delUser
     * @param null $user_id
     * @return array|string
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
    public function delUser($user_id = null)
    {
        try {
            $res = $this->model->delete_data($user_id);
            if ($res['status'] === true) {
                $return_data = WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_SUCCESS);
            } else {
                $return_data = WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_MODEL_VALIDATE);
            }
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return $return_data;
    }

    /**
     * @desc: 列表
     * @name: getUserLists
     * @param array $where 条件
     * @param string $order 排序
     * @param int $pageSize 分页数
     * @return array|string
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
    public function getUserLists($where = [],$order = 'created_at desc',$pageSize = 20)
    {
        try {
            $res = $this->model->lists_data($where,$order,$pageSize);
            return WeHelper::jsonReturn($res, BackendErrorCode::ERR_SUCCESS);
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
    }

    /**
     * @desc: 用户锁
     * @name: setUserLockStatus
     * @param $user_id
     * @param $lock
     * @return array|string
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
    public function setUserLockStatus($user_id,$lock){
        try {
            $res = $this->model->lock($user_id,$lock);
            if ($res['status'] === true) {
                $return_data = WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_SUCCESS);
            } else {
                $return_data = WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_MODEL_VALIDATE);
            }
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return $return_data;
    }
}
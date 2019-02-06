<?php
/**
 **********************************************************
 * @author : YiChaobao [ yichaobao@163.com ]
 * @time : 2019/01/14 10:09
 * @copyright : (c) 2019 YiChaobao All rights reserved.
 **********************************************************
 * @name : RoleService.php
 * @description : 用户角色服务
 **/
namespace business\userService;
use business\interfaceService\user\IRoleService;
use backend\models\UserRole;
use common\helpers\WeHelper;
use common\helpers\BackendErrorCode;
class RoleService implements IRoleService
{
    private $model;
    public function __construct()
    {
        $this->model = new UserRole();
    }

    /**
     * @desc: 添加数据
     * @name: addData
     * @param $data
     * @return array|null
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
    public function addData($data){
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
     * @desc: 获取数据
     * @name: getDataInfo
     * @param string $id
     * @return array|null
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
    public function getDataInfo($id = ''){
        try {
            $res = $this->model->info_data($id);
            if ($res['status'] === true) {
                $return_data = WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_SUCCESS);
            } else {
                $return_data = WeHelper::jsonReturn(null, $res['error_code']);
            }
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return $return_data;
    }

    /**
     * @desc: 更新数据
     * @name: updateData
     * @param null $id
     * @param array $data
     * @return array|null
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
    public function updateData($id = null, $data = []){
//        $res = $this->model->update_data($id, $data);
//        var_dump($res);
//        die();
        try {
            $res = $this->model->update_data($id, $data);
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
     * @desc: 删除数据
     * @name: deleteData
     * @param null $id
     * @return array|null
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
    public function deleteData($id = null){
        try {
            $res = $this->model->delete_data($id);
            if ($res['status'] === true) {
                $return_data = WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_SUCCESS);
            } else {
                $return_data = WeHelper::jsonReturn(null, $res['error_code']);
            }
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return $return_data;
    }

    /**
     * @desc: 获取数据列表
     * @name: getLists
     * @return array|null
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
    public function getLists()
    {
        try {
            $res = $this->model->lists_data();
            return WeHelper::jsonReturn($res, BackendErrorCode::ERR_SUCCESS);
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
    }
}
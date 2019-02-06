<?php
/**
 **********************************************************
 * @author : YiChaobao [ yichaobao@163.com ]
 * @time : 2019/01/14 14:59
 * @copyright : (c) 2019 YiChaobao All rights reserved.
 **********************************************************
 * @name : CertService.php
 * @description :
 **/

namespace business\userService;
use backend\models\UserCertRecord;
use business\interfaceService\user\ICertService;
use backend\models\UserCert;
use common\helpers\WeHelper;
use common\helpers\BackendErrorCode;
class CertService implements ICertService
{
    private $model;
    public function __construct()
    {
        $this->model = new UserCert();
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
        try {
            $res = $this->model->update_data($id, $data);
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
    public function getLists($where = [], $order = 'created_at DESC', $pageSize = 20)
    {
        try {
            $res = $this->model->lists_data($where,$order,$pageSize);
            return WeHelper::jsonReturn($res, BackendErrorCode::ERR_SUCCESS);
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
    }

    public function getLogLists($id){
        try {
            $ucrModel = new UserCertRecord();
            $res = $ucrModel->getLists($id);
            return WeHelper::jsonReturn($res, BackendErrorCode::ERR_SUCCESS);
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
    }
}
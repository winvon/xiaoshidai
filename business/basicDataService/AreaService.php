<?php
/**
 **********************************************************
 * @author : YiChaobao [ yichaobao@163.com ]
 * @time : 2019/01/01 00:00
 * @copyright : (c) 2019 YiChaobao All rights reserved.
 **********************************************************
 * @name : AreaService.php
 * @description : 地区服务
 **/
namespace business\basicDataService;
use backend\models\Area;
use business\interfaceService\area\IAreaService;
use common\helpers\Param;
use common\helpers\BackendErrorCode;
use common\helpers\ErrorCode;
use common\helpers\WeHelper;
use Yii;
class AreaService implements IAreaService
{
    private $model;

    public function __construct()
    {
        $this->model = new Area();
    }

    public function getAllData(){
        try {
            $res = $this->model->getAllTree();
            return WeHelper::jsonReturn($res, BackendErrorCode::ERR_SUCCESS);
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
    }
}
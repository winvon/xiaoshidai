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

namespace business\rbacService;

use backend\models\Rbac;
use business\interfaceService\rbac\IRbacService;
use common\helpers\ErrorCode;
use common\helpers\Param;
use common\helpers\WeHelper;
use Yii;

class RbacService implements IRbacService
{
    private $model;

    public function __construct()
    {
        $this->model = new Rbac();
    }


    public function permissions()
    {
        $get = Yii::$app->request->get();
        try {
            $get = Param::setNull(['accounts'], $get);
            $res = $this->model->getPermissionsByGroup($get);
            return WeHelper::jsonReturn($res, ErrorCode::ERR_SUCCESS);
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, ErrorCode::ERR_DB);
        }
        return $res;
    }

    public function roles()
    {

    }
}
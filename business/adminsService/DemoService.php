<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/18
 * Time: 14:30
 */

namespace business\adminsService;
use backend\models\Admin;
use business\interfaceService\admin\IDemoService;
use common\helpers\ErrorCode;
use common\helpers\WeHelper;

class DemoService implements IDemoService
{
    private $model;

    public function __construct()
    {
        $this->model = new Admin();
    }

    public function getAdminList()
    {
        try {
            $res = $this->model->getAdminsList();
        } catch(\Exception $e){
            return WeHelper::comReturn(null,ErrorCode::ERR_DB);
        }
        return $res;
    }
}
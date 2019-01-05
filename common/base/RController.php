<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/18
 * Time: 14:18
 */

namespace common\base;

use common\helpers\BackendErrorCode;
use common\helpers\Param;
use common\helpers\WeHelper;
use yii\rest\ActiveController;
use yii\helpers\Json;
use yii;

class RController extends ActiveController
{
    public $enableCsrfValidation = false;

    public $modelClass = 'backend\models\Admin';

    public $except_token = ['login', 'options', 'csrftoken','upload-img'];

    public $check_form_token_route = ['create'];

    public function beforeAction($action)
    {
        /*token检查*/
        if (!in_array($action->id, $this->except_token)) {
            $service = $this->getService('Admin.Admin');
            $res = $service->checkToken();
            if (is_array($res)) {
                header("Content-Type:application/json;charset=UTF-8");
                echo Json::encode($res);
                exit();
            }
        }

        if (parent::beforeAction($action)) {
            if ($this->enableCsrfValidation) {
                Yii::$app->getRequest()->getCsrfToken(true);
            }
            return true;
        };

        if (Yii::$app->request->getMethod()=="OPTIONS"){
            header('Access-Control-Allow-Origin:*');
            header('Access-Control-Allow-Headers:*');
            header('Access-Control-Allow-Methods: *');
            header('Access-Control-Allow-Credentials:true');
            exit();
        }
    }

    public function actionOptions()
    {
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Headers:*');
        header('Access-Control-Allow-Methods: *');
        header('Access-Control-Allow-Credentials:true');
        exit();
    }

    /**
     * 获取服务接口
     * @param $servName
     * @return object
     */
    protected function getService($servName, $proxied = false)
    {
        header("Access-Control-Allow-Origin: *");
        $serv = WeHelper::getService($servName);
        if ($proxied) {
            $servProxy = new ServiceProxy($serv, $this->view);
            $serv = $servProxy;
        }
        return $serv;
    }
}
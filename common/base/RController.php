<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/18
 * Time: 14:18
 */

namespace common\base;

use backend\filter\AuthFilter;
use common\helpers\BackendErrorCode;
use common\helpers\Param;
use common\helpers\WeHelper;
use yii\rest\ActiveController;
use yii\helpers\Json;
use yii;

class RController extends ActiveController
{
    public $enableCsrfValidation = false;

    public $modelClass = 'backend\models\Emp';

    /**不检查token的方法**/
    public $except_token = ['login', 'options', 'csrftoken','upload-img','upload'];

    /**检查_csrfToken的方法**/
    public $check_form_token_route = ['create'];

//    public function behaviors()
//    {
//       return array_merge(parent::behaviors(),
//            [
//                'authFilter'=>AuthFilter::className(),
//            ]) ; // TODO: Change the autogenerated stub
//    }

    /**
     * @param yii\base\Action $action
     * @return bool
     * @author von
     */
    public function beforeAction($action)
    {
        if (Yii::$app->request->getMethod()=="OPTIONS"){
            self::setHeader();
            exit();
        }
        /**token检查**/
//        if (!in_array($action->id, $this->except_token)) {
//            $service = $this->getService('Emp.Emp');
//            $res = $service->checkToken();
//            if (is_array($res)) {
//                header("Content-Type:application/json;charset=UTF-8");
//                echo Json::encode($res);
//                exit();
//            }
//        }
        /**表单提交验证**/
        if (parent::beforeAction($action)) {
            if ($this->enableCsrfValidation) {
                Yii::$app->getRequest()->getCsrfToken(true);
            }
            return true;
        };
    }

    /**
     * 设置header头部
     * @author von
     */
    public function setHeader(){
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Headers:*');
        header('Access-Control-Allow-Methods: *');
        header('Access-Control-Allow-Credentials:true');
        return true;
    }

    /**
     * 预请求
     * @author von
     */
    public function actionOptions()
    {
        self::setHeader();
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
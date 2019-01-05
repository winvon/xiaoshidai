<?php
/**
 * Created by PhpStorm.
 * User: FOCUS
 * Date: 2018/12/18
 * Time: 18:17
 */

namespace app\modules\test\controllers;

use common\base\Controller;
use Yii;

class AdminController extends Controller
{
    public function actionLogin()
    {
        $service = $this->getService('Admins.Demo');
        $res = $service->login();
    }



    public function actionUpdate()
    {

    }


}
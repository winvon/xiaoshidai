<?php
/**
 * Created by PhpStorm.
 * User: FOCUS
 * Date: 2018/12/27
 * Time: 20:55
 */

namespace backend\controllers;

use backend\models\forms\UploadForm;
use common\base\RController;
use common\helpers\BackendErrorCode;
use common\helpers\ErrorCode;
use common\helpers\Param;
use common\helpers\Upload;
use common\helpers\WeHelper;
use Yii;
use yii\web\UploadedFile;

class PublicController extends RController
{
    public function actions()
    {
        $action = parent::actions();
        unset($action['index']);
        unset($action['update']);
        unset($action['create']);
        unset($action['delete']);
        return $action;
    }

    public function actionCsrftoken()
    {
        $csrfToken = \Yii::$app->request->csrfToken;
        return WeHelper::jsonReturn(['_csrf-backend' => $csrfToken], ErrorCode::ERR_SUCCESS);
    }

    public function actionUploadImg()
    {
        $post = \Yii::$app->request->post();
        $type = \Yii::$app->request->get('type');
        $post['file'] = !empty($_FILES['file']) ? $_FILES['file'] : '';
        $res = Param::checkParams(['file'], $post);
        if ($res !== true) {
            return WeHelper::jsonReturn($res, BackendErrorCode::ERR_PARAM_LOSE);
        }
        $file = $post['file'];
        switch ((int)$type) {
            case 1:
                $file_name = Upload::uploadByFile($file, 'profile');
                break;
            case 2 :
                $file_name = Upload::uploadByFile($file, 'banner');
                break;
            case 3 :
                $file_name = Upload::uploadByFile($file, 'goods');
                break;
            default:
                $file_name = false;
                break;
        }
        if ($file_name != false) {
            return WeHelper::jsonReturn(['file_name' => $file_name], BackendErrorCode::ERR_SUCCESS);
        }
        return WeHelper::jsonReturn(null, BackendErrorCode::ERR_REQUEST_FAILED);
    }
}
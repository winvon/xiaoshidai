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
        return [];
    }

    /**
     * @return array|null
     * @author von
     */
    public function actionCsrftoken()
    {
        $csrfToken = \Yii::$app->request->csrfToken;
        return WeHelper::jsonReturn(['_csrf-backend' => $csrfToken], ErrorCode::ERR_SUCCESS);
    }

    /**
     * @return array|null
     * @author von
     */
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

    /**
     * 上传文件
     * @param $type
     * @return array|null
     * @author von
     */
    public function actionUpload()
    {
        /**检查请求参数**/
        $post['file'] = !empty($_FILES['file']) ? $_FILES['file'] : '';
        $res = Param::checkParams(['file'], $post);
        if ($res !== true) {
            return WeHelper::jsonReturn($res, BackendErrorCode::ERR_PARAM_LOSE);
        }
        /**保存文件**/
        try {
            $upload = new Upload($post['file']);
            $res = $upload->save();
            if (is_array($res)) {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_REQUEST_FAILED);
            }
            return WeHelper::jsonReturn(['file_name' => $res], BackendErrorCode::ERR_SUCCESS);
        } catch (\Exception $e) {
            return WeHelper::jsonReturn([$e->getMessage()], BackendErrorCode::ERR_DB);
        }
    }


    /**
     * 测试
     * @author von
     */
    public function actionTest()
    {

        $redis = Yii::$app->redis;
        $list_name='miaosha';
        $num=500;
        while ($redis->llen($list_name)>0){
            echo $redis->lpop($list_name).'秒杀成功用户<br>';
        }
        die;
    }


    public function getFileLines($filename, $startLine = 1, $endLine = 50, $method = 'rb')
    {
        $content = array();
        $count = $endLine - $startLine;
        // 判断php版本（因为要用到SplFileObject，PHP>=5.1.0）
        if (version_compare(PHP_VERSION, '5.1.0', '>=')) {
            $fp = new \SplFileObject($filename, $method);
            $fp->seek($startLine - 1);// 转到第N行, seek方法参数从0开始计数
            for ($i = 0; $i <= $count; ++$i) {
                $content[] = $fp->current();// current()获取当前行内容
                $fp->next();// 下一行
            }
        } else {//PHP<5.1
            $fp = fopen($filename, $method);
            if (!$fp) return 'error:can not read file';
            for ($i = 1; $i < $startLine; ++$i) {// 跳过前$startLine行
                fgets($fp);
            }
            for ($i; $i <= $endLine; ++$i) {
                $content[] = fgets($fp);// 读取文件行内容
            }
            fclose($fp);
        }
        return array_filter($content); // array_filter过滤：false,null,''
    }


    public static function rightOneToFirst($array, $n)
    {
        $row = [];
        $count = count($array); //
        for ($i = 0; $i < $count; $i++) {
            if ((int)$i < (int)$n) {//移动的位置作为条件判断
                $row[] = $array[$count - $i - $n];
            } else {
                $row[] = $array[$i - $n];
            };
        }
        return $row;
    }

    /**
     * 前往后移动
     * @param $array
     * @param $n
     * @return array
     * @author von
     */
    public static function leftOneToLast($array, $n)
    {
        $row = [];
        for ($i = 0; $i < count($array); $i++) {
            if (($n + $i) < count($array)) {//  7
                $row[] = $array[$n + $i];
            } else {
                $row[] = $array[($n + $i) - count($array)];
            }
        }
        return $row;
    }

}
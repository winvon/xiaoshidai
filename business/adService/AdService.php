<?php
/**
 * Created by PhpStorm.
 * User: FOCUS
 * Date: 2018/12/19
 * Time: 14:57
 */

namespace business\adService;

use backend\models\Ad;
use business\interfaceService\admin\IBannerService;
use common\helpers\Param;
use common\helpers\BackendErrorCode;
use common\helpers\ErrorCode;
use common\helpers\WeHelper;
use Yii;
class AdService implements IBannerService
{
    private $model;

    public function __construct()
    {
        $this->model = new Ad();
    }

    /**
     * 获取列表
     * @param $params
     * @return mixed
     */
    public function getList()
    {
        $get = Yii::$app->request->get();
        try {
            $get = Param::setNull(['ad_name', 'source','type','size'], $get);
            $res = $this->model->getList($get);
            return WeHelper::jsonReturn($res, BackendErrorCode::ERR_SUCCESS);
        } catch (\Exception $e) {
            Yii::warning( $e->getMessage());
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return $res;
    }

    /**
     * 新增广告渠道
     * @param $params
     * @return boolean
     */
    public function create()
    {
        $post =Param::getParam();
        try {
            $res = $this->model->createAd($post);
            if ($res === true) {
                return WeHelper::jsonReturn(null, BackendErrorCode::ERR_SUCCESS);
            } else {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_MODEL_VALIDATE);
            }
        } catch (\Exception $e) {
            Yii::warning($e->getMessage());
            return WeHelper::jsonReturn( null, BackendErrorCode::ERR_DB);
        }
        return true;

    }

    /**
     * 修改数据
     * @param $params
     * @return mixed
     */
    public function update()
    {
        $post =Param::getParam();
        $get = Yii::$app->request->get();
        try {
            $res = $this->model->findOneById($get['id']);
            if (!$res) {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_OBJECT_NON);
            }
            $this->model = $res;
            $res=$this->model->modifyAd($post);
            if ($res !== true) {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_MODEL_VALIDATE);
            }
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_SUCCESS);

        } catch (\Exception $e) {
            Yii::warning($e->getMessage());
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return true;
    }

    /**
     * 数据详情
     * @param $params
     * @return boolean
     */
    public function view()
    {
        $get = Yii::$app->request->get();
        try {

            $res = $this->model->findOneById($get['id']);
            if (!$res) {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_OBJECT_NON);
            }

            $this->model = $res;

            $res = $this->model->getView();
            return WeHelper::jsonReturn($res, BackendErrorCode::ERR_SUCCESS);

        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return true;
    }

    /**
     * 删除数据
     * @param $params
     * @return boolean
     */
    public function delete()
    {
        $get = Yii::$app->request->get();
        try {
            $res = $this->model->findOneById($get['id']);
            if (!$res) {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_OBJECT_NON);
            }
            $this->model = $res;
            $res=$this->model->del();
            if ($res === true) {
                return WeHelper::jsonReturn(null, BackendErrorCode::ERR_SUCCESS);
            } elseif ($res=== false) {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_DELETE);
            } else {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_MODEL_VALIDATE);
            }
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return true;
    }

    /**
     * @return array|bool|null
     * @author von
     */
    public function show()
    {
        $get = Yii::$app->request->get();
        try {
            $res = $this->model->findOneById($get['id']);
            if (!$res) {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_OBJECT_NON);
            }
            $this->model = $res;
            $res=$this->model->showById();
            if ($res === true) {
                return WeHelper::jsonReturn(null, BackendErrorCode::ERR_SUCCESS);
            } elseif ($res=== false) {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_DELETE);
            } else {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_MODEL_VALIDATE);
            }
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return true;
    }


}
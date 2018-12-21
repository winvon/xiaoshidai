<?php
/**
 * Created by PhpStorm.
 * User: FOCUS
 * Date: 2018/12/19
 * Time: 14:57
 */

namespace business\adminsService;

use backend\models\BannerItem;
use business\interfaceService\admin\IBannerService;
use common\helpers\Param;
use common\helpers\BackendErrorCode;
use common\helpers\ErrorCode;
use common\helpers\WeHelper;
use Yii;

class BannerItemService implements IBannerService
{
    private $model;

    public function __construct()
    {
        $this->model = new BannerItem();
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
            $get = Param::setNull(['banner_name', 'source'], $get);
            $res = $this->model->getList($get);
            return WeHelper::comReturn($res, ErrorCode::ERR_SUCCESS);
        } catch (\Exception $e) {
            return WeHelper::comReturn(null, ErrorCode::ERR_DB);
        }
        return $res;
    }

    /**
     * 添加数据
     * @param $params
     * @return boolean
     */
    public function create()
    {
        $post = Yii::$app->request->post();
        try {
            $res = $this->model->create($post);
            if ($res === true) {
                return WeHelper::comReturn(null, ErrorCode::ERR_SUCCESS);
            } else {
                return WeHelper::comReturn($res, ErrorCode::ERR_MODEL_VALIDATE);
            }
        } catch (\Exception $e) {
            return WeHelper::comReturn(null, ErrorCode::ERR_DB);
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
        $post = Yii::$app->request->post();
        $get = Yii::$app->request->get();
        try {

            $res = $this->model->findOneById($get['id']);
            if (!$res) {
                return WeHelper::comReturn($res, BackendErrorCode::ERR_OBJECT_NON);
            }
            $this->model = $res;

            if ($this->model->modify($post) !== true) {
                return WeHelper::comReturn(null, BackendErrorCode::ERR_MODEL_VALIDATE);
            }
            return WeHelper::comReturn(null, BackendErrorCode::ERR_SUCCESS);

        } catch (\Exception $e) {
            return WeHelper::comReturn(null, ErrorCode::ERR_DB);
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
                return WeHelper::comReturn($res, BackendErrorCode::ERR_OBJECT_NON);
            }

            $this->model = $res;

            $res=$this->model->getView();

            return WeHelper::comReturn($res, BackendErrorCode::ERR_SUCCESS);

        } catch (\Exception $e) {
            return WeHelper::comReturn(null, ErrorCode::ERR_DB);
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
                return WeHelper::comReturn($res, BackendErrorCode::ERR_OBJECT_NON);
            }
            $this->model = $res;
            if ($this->model->del() !== true) {
                return WeHelper::comReturn(null, BackendErrorCode::ERR_MODEL_VALIDATE);
            }
            return WeHelper::comReturn(null, BackendErrorCode::ERR_SUCCESS);
        } catch (\Exception $e) {
            return WeHelper::comReturn(null, ErrorCode::ERR_DB);
        }
        return true;
    }


}
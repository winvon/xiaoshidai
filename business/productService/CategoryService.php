<?php
/**
 * Created by PhpStorm.
 * User: FOCUS
 * Date: 2018/12/19
 * Time: 14:57
 */

namespace business\productService;

use backend\models\Category;
use business\interfaceService\product\ICategoryService;
use common\helpers\Param;
use common\helpers\BackendErrorCode;
use common\helpers\ErrorCode;
use common\helpers\WeHelper;
use Yii;
use yii\helpers\Json;

class CategoryService implements ICategoryService
{
    private $model;

    public function __construct()
    {
        $this->model = new Category();
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
            $get = Param::setNull(['category_name','parent_id'], $get);
            $res = $this->model->getList($get);
            return WeHelper::jsonReturn($res, BackendErrorCode::ERR_SUCCESS);
        } catch (\Exception $e) {
            return $e->getMessage();
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return $res;
    }

    /**
     * 获取列表
     * @return array|null|string
     *
     * @author von
     */
    public function getListByTree()
    {
        $get = Yii::$app->request->get();
        try {
            $res = $this->model->getListByTree($get);
            return WeHelper::jsonReturn($res, BackendErrorCode::ERR_SUCCESS);
        } catch (\Exception $e) {
            return $e->getMessage();
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
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
        $post = Param::getParam();
        try {
            $res = $this->model->createCategory($post);
            if ($res === true) {
                return WeHelper::jsonReturn(null, BackendErrorCode::ERR_SUCCESS);
            } else {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_MODEL_VALIDATE);
            }
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
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
        $post = Param::getParam();
        $get = Yii::$app->request->get();
        try {
            $res = $this->model->findOneById($get['id']);
            if (!$res) {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_OBJECT_NON);
            }
            $this->model = $res;
            $res = $this->model->modifyCategory($post);
            if ($res !== true) {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_MODEL_VALIDATE);
            }
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_SUCCESS);

        } catch (\Exception $e) {
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

            if ($this->model->del() === true) {
                return WeHelper::jsonReturn(null, BackendErrorCode::ERR_SUCCESS);
            } elseif ($this->model->del() === false) {
                return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DELETE);
            } else {
                return WeHelper::jsonReturn(null, BackendErrorCode::ERR_MODEL_VALIDATE);
            }

        } catch (\Exception $e) {
            return $e->getMessage();
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return true;
    }

    /**
     * 显示与隐藏
     * @param $params
     * @return boolean
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

            $res = $this->model->showCategory();
            if ($res === true) {
                return WeHelper::jsonReturn(null, BackendErrorCode::ERR_SUCCESS);
            } else {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_MODEL_VALIDATE);
            }
        } catch (\Exception $e) {
            return WeHelper::jsonReturn([$e->getMessage()], BackendErrorCode::ERR_DB);
        }
        return true;
    }


}
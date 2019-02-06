<?php
/**
 * Created by PhpStorm.
 * User: FOCUS
 * Date: 2018/12/19
 * Time: 14:57
 */

namespace business\productService;

use backend\models\CategoryRelease;
use business\interfaceService\product\ICategoryReleaseService;
use common\helpers\ConstantHelper;
use common\helpers\Param;
use common\helpers\BackendErrorCode;
use common\helpers\WeHelper;
use Yii;

class CategoryReleaseService implements ICategoryReleaseService
{
    private $model;

    public function __construct()
    {
        $this->model = new CategoryRelease();
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
            $get = Param::setNull(['release_name', 'source'], $get);
            $res = $this->model->getList($get);
            return WeHelper::jsonReturn($res, BackendErrorCode::ERR_SUCCESS);
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return true;
    }


    /**
     * 获取详情
     * @param $params
     * @return mixed
     */
    public function view()
    {
        $get = Yii::$app->request->get();
        try {
            $res = $this->model->findOneById($get['id']);
            if (!$res) {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_OBJECT_NON);
            }
            $this->model=$res;
            $res=$this->model->getView();
            $res['detail'] = $this->model->detail();
            return WeHelper::jsonReturn($res, BackendErrorCode::ERR_SUCCESS);
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return true;
    }

    /**
     * @return array|bool|null
     * @author von
     */
    public function create()
    {
        $post = Param::getParam();
        try {
            $res = $this->model->createCategoryRelease($post);
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
            $res = $this->model->modifyCategoryRelease($post);
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
     * 删除数据
     * @return array|bool|null|string
     * @author von
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
            $res = $this->model->del();
            if ($res === true) {
                return WeHelper::jsonReturn(null, BackendErrorCode::ERR_SUCCESS);
            }
            if (is_array($res)) {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_MODEL_VALIDATE);
            }
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return true;
    }

    public function getAppCategoryRelease()
    {
        $source = Yii::$app->request->get('source', ConstantHelper::CATEGORY_RELEASE_SOURCE_ANDROID);
        try {
            $res['type_word'] = $this->model->getAppCategoryRelease(ConstantHelper::CATEGORY_CATEGORY_TYPE_WORD, $source);
            $res['type_icon'] = $this->model->getAppCategoryRelease(ConstantHelper::CATEGORY_CATEGORY_TYPE_ICON, $source);
            if (is_array($res)) {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_SUCCESS);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
    }


}
<?php
/**
 **********************************************************
 * @author : YiChaobao [ yichaobao@163.com ]
 * @time : 2019/01/01 00:00
 * @copyright : (c) 2019 YiChaobao All rights reserved.
 **********************************************************
 * @name : UserService.php
 * @description : 用户服务
 **/

namespace business\rbacService;

use backend\models\Menu;
use business\interfaceService\rbac\IRbacService;
use common\helpers\BackendErrorCode;
use common\helpers\Param;
use common\helpers\WeHelper;
use Yii;

class MenuService implements IRbacService
{
    private $model;

    public function __construct()
    {
        $this->model = new Menu();
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
            $get = Param::setNull(['name', 'url', 'is_display'], $get);
            $res = $this->model->getList($get);
            return WeHelper::jsonReturn($res, BackendErrorCode::ERR_SUCCESS);
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return $res;
    }

    /**
     * 获取菜单树形列表
     * @param $params
     * @return mixed
     */
    public function getMenusName()
    {
        try {
            $res = $this->model->getMenusName();
            return WeHelper::jsonReturn($res, BackendErrorCode::ERR_SUCCESS);
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return $res;
    }

    /**
     * 新增菜单
     * @return array|bool|null
     * @author von
     */
    public function create()
    {
        $post = Param::getParam();
        try {
            $res = $this->model->createMenu($post);
            if ($res === true) {
                return WeHelper::jsonReturn(null, BackendErrorCode::ERR_SUCCESS);
            } else {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_MODEL_VALIDATE);
            }
        } catch (\Exception $e) {
            Yii::warning($e->getMessage());
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
            $res = $this->model->modifyMenu($post);
            if ($res !== true) {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_MODEL_VALIDATE);
            }
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_SUCCESS);

        } catch (\Exception $e) {
            return $e->getMessage();
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return true;
    }

    /**
     * @return array|bool|null
     * @author von
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
    }

    /**
     * @return array|bool|null
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
            $res = $this->model->delById();
            if ($res === true) {
                return WeHelper::jsonReturn(null, BackendErrorCode::ERR_SUCCESS);
            } elseif ($res === false) {
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
     * 是否显示
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
            $res = $this->model->setMenuShowById();
            if ($res === true) {
                return WeHelper::jsonReturn(null, BackendErrorCode::ERR_SUCCESS);
            } elseif ($res === false) {
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
     * 修改排序
     * @return array|bool|null
     * @author von
     */
    public function sort()
    {
        $post = Param::getParam();
        $get = Yii::$app->request->get();
        try {
            $res = $this->model->findOneById($get['id']);
            if (!$res) {
                return WeHelper::jsonReturn($res, BackendErrorCode::ERR_OBJECT_NON);
            }
            $this->model = $res;
            $res = $this->model->setMenuSortById($post);
            if ($res === true) {
                return WeHelper::jsonReturn(null, BackendErrorCode::ERR_SUCCESS);
            } elseif ($res === false) {
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
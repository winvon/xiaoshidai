<?php
/**
 * Created by von.
 * User: FOCUS
 * Date: 2019/1/9
 * Time: 11:21
 */

namespace backend\modules\rbac\controllers;

use common\base\RController;

class RoleController extends RController
{
    public function actions()
    {
        return []; // TODO: Change the autogenerated stub
    }

    /**
     * 菜单列表
     * @return mixed 
     * @author von
     */
    public function actionIndex()
    {
        $service = $this->getService('Rbac.Role');
        return $service->getList();
    }

    /**
     * 获取菜单树形结构
     * @return mixed
     * @author von
     */
    public function actionGetMenusName()
    {
        $service = $this->getService('Rbac.Role');
        return $service->getMenusName();
    }

    /**
     * 菜单详情
     * @return mixed
     */
    public function actionView($id)
    {
        $service = $this->getService('Rbac.Role');
        return $service->view($id);
    }

    /**
     * 创建菜单
     * @return mixed
     */
    public function actionCreate()
    {
        $service = $this->getService('Rbac.Role');
        return $service->create();

    }

    /**
     * 修改菜单
     * @return mixed
     */
    public function actionUpdate()
    {
        $service = $this->getService('Rbac.Role');
        return $service->update();
    }

    /**
     * 删除菜单
     * @return mixed
     */
    public function actionDelete()
    {
        $service = $this->getService('Rbac.Role');
        return $service->delete();
    }

    /**
     * 菜单排序
     * @return mixed
     */
    public function actionSort()
    {
        $service = $this->getService('Rbac.Role');
        return $service->sort();
    }

    /**
     * 菜单显示
     * @return mixed
     */
    public function actionShow()
    {
        $service = $this->getService('Rbac.Role');
        return $service->show();
    }

    /**
     * 菜单冻结
     * @return mixed
     */
    public function actionLock()
    {
        $service = $this->getService('Rbac.Role');
        return $service->lock();
    }

    /**
     * 删除菜单
     * @return mixed
     */
    public function actionDisplay()
    {
        $service = $this->getService('Rbac.Role');
        return $service->delete();
    }

}
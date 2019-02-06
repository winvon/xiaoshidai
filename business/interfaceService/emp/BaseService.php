<?php
/**
 * Created by PhpStorm.
 * User: FOCUS
 * Date: 2018/12/21
 * Time: 15:04
 */

namespace business\interfaceService\emp;


interface  BaseService
{
    /**
     * 获取列表
     * @param $params
     * @return mixed
     */
    public function getList();

    /**
     * 添加数据
     * @param $params
     * @return boolean
     */
    public function create();

    /**
     * 修改数据
     * @param $params
     * @return mixed
     */
    public function update();

    /**
     * 数据详情
     * @param $params
     * @return boolean
     */
    public function view();

    /**
     * 删除数据
     * @param $params
     * @return boolean
     */
    public function delete();
}
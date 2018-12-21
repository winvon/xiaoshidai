<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/18
 * Time: 14:32
 */
namespace business\interfaceService\admin;

interface IDemoService {

    /**
     * 获取管理员用户列表
     * @param $params
     * @return mixed
     */
    public function getAdminList();
}
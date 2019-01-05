<?php
/**
 **********************************************************
 * @author : YiChaobao [ yichaobao@163.com ]
 * @time : 2019/01/01 00:00
 * @copyright : (c) 2019 YiChaobao All rights reserved.
 **********************************************************
 * @name : IUserService.php
 * @description :
 **/

namespace business\interfaceService\user;
interface IUserService
{
    public function addUser();

    public function getUserInfo();

    public function updateUser();

    public function delUser();

    public function getUserLists();

//    public function setUserLockStatus();
}
<?php

namespace business;

use common\base\IBusinessInit;
use Yii;

class BusinessInit implements IBusinessInit{

    public function init(){
        $this->AdminsService();
    }

    private function AdminsService(){
        Yii::$container->setSingleton('Admins.DemoService', 'business\adminsService\DemoService');
        Yii::$container->setSingleton('Admins.AdminService', 'business\adminsService\AdminService');
        Yii::$container->setSingleton('Admins.BannerService', 'business\adminsService\BannerService');
        Yii::$container->setSingleton('Admins.BannerItemService', 'business\adminsService\BannerItemService');
    }
}
<?php

namespace business;

use common\base\IBusinessInit;
use Yii;

class BusinessInit implements IBusinessInit
{
    public function init()
    {
        $this->BasicDataService();
        $this->AdminService();
        $this->GoodsService();
        $this->UserService();
        $this->CouponService();
        $this->AdService();
        $this->RbacService();
    }

    private function BasicDataService()
    {
        Yii::$container->setSingleton('Basic.AreaService', 'business\basicDataService\AreaService');
    }

    private function AdminService()
    {
        Yii::$container->setSingleton('Admin.DemoService', 'business\adminService\DemoService');
        Yii::$container->setSingleton('Admin.AdminService', 'business\adminService\AdminService');
    }

    private function GoodsService()
    {
        Yii::$container->setSingleton('Goods.CategoryService', 'business\goodsService\CategoryService');
        Yii::$container->setSingleton('Goods.GoodsService', 'business\goodsService\GoodsService');
        Yii::$container->setSingleton('Goods.ItemService', 'business\goodsService\ItemService');
    }

    private function UserService()
    {
        Yii::$container->setSingleton('User.UserService', 'business\userService\UserService');
    }

    private function CouponService()
    {
        Yii::$container->setSingleton('Coupon.CouponService', 'business\couponService\CouponService');
        Yii::$container->setSingleton('Coupon.UserCouponService', 'business\couponService\UserCouponService');
    }

    private function AdService()
    {
        Yii::$container->setSingleton('Ad.BannerService', 'business\adService\BannerService');
        Yii::$container->setSingleton('Ad.BannerItemService', 'business\adService\BannerItemService');
    }

    private function RbacService()
    {
        Yii::$container->setSingleton('Rbac.RbacService', 'business\rbacService\RbacService');
    }
}
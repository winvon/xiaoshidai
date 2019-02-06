<?php

namespace business;

use common\base\IBusinessInit;
use Yii;

class BusinessInit implements IBusinessInit
{
    public function init()
    {
        $this->BasicDataService();
        $this->EmpService();
        $this->ProductService();
        $this->UserService();
        $this->CouponService();
        $this->AdService();
        $this->RbacService();
        $this->SpecialService();
        $this->MarketingActivitiesService();
    }

    private function BasicDataService()
    {
        Yii::$container->setSingleton('Basic.AreaService', 'business\basicDataService\AreaService');
    }

    private function EmpService()
    {
        Yii::$container->setSingleton('Emp.EmpService', 'business\empService\EmpService');
        Yii::$container->setSingleton('Emp.EmpService', 'business\empService\EmpService');
    }

    private function ProductService()
    {
        Yii::$container->setSingleton('Product.CategoryService', 'business\productService\CategoryService');
        Yii::$container->setSingleton('Product.CategoryReleaseService', 'business\productService\CategoryReleaseService');
        Yii::$container->setSingleton('Product.ProductService', 'business\productService\ProductService');
        Yii::$container->setSingleton('Product.ChaptersService', 'business\productService\ChaptersService');
    }

    private function UserService()
    {
        Yii::$container->setSingleton('User.UserService', 'business\userService\UserService');
        Yii::$container->setSingleton('User.RoleService', 'business\userService\RoleService');
        Yii::$container->setSingleton('User.CertService', 'business\userService\CertService');
    }

    private function CouponService()
    {
        Yii::$container->setSingleton('Coupon.CouponService', 'business\couponService\CouponService');
        Yii::$container->setSingleton('Coupon.UserCouponService', 'business\couponService\UserCouponService');
    }

    private function AdService()
    {
        Yii::$container->setSingleton('Ad.AdService', 'business\adService\AdService');
        Yii::$container->setSingleton('Ad.BannerService', 'business\adService\BannerService');
    }

    private function RbacService()
    {
        Yii::$container->setSingleton('Rbac.RbacService', 'business\rbacService\RbacService');
        Yii::$container->setSingleton('Rbac.NavService', 'business\rbacService\NavService');
        Yii::$container->setSingleton('Rbac.AuthService', 'business\rbacService\AuthService');
        Yii::$container->setSingleton('Rbac.RoleService', 'business\rbacService\RoleService');
    }

    private function SpecialService()
    {
        Yii::$container->setSingleton('Special.TopicService', 'business\specialService\TopicService');
    }

    private function MarketingActivitiesService()
    {
        Yii::$container->setSingleton('MarketingActivities.MarketingActivitiesService', 'business\market_activeService\MarketingActivitiesService');
    }
}
<?php
/**
 **********************************************************
 * @author : YiChaobao [ yichaobao@163.com ]
 * @time : 2019/01/01 00:00
 * @copyright : (c) 2019 YiChaobao All rights reserved.
 **********************************************************
 * @name : Module.php
 * @description : 模块
 **/
namespace app\modules\v1;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\v1';

    public function init()
    {
        parent::init();
        $this->setModule('test','app\modules\v1\test\Module');
        $this->setModule('site','app\modules\v1\site\Module');//首页模块
    }
}
<?php
/**
 **********************************************************
 * @author : YiChaobao [ yichaobao@163.com ]
 * @time : 2019/01/01 00:00
 * @copyright : (c) 2019 YiChaobao All rights reserved.
 **********************************************************
 * @name : Area.php
 * @description : 地区表模型
 **/
namespace backend\models;

use common\helpers\ErrorCode;
use common\helpers\WeHelper;
use Yii;

class Area extends \backend\models\BaseModel
{
    /**
     * @desc: 设置表名
     * @name: tableName
     * @return string
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
    public static function tableName()
    {
        return '{{%area}}';
    }
    public function attributeLabels()
    {
        return [];
    }
    public function rules()
    {
        return [];
    }
    public static function getAllTree(){
        $list = self::find()->select(['code as value', 'name as label', 'parent_code'])->orderBy('id ASC')->asArray()->all();
        $tree = array();
        if (is_array($list)) {
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data['value']] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                $parentId = $data['parent_code'];
                if ('china' == $parentId) {
                    $tree[] =& $list[$key];
                } else {
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent['children'][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }



}
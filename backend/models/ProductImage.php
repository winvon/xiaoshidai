<?php
/**
 **********************************************************
 * @author : YiChaobao [ yichaobao@163.com ]
 * @time : 2019/01/01 00:00
 * @copyright : (c) 2019 YiChaobao All rights reserved.
 **********************************************************
 * @name : GoodsImage.php
 * @description : 产品图片
 **/
namespace backend\models;
use yii\data\Pagination;
use Yii;
use  yii\behaviors\TimestampBehavior;
class ProductImage extends \backend\models\BaseModel
{
    public static function tableName()
    {
        return '{{%product_image}}';
    }
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => time(),
            ],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => '所属产品',
            'type' => '类型',
            'image_path' => '图片路径',
            'display_order' => '排序',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function rules()
    {
        return [
            [['product_id', 'type', 'image_path', 'display_order'], 'required', 'message' => '{attribute}不能为空'],
            ['image_path', 'string', 'max' => 200, 'tooLong' => '{attribute}最多200个字符'],
            ['product_id', 'number', 'message' => '产品编号数据类型错误'],
        ];
    }
    public function create_data($data)
    {
        $model = new self();
        $model->attributes = $data;
        if ($model->validate() && $model->save()) {
            return [
                'status' => true,
                'data' => $model->id,
            ];
        }
        return [
            'status' => false,
            'data' => $model->getErrors(),
        ];
    }

    public function update_data($data)
    {
        $info = self::findOne($data['id']);
        if ($info) {
            $info->attributes = $data;
            if ($info->validate() && $info->save()) {
                return [
                    'status' => true,
                    'data' => $info->id
                ];
            }
            return [
                'status' => false,
                'data' => $info->getErrors(),
            ];
        }
    }


    public function lists_data($id){
        return self::findAll(['product_id'=>$id]);
    }
}
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
class GoodsImage extends \backend\models\BaseModel
{
    public static function tableName()
    {
        return '{{%goods_image}}';
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
            'goods_id' => '所属商品',
            'type' => '类型',
            'pictrue' => '图片',
            'is_delete' => '是否删除',
            'display_order' => '排序',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function rules()
    {
        return [
            [['goods_id', 'type', 'pictrue', 'display_order'], 'required', 'message' => '{attribute}不能为空'],
            ['pictrue', 'string', 'max' => 200, 'tooLong' => '{attribute}最多200个字符'],
            [['is_delete'], 'in', 'range' => [0, 1], 'message' => '{attribute}数据错误'],
            ['goods_id', 'number', 'message' => '商品编号数据类型错误'],
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
        $goods = self::findOne($data['id']);
        if ($goods) {
            $goods->attributes = $data;
            if ($goods->validate() && $goods->save()) {
                return [
                    'status' => true,
                    'data' => $goods->id
                ];
            }
            return [
                'status' => false,
                'data' => $goods->getErrors(),
            ];
        }
    }

    public function delete_notin_data($ids=[],$goods_id=null){
        $where=[
            'and',['NOT IN','id',$ids],['goods_id'=>$goods_id],['is_delete'=>0]
        ];
        return self::updateAll(['is_delete'=>1],$where);
    }
}
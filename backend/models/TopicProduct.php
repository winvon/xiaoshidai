<?php
/**
 **********************************************************
 * @author : YiChaobao [ yichaobao@163.com ]
 * @time : 2019/01/16 11:07
 * @copyright : (c) 2019 YiChaobao All rights reserved.
 **********************************************************
 * @name : TopicProduct.php
 * @description :
 **/

namespace backend\models;

use common\helpers\ConstantHelper;
use Yii;
use yii\behaviors\TimestampBehavior;

class TopicProduct extends \backend\models\BaseModel
{
    public static function tableName()
    {
        return '{{%topic_product}}';
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
            'topic_id' => '专题ID',
            'product_id' => '产品ID',
            'tag_ids' => '标签ID',
            'type' => '促销方式',
            'bind_value' => '折扣值',
            'display_order' => '排序',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function rules()
    {
        return [
            [['topic_id', 'product_id', 'tag_ids', 'type', 'bind_value', 'display_order'], 'required', 'message' => '关联商品{attribute}不能为空'],//必填字段 【创建|更新】
            [['topic_id', 'product_id'], 'ruleProductOnly', 'on' => ['create']],
        ];
    }

    public function ruleProductOnly($attribute, $params)
    {
        $model = self::find()->where([
            'topic_id' => $this->topic_id,
            'product_id' => $this->product_id,
        ])->one();
        if ($model != null) {
            $this->addError('product_id', '促销商品重复');
        }

    }


    public function create_data($data)
    {
        $model = new self();
        $model->scenario = 'create';
        $model->attributes = $data;
        if ($model->validate() && $model->save()) {
            return [
                'status' => true,
                'data' => [
                    'id' => $model->id
                ],
            ];
        }
        return [
            'status' => false,
            'data' => $model->getErrors(),
        ];
    }


    public function update_data($id, $data)
    {
        $info = self::findOne($id);
        if ($info) {
            $info->attributes = $data;
            if ($info->validate() && $info->save()) {
                return [
                    'status' => true,
                    'data' => [
                        'id' => $info->id
                    ],
                ];
            }
            return [
                'status' => false,
                'data' => $info->getErrors(),
            ];
        }
        return [
            'status' => false,
            'error_code' => 1007
        ];
    }


    /**
     * @param $topic_id
     * @return array|\yii\db\ActiveRecord[]
     * @author 冯文飞 修改
     */
    public function list_data($topic_id)
    {
        return self::find()
            ->leftJoin('xsd_product_image', 'xsd_product_image.product_id=xsd_topic_product.product_id')
            ->leftJoin('xsd_product', 'xsd_product.id=xsd_topic_product.product_id')
            ->select('xsd_topic_product.id,
                               xsd_topic_product.topic_id,
                               xsd_product.product_name,
                               xsd_product.price,
                               xsd_topic_product.product_id,
                               xsd_topic_product.tag_ids,
                               xsd_topic_product.type,
                               xsd_topic_product.bind_value,
                               xsd_product_image.image_path,
                               xsd_topic_product.display_order')
            ->where(['topic_id' => $topic_id,])->orderBy('display_order DESC')->asArray()->all();
    }


    public function save_data($data)
    {
        if (!empty($data['id'])) {//更新
            return $this->update_data($data['id'], $data);
        } else {//添加
            return $this->create_data($data);
        }
    }


    public function deleteAllData($topic_id, $product_list_ids)
    {
        $list = self::find()->where(['topic_id' => $topic_id])->andWhere(['not in', 'id', $product_list_ids])->all();
        foreach ($list as $item) {
            $item->delete();
        }
        return [
            'status' => true,
        ];
    }

}
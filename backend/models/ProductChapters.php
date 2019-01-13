<?php
/**
 **********************************************************
 * @author : YiChaobao [ yichaobao@163.com ]
 * @time : 2019/01/01 00:00
 * @copyright : (c) 2019 YiChaobao All rights reserved.
 **********************************************************
 * @name : GoodsItem.php
 * @description : 商品分集表
 **/

namespace backend\models;

use common\helpers\ConstantHelper;
use yii\data\Pagination;
use Yii;
use  yii\behaviors\TimestampBehavior;

class ProductChapters extends \backend\models\BaseModel
{
    public static function tableName()
    {
        return '{{%product_chapters}}';
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
            'chapter_price' => '售价',
            'chapter_name' => '名称',
            'item_path' => '路径',
            'is_try' => '是否试看',
            'is_delete' => '是否删除',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function rules()
    {
        return [
            [['product_id', 'chapter_price', 'chapter_name', 'item_path', 'is_try'], 'required', 'message' => '{attribute}不能为空'],
            [['chapter_name', 'item_path'], 'string', 'max' => 200, 'tooLong' => '{attribute}最多200个字符'],
            [['is_try', 'is_delete'], 'in', 'range' => [0, 1], 'message' => '{attribute}数据错误'],
            [['status'], 'default', 'value' => 1],
            ['status', 'in', 'range' => [-1, 0, 1, 2], 'message' => '状态错误'],
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
                'data' => [
                    'id' => $model->id,
                    'product_id' => $model->product_id
                ],
            ];
        }
        return [
            'status' => false,
            'data' => $model->getErrors(),
        ];
    }

    public function info_data($item_id)
    {
        $info = self::find()->where(['id' => $item_id])->asArray(true)->one();
        $info_model = self::findOne($item_id);
        $info['file_lists'] = $info_model->productChaptersFile;
        if ($info) {
            return [
                'status' => true,
                'data' => $info,
            ];
        }
        return [
            'status' => 3050,
            'data' => null,
        ];
    }

    public function update_data($goods_item_id, $data)
    {
        $goods_item = self::findOne($goods_item_id);
        if ($goods_item) {
            $goods_item->attributes = $data;
            if ($goods_item->validate() && $goods_item->save()) {
                return [
                    'status' => true,
                    'data' => [
                        'id' => $goods_item->id,
                        'product_id' => $goods_item->product_id
                    ],
                ];
            }
            return [
                'status' => false,
                'data' => $goods_item->getErrors(),
            ];
        }
        return [
            'status' => 3050,
            'data' => null,
        ];
    }

    public function delete_data($goods_item_id)
    {
        $goods_item = self::findOne($goods_item_id);
        if ($goods_item) {
            $goods_item->is_delete = 1;
            if ($goods_item->validate() && $goods_item->save()) {
                return [
                    'status' => true,
                    'data' => [
                        'id' => $goods_item->id
                    ],
                ];
            }
            return [
                'status' => false,
                'data' => $goods_item->getErrors(),
            ];
        }
        return [
            'status' => 3050,
            'data' => null,
        ];
    }

    public function lists_data($where = [], $order = 'created_at desc', $pageSize = 20)
    {
        $model = self::getQuery($where);
        $totalCount = $model->count();
        $pagination = new Pagination([
            'defaultPageSize' => $pageSize,
            'totalCount' => $totalCount,
        ]);
        $lists = $model->select(['id', 'product_id', 'chapter_name', 'chapter_price', 'is_try', 'is_delete', 'status', 'created_at', 'updated_at'])->orderBy($order)
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
        $param[ConstantHelper::COUNT] = (int)$pagination->totalCount;
        $param[ConstantHelper::LISTS] = $lists;
        return self::backListFormat($param);
    }

    public function getQuery($where)
    {
        $query = self::find()
            ->where(['is_delete' => self::DELETE_NOT])
            ->andFilterWhere(['like', 'chapter_name', $where['chapter_name']])
            ->andFilterWhere(['product_id' => $where['product_id']]);

        if (isset($where['created_start_time']) && isset($where['created_end_time'])) {
            $query->andWhere(['between', 'created_at', $where['created_start_time'], $where['created_end_time']]);
        } elseif (isset($where['reg_start_time'])) {
            $query->andWhere(['>=', 'created_at', $where['created_start_time']]);
        } elseif (isset($where['reg_end_time'])) {
            $query->andWhere(['<=', 'created_at', $where['created_end_time']]);
        }
        return $query;
    }

    public function getProductChaptersFile()
    {
        return $this->hasMany(ProductChaptersFile::className(), ['product_chapter_id' => 'id']);
    }
}
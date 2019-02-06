<?php
/**
 **********************************************************
 * @author : YiChaobao [ yichaobao@163.com ]
 * @time : 2019/01/01 00:00
 * @copyright : (c) 2019 YiChaobao All rights reserved.
 **********************************************************
 * @name : Goods.php
 * @description : 产品模型
 **/

namespace backend\models;

use common\helpers\ConstantHelper;
use yii\data\Pagination;
use Yii;
use  yii\behaviors\TimestampBehavior;

use backend\models\ProductImage;

class Product extends \backend\models\BaseModel
{
    public static function tableName()
    {
        return '{{%product}}';
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
            'type' => '类型',
            'category_id' => '类别',
            'product_name' => '产品名称',
            'description' => '产品描述',
            'price' => '产品价格',
            'sales_price' => '产品促销价格',
            'is_sales' => '是否促销',
            'is_delete' => '是否删除',
            'user_id' => '所属会员',
            'sales_number' => '销量',
            'set_sales_number' => '热度值',
            'set_chapter_number' => '章节数',
            'body' => '详情',
            'status' => '状态',
            'start_time' => '促销活动开始时间',
            'end_time' => '促销活动结束时间',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function rules()
    {
        return [
            [['product_name', 'price', 'user_id', 'category_id','description' ,'type', 'body','sales_price','set_chapter_number'], 'required', 'message' => '{attribute}不能为空', 'on' => ['create', 'update']],//必填【创建|更新】

            ['product_name', 'string', 'max' => 200, 'tooLong' => '产品名称最多200个字符'],
            ['description', 'string', 'max' => 255, 'tooLong' => '描述在255个字符内'],
            ['is_delete', 'in', 'range' => [0, 1], 'message' => '数据错误'],
            [['status'], 'default', 'value' => 1],
            ['status', 'in', 'range' => [-1, 0, 1, 2], 'message' => '状态错误'],
            [['set_chapter_number', 'category_id', 'user_id', 'sales_number', 'set_sales_number'], 'number', 'message' => '数据类型错误'],
        ];
    }


    public function info_data($id)
    {
        $info = self::find()->where(['id' => $id])->asArray(true)->one();
        $info_model = self::findOne($id);
        $info['image_lists'] = $info_model->productImage;
//        $info['goods_files'] = $info_model->goodsFile;
        if ($info) {
            return [
                'status' => true,
                'data' => $info,
            ];
        }
        return [
            'status' => 3003
        ];
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

    /**
     * @desc: 更新数据
     * @name: update_data
     * @param $goods_id
     * @param $data
     * @return array
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
    public function update_data($id, $data)
    {
        $info = self::findOne($id);
        if ($info) {
            $info->scenario = 'update';
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
            'data' => '不存在该商品',
        ];
    }


    public function delete_data($id)
    {
        $info = self::findOne($id);
        if ($info) {
            $info->is_delete = 1;
            if ($info->validate() && $info->save()) {
                return [
                    'status' => true,
                    'data' => [
                        'user_id' => $info->id
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
            'data' => '不存在该商品',
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
        $lists = $model->select(['g.id', 'g.type', 'category_id', 'product_name', 'price', 'sales_price', 'user_id', 'status', 'g.created_at', 'g.updated_at','set_chapter_number','sales_price','sales_number', 'mobile', 'username', 'category_name'])->orderBy($order)
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
        $_lists = [];
        foreach ($lists as $key => $value) {
            $image_one = ProductImage::findOne([
                'product_id'=>$value['id']
            ]);
            if(!empty($image_one)){
                $value['image_path']=$image_one['image_path'];
            }
            $_lists[] = $value;
        }

        $param[ConstantHelper::COUNT] = (int)$pagination->totalCount;
        $param[ConstantHelper::LISTS] = $_lists;
        return self::backListFormat($param);

    }

    public function getQuery($where)
    {
        $query = self::find()->alias('g')
            ->leftJoin('{{%user}}', 'g.user_id = {{%user}}.id')
            ->leftJoin('{{%category}}', 'g.category_id = {{%category}}.id')
            ->where(['g.is_delete' => self::DELETE_NOT])
            ->andFilterWhere(['like', 'g.product_name', $where['product_name']])
            ->andFilterWhere(['like', 'mobile', $where['mobile']])
            ->andFilterWhere(['category_id' => $where['category_id']])
            ->andFilterWhere(['g.type' => $where['type']]);
        if (isset($where['created_start_time']) && isset($where['created_end_time'])) {
            $query->andWhere(['between', 'created_at', $where['created_start_time'], $where['created_end_time']]);
        } elseif (isset($where['reg_start_time'])) {
            $query->andWhere(['>=', 'created_at', $where['created_start_time']]);
        } elseif (isset($where['reg_end_time'])) {
            $query->andWhere(['<=', 'created_at', $where['created_end_time']]);
        }
        return $query;
    }

    /**
     * @desc:
     * @name: getGoodsImage
     * @return \yii\db\ActiveQuery
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
    public function getProductImage()
    {
        return $this->hasMany(ProductImage::className(), ['product_id' => 'id'])->orderBy('display_order DESC');
    }
    public function getProductImageOne()
    {
        return $this->hasOne(ProductImage::className(), ['product_id' => 'id'])->orderBy('display_order DESC');
    }
}
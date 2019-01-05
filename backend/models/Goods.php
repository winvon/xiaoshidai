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

class Goods extends \backend\models\BaseModel
{
    public static function tableName()
    {
        return '{{%goods}}';
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
            'goods_name' => '产品名称',
            'goods_description' => '产品描述',
            'goods_price' => '产品价格',
            'body' => '详情',
            'sale_price' => '产品促销价格',
            'is_delete' => '是否删除',
            'user_id' => '所属会员',
            'rebate' => '折扣',
            'sales' => '销量',
            'set_sales' => '热度值',
            'clicks' => '点击量',
            'number' => '课程数',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function rules()
    {
        return [
            [['goods_name', 'goods_price', 'user_id', 'category_id', 'status', 'type', 'number','body'], 'required', 'message' => '{attribute}不能为空', 'on' => ['create', 'update']],//必填【创建|更新】

            ['goods_name', 'string', 'max' => 200, 'tooLong' => '名称最多200个字符'],
            ['goods_description', 'string', 'max' => 255, 'tooLong' => '描述在255个字符内'],
            ['is_delete', 'in', 'range' => [0, 1], 'message' => '数据错误'],
            ['status', 'in', 'range' => [-1, 0, 1, 2], 'message' => '状态错误'],
            [['number', 'category_id', 'user_id', 'sales', 'set_sales', 'clicks'], 'number', 'message' => '数据类型错误'],
        ];
    }


    public function info_data($user_id)
    {
        $info = self::findOne($user_id);
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
    public function update_data($goods_id, $data)
    {
        $goods = self::findOne($goods_id);
        if ($goods) {
            $goods->scenario = 'update';
            $goods->attributes = $data;
            if ($goods->validate() && $goods->save()) {
                return [
                    'status' => true,
                    'data' => [
                        'id' => $goods->id
                    ],
                ];
            }
            return [
                'status' => false,
                'data' => $goods->getErrors(),
            ];
        }
        return [
            'status' => false,
            'data' => '不存在该商品',
        ];
    }


    public function delete_data($goods_id)
    {
        $goods = self::findOne($goods_id);
        if ($goods) {
            $goods->is_delete = 1;
            if ($goods->validate() && $goods->save()) {
                return [
                    'status' => true,
                    'data' => [
                        'user_id' => $goods->id
                    ],
                ];
            }
            return [
                'status' => false,
                'data' => $goods->getErrors(),
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
        $lists = $model->select(['g.id', 'type', 'category_id', 'goods_name', 'goods_price', 'sale_price', 'user_id', 'clicks', 'status', 'g.created_at', 'g.updated_at', 'mobile', 'username','category_name'])->orderBy($order)
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        $param[ConstantHelper::COUNT] = (int)$pagination->totalCount;
        // $param[ConstantHelper::PAGE] = $pagination->page + 1;
        // $param[ConstantHelper::PAGE_SIZE] = $pagination->pageSize;
        // $param[ConstantHelper::PAGE_COUNT] = $pagination->pageCount;
        $param[ConstantHelper::LISTS] = $lists;
        return self::backListFormat($param);
//        return [
//            'count' => (int)$pagination->totalCount,//列表总数
//            'page_count' => $pagination->pageCount,//分页数
//            'page_size' => $pagination->pageSize,//每页数量
//            'page' => $pagination->page + 1,//当前页
//            'lists' => $lists,
//        ];
    }

    public function getQuery($where)
    {
        $query = self::find()->alias('g')
            ->leftJoin('{{%user}}', 'g.user_id = {{%user}}.id')
            ->leftJoin('{{%category}}', 'g.category_id = {{%category}}.id')
            ->where(['g.is_delete' => self::DELETE_NOT])

            ->andFilterWhere(['like', 'goods_name', $where['goods_name']])
            ->andFilterWhere(['like', 'mobile', $where['mobile']])
            ->andFilterWhere(['category_id' => $where['category_id']])

            ->andFilterWhere(['type' => $where['type']]);
        if (isset($where['created_start_time']) && isset($where['created_end_time'])) {
            $query->andWhere(['between', 'created_at', $where['created_start_time'], $where['created_end_time']]);
        } elseif (isset($where['reg_start_time'])) {
            $query->andWhere(['>=', 'created_at', $where['created_start_time']]);
        } elseif (isset($where['reg_end_time'])) {
            $query->andWhere(['<=', 'created_at', $where['created_end_time']]);
        }
        return $query;
    }
}
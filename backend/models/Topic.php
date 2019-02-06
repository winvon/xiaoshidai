<?php
/**
 **********************************************************
 * @author : YiChaobao [ yichaobao@163.com ]
 * @time : 2019/01/16 10:35
 * @copyright : (c) 2019 YiChaobao All rights reserved.
 **********************************************************
 * @name : Topic.php
 * @description :
 **/

namespace backend\models;

use common\helpers\ConstantHelper;
use yii\data\Pagination;
use Yii;
use yii\behaviors\TimestampBehavior;

class Topic extends \backend\models\BaseModel
{
    public static function tableName()
    {
        return '{{%topic}}';
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
            'source' => '渠道',
            'client' => '来源',
            'topic_name' => '名称',
            'description' => '描述',
            'body' => '内容',
            'bind_url' => '网址',
            'coupon_ids' => '优惠券',
            'is_delete' => '是否删除',
            'is_lock' => '是否锁定',
            'display_order' => '排序',
            'show_start_time' => '显示开始时间',
            'show_end_time' => '显示结束时间',
            'start_time' => '活动开始时间',
            'end_time' => '活动结束时间',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function rules()
    {
        return [
            [['source', 'client', 'topic_name', 'description', 'body', 'bind_url', 'coupon_ids', 'display_order', 'show_start_time', 'show_end_time', 'start_time', 'end_time'], 'required', 'message' => '{attribute}不能为空'],//必填字段 【创建|更新】
            [['is_delete', 'is_lock'], 'default', 'value' => 0],
            [['show_start_time'], 'date', 'timestampAttribute' => 'show_start_time'],
            [['show_end_time'], 'date', 'timestampAttribute' => 'show_end_time'],
            ['show_end_time', 'compare', 'compareAttribute' => 'show_start_time', 'operator' => '>', 'enableClientValidation' => false, 'message' => '显示结束时间必须大于开始时间'],

            [['start_time'], 'date', 'timestampAttribute' => 'start_time'],
            [['end_time'], 'date', 'timestampAttribute' => 'end_time'],
            ['end_time', 'compare', 'compareAttribute' => 'start_time', 'operator' => '>', 'enableClientValidation' => false, 'message' => '专题结束时间必须大于开始时间'],
        ];
    }

    public function create_data($data)
    {
        $model = new self();
        $model->attributes = $data;
        if ($model->coupon_ids) {
            $model->coupon_ids = implode(",", $model->coupon_ids);
        }
        $model->show_start_time = strtotime($model->show_start_time);
        $model->show_end_time = strtotime($model->show_end_time);
        $model->start_time = strtotime($model->start_time);
        $model->end_time = strtotime($model->end_time);
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

    public function info_data($id)
    {
        $info = self::findOne($id)->toArray();
        if ($info) {
            return [
                'status' => true,
                'data' => $info,
            ];
        }
        return [
            'status' => false,
            'error_code' => 1007
        ];
    }

    /**
     * @param $coupon_ids
     * @return array|\yii\db\ActiveRecord[]
     * @author 冯文飞
     */
    public function coupon_list($coupon_ids)
    {
        $coupon_ids = explode(',', $coupon_ids);
        return Coupon::find()->where(['in', 'id', $coupon_ids])->asArray()->all();
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

    public function lock_data($id, $key)
    {
        $info = self::findOne($id);
        if ($info) {
            $info->is_lock = $key;
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

    public function update_data($id, $data)
    {
        $info = self::findOne($id);
        if ($info) {
            $info->attributes = $data;
            if ($info->coupon_ids) {
                $info->coupon_ids = implode(",", $info->coupon_ids);
            }
            $info->show_start_time = strtotime($info->show_start_time);
            $info->show_end_time = strtotime($info->show_end_time);
            $info->start_time = strtotime($info->start_time);
            $info->end_time = strtotime($info->end_time);
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

    public function lists_data($where = [], $order = 'created_at desc', $pageSize = 20)
    {
        $model = self::getQuery($where);
        $totalCount = $model->count();
        $pagination = new Pagination([
            'defaultPageSize' => $pageSize,
            'totalCount' => $totalCount,
        ]);
        $lists = $model->orderBy($order)
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
            ->andFilterWhere(['like', 'topic_name', $where['topic_name']])
            ->andFilterWhere(['source' => $where['source']])
            ->andFilterWhere(['client' => $where['client']]);
        return $query;
    }

}
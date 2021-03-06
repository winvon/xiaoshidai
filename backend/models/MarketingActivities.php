<?php

namespace backend\models;

use common\helpers\ConstantHelper;
use common\helpers\Param;
use Yii;

/**
 * This is the model class for table "{{%marketing_activities}}".
 *
 * @property int $id
 * @property int $type 类别(1优惠券,2积分)
 * @property string $activities_name 推广活动名
 * @property string $bind_value 绑定值('优惠券id或者积分')
 * @property string $bind_url 网址
 * @property string $description 活动描述
 * @property int $is_delete 删除
 * @property int $is_lock 冻结
 * @property int $display_order 排序
 * @property int $start_time 生效时间
 * @property int $end_time 失效时间
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class MarketingActivities extends \backend\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%marketing_activities}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'activities_name', 'bind_value', 'bind_url', 'start_time', 'end_time'], 'required'],
            [['type', 'is_delete', 'is_lock', 'display_order'], 'integer'],
            [['activities_name', 'bind_value'], 'string', 'max' => 100],
            [['description'], 'default', 'value' => ''],
            [['display_order'], 'default', 'value' => 0],
            [['bind_url', 'description'], 'string', 'max' => 200],
            [['start_time', 'end_time'], 'gtTimeNow'],
            [['end_time'], 'gtStartTime'],
            [['start_time', 'end_time', 'created_at', 'updated_at','bind_value'], 'safe'],
        ];
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            'delete' => ['is_delete'],
            'lock' => ['is_lock'],
            'sort' => ['display_order'],
        ]); // TODO: Change the autogenerated stub
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '类别(1优惠券,2积分)',
            'activities_name' => '推广活动名',
            'bind_value' => '绑定值(优惠券id或者积分)',
            'bind_url' => '网址',
            'description' => '活动描述',
            'is_delete' => '删除',
            'is_lock' => '冻结',
            'display_order' => '排序',
            'start_time' => '生效时间',
            'end_time' => '失效时间',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }


    /**
     * @param $attribute
     * @param $params
     * @author von
     */
    public function gtTimeNow($attribute, $params)
    {
        if ($this->$attribute <= time()) {
            $this->addError($attribute, $this->attributeLabels()[$attribute] . '必须大于当前时间');
        }
    }

    /**
     * @param $attribute
     * @param $params
     * @author von
     */
    public function gtStartTime($attribute, $params)
    {
        if ($this->$attribute <= $this->start_time) {
            $this->addError($attribute, $this->attributeLabels()[$attribute] . '必须大于生效时间');
        }
    }

    /**
     * 通过id查询数据
     * @param $id
     * @return bool|null|static
     */
    public function findOneById($id)
    {
        $model = self::findOne(['id' => $id]);
        if ($model) {
            return $model;
        }
        return false;
    }

    /**
     * 处理返回数据
     * @return array
     */
    public function getView()
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'activities_name' => $this->activities_name,
            'bind_value' =>  $this->bind_value,
            'bind_url' => $this->bind_url,
            'description' => $this->description,
            'is_lock' => $this->is_lock,
            'display_order' => $this->display_order,
            'number' => count($this->marketingActivitiesUser),
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }

    /**
     * 新增
     * @param $data
     * @return array|bool
     */
    public function createMarketingActivities($data)
    {
        $model = new self();
        $model->attributes = $data;
        $model->start_time = strtotime($model->start_time);
        $model->end_time = strtotime($model->end_time);
        if ($model->save()) {
            return true;
        }
        return $model->getErrors();
    }

    /**
     * 处理保存前的数据格式
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->created_at = time();
        }
        $this->updated_at = time();
        return parent::beforeSave($insert);
    }

    /**
     * 获取查询sql
     * @param $params
     * @return $this
     * 'activities_name', 'type', 'is_lock'
     */
    public function getQuery($params)
    {
        return $query = self::find()
            ->where(['is_delete' => self::DELETE_NOT])
            ->andFilterWhere(['like', 'activities_name', $params['activities_name']])
            ->andFilterWhere(['is_lock' => $params['is_lock']])
            ->andFilterWhere(['type' => $params['type']]);
    }

    /**
     * 修改
     * @param $params
     * @return array|bool
     */
    public function modifyMarketingActivities($params)
    {
        $this->attributes = $params;
        if (!empty($params['start_time'])) {
            $this->start_time = strtotime($params['start_time']);
        }
        if (!empty($params['end_time'])) {
            $this->end_time = strtotime($params['end_time']);
        }
        if ($this->validate() && $this->save()) {
            return true;
        }
        return $this->getErrors();
    }

    /**
     * 获取数据列
     * @param $params
     * @return array
     */
    public function getList($params)
    {
        $page_size = Param::getHeaders('page_size');
        $page = Param::getHeaders('page');
        $query = self::getQuery($params);
        $models = $query->limit($page_size)
            ->offset(($page - 1) * $page_size)
            ->orderBy('created_at DESC')
            ->all();
        $list = [];
        foreach ($models as $model) {
            $list[] = $model->getView();
        }
        $count = $query->count();
        $param[ConstantHelper::COUNT] = $count;
        $param[ConstantHelper::LISTS] = $list;
        return self::backListFormat($param);
    }

    /**
     * 获取优惠券详情
     * @return array|\yii\db\ActiveRecord[]
     * @author von
     */
    public function getCouponList()
    {
        if ($this->type == ConstantHelper::MARKET_ACTIVE_TYPE_COUPON) {
            $ids = explode(',', $this->bind_value);
            return Coupon::find()->where(['in', 'id', $ids])->asArray()->all();
        }
        return [];
    }

    /**
     * 删除优惠券
     * @param $this ->id
     * @return array|bool
     */
    public function del()
    {
        $this->scenario = 'delete';
        $uc = MarketingActivitiesUser::findOne(['activities_id' => $this->id]);
        if ($uc != null) {
            $this->addError('is_delete', '活动存在用户,不支持删除');
            return $this->getErrors();
        }
        $this->is_delete = ConstantHelper::IS_DELETE_TRUE;
        if ($this->save()) {
            return true;
        }
        return $this->getErrors();
    }

    /**
     * 冻结或解冻优惠券
     * @param $this ->id
     * @return array|bool
     */
    public function lockCouponById()
    {
        $this->scenario = 'lock';
        if ($this->is_lock === ConstantHelper::IS_LOCK_TRUE) {
            $this->is_lock = ConstantHelper::IS_LOCK_FALSE;
        } else {
            $this->is_lock = ConstantHelper::IS_LOCK_TRUE;
        }
        UserCoupon::updateAll(['is_lock' => $this->is_lock], ['coupon_id' => $this->id]);
        if ($this->save()) {
            return true;
        }
        return $this->getErrors();
    }


    /**
     * 排序
     * @param $this ->id
     * @return array|bool
     */
    public function sort($data)
    {
        $this->scenario = 'sort';
        $this->attributes = $data;
        if ($this->save()) {
            return true;
        }
        return $this->getErrors();
    }

    /**
     * @return \yii\db\ActiveQuery
     * @author von
     */
    public function getMarketingActivitiesUser()
    {
        return $this->hasMany(MarketingActivitiesUser::className(), ['activities_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     *
     * @author von
     */
    public function getCoupon()
    {
        return $this->hasOne(Coupon::className(), ['id' => 'bind_value']);
    }
}

<?php

namespace backend\models;

use common\helpers\ConstantHelper;
use common\helpers\Param;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%banner}}".
 *
 * @property int $id
 * @property int ad_id
 * @property int type
 * @property string $banner_name
 * @property int $source
 * @property string bind_value
 * @property int hot
 * @property int is_lock`
 * @property int display_order
 * @property int is_delete
 * @property int start_time
 * @property int end_time
 * @property int $created_at
 * @property int $updated_at
 */
class Banner extends BaseModel
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%banner}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ad_id', 'banner_name', 'type', 'bind_value', 'start_time', 'end_time'], 'required'],
            [['ad_id', 'type', 'is_delete', 'display_order', 'hot'], 'integer'],
            [['banner_name'], 'string', 'max' => 50],
            ['hot', 'default', 'value' => 0],
            ['display_order', 'default', 'value' => 0],
            [['start_time', 'end_time'], 'gtTimeNow'],
            [['end_time'], 'gtStartTime'],
            ['is_lock', 'default', 'value' => ConstantHelper::IS_LOCK_FALSE],
            ['type', 'in', 'range' => [1, 2, 3]],
        ];
    }

    public function gtTimeNow($attribute, $params)
    {
        if ($this->$attribute <= time()) {
            $this->addError($attribute, $this->attributeLabels()[$attribute] . '须大于当前时间');
        }
    }

    public function gtStartTime($attribute, $params)
    {
        if ($this->$attribute <= $this->start_time) {
            $this->addError($attribute, $this->attributeLabels()[$attribute] . '须大于' . $this->attributeLabels()['start_time']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ad_id' => 'Ad ID',
            'banner_name' => 'Banner Name',
            'type' => 'Type',
            'bind_value' => 'bind_value',
            'is_delete' => 'Is Delete',
            'display_order' => 'Display Order',
            'hot' => 'Click Number',
            'is_lock' => 'Is Lock',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
//            'update' => ['banner_name', 'type', 'ad_id', 'attributes', 'display_order', 'is_lock', 'start_time', 'end_time'],
            'delete' => ['is_delete'],
            'lock' => ['is_lock'],
        ]); // TODO: Change the autogenerated stub
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
     * 处理返回的数据格式
     * @return array
     */
    public function getView()
    {
        return [
            'id' => (string)$this->id,
            'banner_name' => $this->banner_name,
            'ad_name' => @$this->ad->ad_name,
            'type' => $this->type,
            'bind_value' => $this->type == ConstantHelper::BANNER_ITEM_TYPE_BY_URL ? $this->bind_value : @$this->goods->goods_name,
            'display_order' => $this->display_order,
            'hot' => $this->hot,
            'is_lock' => $this->is_lock,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }

    /**
     * 新增广告
     * @param $data
     *
     * @return array|bool
     */
    public function createBanner($data)
    {
        $model = new self();
        $model->attributes=$data;
        $model->start_time = strtotime($data['start_time']);
        $model->end_time = strtotime($data['end_time']);
        if ( $model->save()) {
            return true;
        }
        return $model->getErrors();
    }

    /**
     * 保存前处理数据
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->is_delete = ConstantHelper::IS_DELETE_FALSE;
            $this->created_at = time();
            $this->updated_at = time();
        } else {
            $this->updated_at = time();
        }
        return parent::beforeSave($insert);
    }

    /**
     * 获取查询sql
     * @param $params
     * @return $this
     */
    public function getQuery($params)
    {
        return $query = self::find()
            ->where(['is_delete' => ConstantHelper::IS_DELETE_FALSE])
            ->andFilterWhere(['like', 'banner_name', $params['banner_name']])
            ->andFilterWhere(['source' => $params['source']])
            ->andFilterWhere(['type' => $params['type']])
            ;
    }

    /**
     * 修改广告
     * @param $params
     * @return array|bool
     */
    public function modifyBanner($params)
    {
//        $this->scenario = 'update';
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
     * 删除广告
     * @return array|bool
     */
    public function del()
    {
        $this->scenario = 'delete';
        $this->is_delete =ConstantHelper::IS_DELETE_TRUE;
        if ($this->save()) {
            return true;
        }
        return $this->getErrors();
    }

    /**
     * @return array|bool
     *
     * @author von
     */
    public function lock()
    {
        $this->scenario = 'lock';
        if ($this->is_lock == ConstantHelper::IS_LOCK_FALSE) {
            $this->is_lock = ConstantHelper::IS_LOCK_TRUE;
        } else {
            $this->is_lock = ConstantHelper::IS_LOCK_FALSE;
        }
        if ($this->save()) {
            return true;
        }
        return $this->getErrors();
    }

    /**
     * 和广告渠道对应关系
     * @return \yii\db\ActiveQuery
     */
    public function getAd()
    {
        return $this->hasOne(Ad::className(), ['id' => 'ad_id']);
    }

    /**
     * 绑定的商品
     * @return \yii\db\ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasOne(Product::className(), ['id' => 'bind_value']);
    }
}

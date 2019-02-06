<?php

namespace backend\models;

use common\helpers\ConstantHelper;
use common\models\User;
use Yii;
use common\helpers\Param;

/**
 * This is the model class for table "{{%user_coupon}}".
 *
 * @property int $id
 * @property int $user_id
 * @property int $coupon_id
 * @property int $is_delete
 * @property int $is_lock
 * @property int $is_consume
 * @property int $consume_time
 * @property int $created_at
 * @property int $updated_at
 */
class UserCoupon extends \backend\models\BaseModel
{
    const LOCK_NOT = 0;
    const LOCKED = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_coupon}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'coupon_id', 'is_delete', 'is_lock', 'is_consume', 'consume_time', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'coupon_id', 'is_delete', 'is_lock', 'is_consume', 'consume_time', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户id',
            'coupon_id' => '优惠券id',
            'is_delete' => 'Is Delete',
            'is_lock' => '是否冻结',
            'is_consume' => '是否使用',
            'consume_time' => '消费使用时间',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
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
     * 新增用户优惠券
     * @param $data
     * @return array|bool
     */
    public function createUserCoupon($data)
    {
        $model = new self();
        $model->attributes = $data;
        if ($model->validate() && $model->save()) {
            return true;
        }
        return $model->getErrors();
    }

    /**
     * 处理保存前数据格式
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->is_delete = self::DELETE_NOT;
            $this->created_at = time();
            $this->updated_at = time();
        } else {
            $this->updated_at = time();
        }
        return parent::beforeSave($insert);
    }

    /**
     * @param $user_id
     * @return static[]
     */
    public static function findAllByUserId($user_id)
    {
        return self::findAll(['user_id' => $user_id]);
    }

    /**
     * @param $coupon_id
     * @return static[]
     */
    public static function findAllByCouponId($coupon_id)
    {
        return self::findAll(['coupon_id' => $coupon_id]);
    }

    /**
     * @param $user_id
     * @param $coupon_id
     * @return static[]
     */
    public static function findAllByUserIdAndCouponId($user_id, $coupon_id)
    {
        return self::findAll(['user_id' => $user_id, 'coupon_id' => $coupon_id]);
    }

    /**
     * 获取查询sql
     * @param $params
     * @return $this
     */
    public function getQuery($params)
    {
        return $query = self::find()
            ->leftJoin('xsd_user','xsd_user.id=xsd_user_coupon.user_id')
            ->leftJoin('xsd_coupon','xsd_coupon.id=xsd_user_coupon.coupon_id')
            ->select('xsd_user_coupon.id as id,coupon_id,user_id,xsd_user_coupon.is_lock,xsd_user_coupon.is_lock,is_consume,xsd_user_coupon.consume_time,xsd_user_coupon.updated_at,xsd_user_coupon.created_at')
            ->where(['xsd_user_coupon.is_delete' => self::DELETE_NOT])
            ->andFilterWhere(['like','xsd_user.username', $params['username']])
            ->andFilterWhere(['like','xsd_coupon.coupon_name' , $params['coupon_name']])
            ->andFilterWhere(['xsd_user_coupon.is_lock' => $params['is_lock']])
            ->andFilterWhere(['xsd_user_coupon.is_consume' => $params['is_consume']])
            ->andFilterWhere(['xsd_user_coupon.user_id' => $params['user_id']])
            ;
    }

    /**
     * 处理返回数据格式
     * @return array
     */
    public function getView()
    {
        return [
            'id' => $this->id,
            'coupon_name' => @$this->coupon->coupon_name,
            'username' => @$this->user->username,
            'user_id' => $this->user_id,
            'mobile' => @$this->user->mobile,
            'is_lock' => $this->is_lock,
            'is_consume' => $this->is_consume,
            'consume_time' => $this->consume_time == 0 ? '' : $this->consume_time,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }

    /**
     * 处理返回数据格式
     * @return array
     */
    public function getCollectView()
    {
        $query=self::find()->where(['is_delete'=>ConstantHelper::IS_DELETE_FALSE])->andWhere(['user_id'=>(int)$this->user_id]);
       $number=$query->count();
        $use_number=$query->andWhere(['is_consume'=>ConstantHelper::COUPON_IS_CONSUME_TRUE])->count();
        return [
            'coupon_name' => @$this->coupon->coupon_name,
            'user_id' => $this->user_id,
            'username' => @$this->user->username,
            'mobile' => @$this->user->mobile,
            'number' => $number,
            'using_status'=> $number==0?0:round($use_number/$number,4)
        ];
    }

    /**
     * 修改用户优惠券
     * @param $params
     * @return array|bool
     */
    public function modifyUserCoupon($params)
    {
        $this->scenario = 'update';
        $this->attributes = $params;
        if ($this->save()) {
            return true;
        }
        return $this->getErrors();
    }

    /**
     * 获取用户优惠券列表
     * @param $params
     * @return array
     */
    public function getUserList($params)
    {
        $page_size = Param::getHeaders('page_size');
        $page = Param::getHeaders('page');
        $query = self::getQuery($params);
        $models = $query
            ->groupBy('user_id')
            ->limit($page_size)
            ->offset(($page - 1) * $page_size)
            ->orderBy('created_at DESC')
            ->all();
        $list = [];
        foreach ($models as $model) {
            $list[] = $model->getCollectView();
        }
        $count = $query->count();
        $param[ConstantHelper::COUNT] = $count;
        $param[ConstantHelper::LISTS] = $list;
        return self::backListFormat($param);
    }

    /**
     * 获取用户优惠券详情
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
     * 删除用户优惠券
     * @return array|bool
     */
    public function del()
    {
        $this->is_delete = self::DELETED;
        if ($this->save()) {
            return true;
        }
        return $this->getErrors();
    }

    /**
     * 冻结用户优惠券
     * @return array|bool
     */
    public function lock()
    {
        if ($this->is_lock == self::LOCK_NOT){
            $this->is_lock = self::LOCKED;
        }else{
            $this->is_lock = self::LOCK_NOT;
        }
        if ($this->save()) {
            return true;
        }
        return $this->getErrors();
    }

    /**
     * 与优惠券表对应关系
     * @return \yii\db\ActiveQuery
     */
    public function getCoupon()
    {
        return $this->hasOne(Coupon::className(), ['id' => 'coupon_id']);
    }

    /**
     * 与前台用户对应关系
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}

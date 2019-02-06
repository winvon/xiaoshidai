<?php

namespace backend\models;

use common\helpers\ConstantHelper;
use common\helpers\Param;
use Yii;

/**
 * This is the model class for table "{{%marketing_activities_user}}".
 * @property int $id
 * @property int $activities_id 营销活动id
 * @property int $user_id 用户id
 * @property int $created_at
 */
class MarketingActivitiesUser extends BaseModel
{
    public $username;
    public $mobile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%marketing_activities_user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['activities_id', 'user_id'], 'required'],
            [['activities_id', 'user_id', 'created_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'activities_id' => '营销活动id',
            'user_id' => '用户id',
            'created_at' => 'Created At',
        ];
    }

    /**
     * 处理返回数据
     * @return array
     */
    public function getView()
    {
        return [
            'id' => $this->id,
            'activities_name' =>@ $this->marketingActivities->activities_name,
            'username' => $this->username,
            'user_id' => $this->user_id,
            'mobile' => $this->mobile,
            'created_at' => $this->created_at,
        ];
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
            ->leftJoin('xsd_user', 'xsd_user.id=xsd_marketing_activities_user.user_id')
            ->filterWhere(['like', 'username', $params['username']])
            ->andFilterWhere(['activities_id'=> $params['activities_id']])
            ->select('xsd_marketing_activities_user.id,activities_id,xsd_user.username,user_id,xsd_user.mobile,xsd_marketing_activities_user.created_at');
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
     * 给活动添加用户
     * @param $activities_id
     * @param $user_id
     * @return bool
     * @author von
     */
    public function createMarketingActivitiesUser($activities_id, $user_id)
    {
        $user_ids = explode(',', $user_id);
        $user_ids = array_filter($user_ids);
        $insert = [];
        $total=count($user_ids);
        $s=0;
        foreach ($user_ids as $user_id) {
            if (self::findOne(['activities_id' => $activities_id, 'user_id' => $user_id])) {
                $s++;
                continue;
            }
            $insert[] = [$activities_id, $user_id, time()];
        }
        if (!empty($insert)) {
            $res = Yii::$app->db->createCommand()->batchInsert(MarketingActivitiesUser::tableName(),
                ['activities_id', 'user_id', 'created_at'], $insert)->execute();
        }else{
            $res=0;
        }
        return ['success'=>(int)($s+$res),'failed'=>(int)($total-$s-$res)];
    }

    /**
     * 通过id 删除
     * @param $id
     * @return bool
     * @author von
     */
    public function delById($id)
    {
        $model = self::findOne($id);
        if ($model != null) {
            $model->delete();
        }
        return true;
    }

    /**
     * @return \yii\db\ActiveQuery
     * @author von
     */
    public function getMarketingActivities()
    {
        return $this->hasOne(MarketingActivities::className(), ['id' => 'activities_id']);
    }

}

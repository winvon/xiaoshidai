<?php
/**
 **********************************************************
 * @author : YiChaobao [ yichaobao@163.com ]
 * @time : 2019/01/01 00:00
 * @copyright : (c) 2019 YiChaobao All rights reserved.
 **********************************************************
 * @name : User.php
 * @description : 用户模型
 **/

namespace backend\models;

use common\helpers\ConstantHelper;
use common\helpers\ErrorCode;
use common\helpers\WeHelper;
use common\helpers\User as userHelpers;
use yii\data\Pagination;
use Yii;
use  yii\behaviors\TimestampBehavior;

class User extends \backend\models\BaseModel
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    /**
     * @desc: 数据表名
     * @name: tableName
     * @return string
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
    public static function tableName()
    {
        return '{{%user}}';
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

    /**
     * @desc: 字段标签
     * @name: attributeLabels
     * @return array
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
    public function attributeLabels()
    {
        return [

            'id' => 'ID',
            'openid' => 'openid',
            'nickname' => '昵称',
            'username' => '真实姓名',
            'mobile' => '手机号码',
            'email' => '邮箱',
            'id_card' => '身份证号',
            'mobile_validate' => '手机验证',
            'email_validate' => '邮箱验证',
            'verified' => '证件实名效验',
            'headimgurl' => '头像',
            'sex' => '性别',
            'age' => '年龄',
            'province_id' => '省份',
            'city_id' => '城市',
            'district_id' => '区域',
            'user_role_id' => '用户角色',
            'password' => '密码',
            'pay_password' => '支付密码',
            'is_delete' => '是否删除',
            'is_lock' => '是否锁定',
            'created_at' => '注册时间',
            'updated_at' => '更新时间',
            'reg_client' => '注册来源',
            'last_time' => '近期登录时间',
            'last_ip' => '近期登录ip',
            'reg_ip' => '注册ip',
        ];
    }

    /**
     * @desc: 验证规则
     * @name: rules
     * @return array
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
    public function rules()
    {
        return [
            [['mobile', 'province_id', 'city_id', 'district_id'], 'required', 'message' => '{attribute}不能为空', 'on' => ['create', 'update']],//必填字段 【创建|更新】
            [['mobile'], 'match', 'pattern' => '/^[1][345789][0-9]{9}$/', 'message' => '手机号码格式错误', 'on' => ['create', 'update']],//手机号码【创建|更新】
            ['mobile', 'unique', 'targetClass' => '\backend\models\User', 'message' => '该手机号码已注册', 'on' => ['create', 'update']],//手机号码查重【创建|更新】

            [['password'], 'required', 'message' => '{attribute}不能为空', 'on' => ['create']],//密码【创建】
            ['password', 'string', 'min' => 6, 'max' => 32, 'tooLong' => '密码长度在6-32位', 'tooShort' => '密码长度在6-32位字符', 'on' => ['create', 'update']],//密码规则【创建|更新】

            ['username', 'string', 'max' => 20, 'tooLong' => '姓名最多20个字符'],
            [['email'], 'string', 'max' => 50, 'tooLong' => '邮箱地址最多50个字符'],
            ['email', 'email', 'message' => '邮箱格式错误'],
            ['sex', 'in', 'range' => [0, 1, 2], 'message' => '性别错误'],
            [['mobile_validate', 'email_validate', 'verified', 'is_delete', 'is_lock'], 'in', 'range' => [0, 1], 'message' => '数据错误'],

            [['nickname', 'openid', 'headimgurl', 'share_code','pay_password'], 'safe'],

        ];
    }

    public function setPassword($password)
    {
        return md5($password);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->password = $this->setPassword($this->password);
                $this->reg_ip = $this->last_ip = ip2long(Yii::$app->request->userIP);
            }
            return true;
        } else {
            return false;
        }
    }
//    public function afterFind()
//    {
//        parent::afterFind();
//        $this->reg_time = date('Y-m-d H:i',$this->reg_time);
//        $this->reg_ip = long2ip($this->reg_ip);
//        $this->last_time = date('Y-m-d H:i',$this->last_time);
//        $this->last_ip = long2ip($this->last_ip);
//    }

    /**
     * @desc: 获取
     * @name: info
     * @param $user_id
     * @return array
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
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
            'status' => false,
            'data' => '不存在该用户',
        ];
    }

    /**
     * @desc: 创建
     * @name: create
     * @param $data 用户数据
     * @return array|bool
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
    public function create_data($data)
    {

        $model = new self();
        $model->scenario = 'create';
        $model->attributes = $data;
        if ($model->validate() && $model->save()) {

            return [
                'status' => true,
                'data' => [
                    'user_id' => $model->id
                ],
            ];
        }
        return [
            'status' => false,
            'data' => $model->getErrors(),
        ];
    }

    /**
     * @desc: 更新
     * @name: update_data
     * @param $user_id 编号
     * @param $data 数据
     * @return array
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
    public function update_data($user_id, $data)
    {
        $user = self::findOne($user_id);
        if ($user) {
            $user->scenario = 'update';
            $user->attributes = $data;
            if ($user->validate() && $user->save()) {
                return [
                    'status' => true,
                    'data' => [
                        'user_id' => $user->id
                    ],
                ];
            }
            return [
                'status' => false,
                'data' => $user->getErrors(),
            ];
        }
        return [
            'status' => false,
            'data' => '不存在该用户',
        ];
    }

    /**
     * @desc: 删除用户
     * @name: delete_data
     * @param $user_id 编号
     * @return array
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
    public function delete_data($user_id)
    {
        $user = self::findOne($user_id);
        if ($user) {
            $user->is_delete = 1;
            if ($user->validate() && $user->save()) {
                return [
                    'status' => true,
                    'data' => [
                        'user_id' => $user->id
                    ],
                ];
            }
            return [
                'status' => false,
                'data' => $user->getErrors(),
            ];
        }
        return [
            'status' => false,
            'data' => '不存在该用户',
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
        $lists = $model->select(['id', 'nickname', 'username', 'mobile','headimgurl' ,'email', 'sex', 'is_lock', 'created_at','last_time','city_id','user_role_id'])->orderBy($order)
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
        $lists_data = [];
        foreach ($lists as $value) {
            $value['user_role_name'] = '普通会员';
            $value['city_name'] = '成都';
            $lists_data[] = $value;
        }
        $param[ConstantHelper::COUNT] = (int)$pagination->totalCount;
        $param[ConstantHelper::PAGE] = $pagination->page + 1;
        $param[ConstantHelper::PAGE_SIZE] = $pagination->pageSize;
        $param[ConstantHelper::PAGE_COUNT] = $pagination->pageCount;
        $param[ConstantHelper::LISTS] = $lists_data;
        return self::backListFormat($param);

//        return [
//            'count' => (int)$pagination->totalCount,//列表总数
//            'page_count' => $pagination->pageCount,//分页数
//            'page_size' => $pagination->pageSize,//每页数量
//            'page' => $pagination->page + 1,//当前页
//            'lists' => $lists_data,
//        ];

    }

    public function getQuery($where)
    {
        $query = self::find()
            ->where(['is_delete' => self::DELETE_NOT])
            ->andFilterWhere(['like', 'mobile', $where['mobile']])
            ->andFilterWhere(['like', 'username', $where['username']])
            ->andFilterWhere(['sex' => $where['sex']]);
        if (isset($where['reg_start_time']) && isset($where['reg_end_time'])) {
            $query->andWhere(['between', 'created_at', $where['reg_start_time'], $where['reg_end_time']]);
        } elseif (isset($where['reg_start_time'])) {
            $query->andWhere(['>=', 'created_at', $where['reg_start_time']]);
        } elseif (isset($where['reg_end_time'])) {
            $query->andWhere(['<=', 'created_at', $where['reg_end_time']]);
        }
        return $query;
    }

    public function lock($user_id, $lock = 0)
    {
        $user = self::findOne($user_id);
        if ($user) {
            $user->is_lock = $lock;
            if ($user->validate() && $user->save()) {
                return [
                    'status' => true,
                    'data' => [
                        'user_id' => $user->id,
                        'is_lock' => $user->is_lock,
                    ],
                ];
            }
            return [
                'status' => false,
                'data' => $user->getErrors(),
            ];
        }
        return [
            'status' => false,
            'data' => '不存在该用户',
        ];
    }
}
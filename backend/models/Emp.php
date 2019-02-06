<?php

namespace backend\models;

use common\helpers\ConstantHelper;
use common\helpers\ErrorCode;
use common\helpers\Param;
use common\helpers\WeHelper;
use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%emp}}".
 *
 * @property int $id
 * @property int $job_number
 * @property string $password
 * @property string $username
 * @property string $mobile
 * @property string $email
 * @property string work_photo
 * @property string role_ids
 * @property int is_delete
 * @property int sex
 * @property int is_lock
 * @property int is_admin
 * @property string token
 * @property int last_time
 * @property int last_ip
 * @property int created_at
 * @property int updated_at
 */
class Emp extends \backend\models\BaseModel
{

    const LOCK_NOT = 0;
    const LOCKED = 1;

    const SUPER_ADMIN_NO = 0;
    const SUPER_ADMIN_YES = 1;

    public $oldpassword;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%emp}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['password', 'username', 'email', 'mobile'], 'trim'],
            [['password', 'username', 'mobile'], 'required'],
            [['job_number', 'is_delete', 'is_lock', 'is_admin', 'created_at', 'updated_at'], 'integer'],
            ['password', 'string', 'max' => 32],
            ['password', 'string', 'min' => 8],
            ['password', 'valid_pass'],
            ['username', 'string', 'max' => 20],
            ['sex', 'default', 'value' => 0],
            ['is_lock', 'default', 'value' => self::LOCK_NOT],
            ['is_delete', 'default', 'value' => self::DELETE_NOT],
            ['mobile', 'unique', 'targetClass' => self::class, 'message' => '手机号已被使用'],
            ['email', 'unique', 'targetClass' => self::class, 'message' => '邮箱已被使用'],
            ['mobile', 'match', 'pattern' => '/^[1][34578][0-9]{9}$/', 'message' => '手机号码格式错误'],
            [['email'], 'string', 'max' => 50],
            ['email', 'email', 'message' => '邮箱格式错误'],
            [['token'], 'string', 'max' => 200],
            ['oldpassword', 'checkPassword', 'on' => 'change-password'],
            [['sex', 'work_photo', 'role_ids', 'last_time', 'last_ip'], 'safe'],
        ];
    }

    /**
     * @param $attribute
     * @param $param
     * @author von
     */
    public function valid_pass($attribute, $param)
    {
        $r1 = '/[A-Z]/';  //uppercase
        $r2 = '/[a-z]/';  //lowercase
        $r3 = '/[0-9]/';  //numbers
        $r4 = '/[~!@#$%^&*()\-_=+{};:<,.>?]/';  // special char
        $r5 = '/[a-zA-Z]/';  //case
        if (preg_match_all($r5, $this->$attribute, $o) < 1) {
            $this->addError($attribute, '密码必须包含字母和数字，请返回修改！');
        }
        if (preg_match_all($r3, $this->$attribute, $o) < 1) {
            $this->addError($attribute, '密码必须包含字母和数字，请返回修改！');
        }
    }

    /**
     * 检查密码
     * @param $password
     * @return bool
     */
    public function checkPassword($attribute, $param)
    {
        if (!$this->validatePassword($this->$attribute)) {
            $this->addError($attribute, '旧密码错误');
        }
    }

    public function scenarios()
    {
        return array_merge(parent::scenarios(),
            [
                'setToken' => ['token'],
                'delete' => ['is_delete'],
                'lock' => ['is_lock'],
//                'update' => ['username', 'password', 'mobile', 'email','sex',''],
                'change-password' => ['oldpassword', 'password'],
            ]); // TODO: Change the autogenerated stub
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'job_number' => '账号',
            'password' => '密码',
            'username' => '姓名',
            'mobile' => '手机',
            'email' => '邮箱',
            'is_delete' => 'Is Delete',
            'is_lock' => '冻结',
            'is_admin' => 'Is Super Admin',
            'token' => 'Token',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * 设置登陆后token
     * @return string
     */
    public function setToken()
    {
        $this->scenario = 'setToken';
        $token = Yii::$app->security->generateRandomString();
        $cache = Yii::$app->cache;
        $cache->set($token, $this->id, 7 * 60 * 60 * 24);
        $this->token = $token;
        $this->last_time = time();
        $this->last_ip = ip2long(Yii::$app->request->userIP);
        if (!$this->save()) {
           return $this->getErrors();
        }
        return $token;
    }


    /**
     * 检查密码
     * @param $password
     * @return bool
     */
    public function validatePassword($password)
    {
        if ($this->password == $this->setPassword($password)) {
            return true;
        }
        return false;
    }

    /**
     * 查询单个对象
     * @param $username
     * @return bool|null|static
     */
    public function findOneByUsername($username)
    {
        $model = self::findOne(['username' => $username]);
        if ($model) {
            return $model;
        }
        return false;
    }

    /**
     * 查询单个对象
     * @param $token
     * @return bool|null|static
     */
    public function findOneByToken($token)
    {
        /*后面修改为以$user_id 查询*/
        $cache = Yii::$app->cache;
        $user_id = $cache->get($token);
        /**********************/
        $model = self::findOne(['token' => $token]);
        if ($model) {
            return $model;
        }
        return false;
    }

    /**
     * 查询单个对象
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
     * 查询单个对象
     * @param $job_number
     * @return bool|null|static
     */
    public function findOneByjob_number($job_number)
    {
        $model = self::findOne(['job_number' => $job_number]);
        if ($model) {
            return $model;
        }
        return false;
    }

    /**
     * 处理对象的数据
     * @return array
     */
    public function getView()
    {
        return [
            'id' => $this->id,
            'job_number' => $this->job_number,
            'username' => $this->username,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'work_photo' => $this->work_photo,
            'sex' => $this->sex,
            'role_ids' => $this->role_ids,
            'is_lock' => $this->is_lock,
            'last_ip' => long2ip($this->last_ip),
            'last_time' => $this->last_time == null ? 0 : $this->last_time,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }

    /**
     * 获取详情
     * @return array
     */
    public function getDetail()
    {
        $role_ids = explode(',', $this->role_ids);
        $role = [];
        foreach ($role_ids as $role_id) {
            $roleModel = Role::findOne($role_id);
            if ($roleModel != null) {
                $role[] = [
                    'id' => $roleModel->id,
                    'role_name' => $roleModel->role_name,
                ];
            }
        }
        $array = $this->getView();
        $array['roles'] = $role;
        return $array;
    }

    /**
     * 设置密码
     * @param $password
     * @return string
     */
    public function setPassword($password)
    {
        return md5($password);
    }

    /**
     * 新增员工
     * @param $data
     * @return array|bool
     */
    public function createEmp($data)
    {
        $model = new self();
        $model->attributes = $data;
        if ($model->validate() && $model->save()) {
            return true;
        }
        return $model->getErrors();
    }

    /**
     * 处理保存前的数据
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->job_number = self::find()->max('job_number') + 1;
            $this->password = $this->setPassword($this->password);
            $this->is_delete = self::DELETE_NOT;
            $this->is_lock = self::LOCK_NOT;
            $this->is_admin = self::SUPER_ADMIN_NO;
            $this->created_at = time();
            $this->updated_at = time();
        } else {
            $this->updated_at = time();
        }
        return parent::beforeSave($insert);
    }

    /**
     * 拼接sql 用于查询
     * @param $params
     * @return $this
     */
    public function getQuery($params)
    {
        return $query = self::find()
            ->where(['is_delete' => self::DELETE_NOT])
//            ->andWhere(['is_admin' => self::SUPER_ADMIN_NO])
            ->andFilterWhere(['like','job_number' , $params['job_number']])
            ->andFilterWhere(['like', 'username', $params['username']])
            ->andFilterWhere(['like', 'email', $params['email']])
            ->andFilterWhere(['like', 'mobile', $params['mobile']])
            ->andFilterWhere(['like', 'is_lock', $params['is_lock']]);
    }

    /**
     * 修改员工信息
     * @param $params
     * @return array|bool
     */
    public function modifyEmp($params)
    {
//        $this->scenario = 'update';
        $this->attributes = $params;
        if (!empty($params['password'])) {
            $this->password = $this->setPassword($this->password);
        }
        if ($this->save()) {
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
        $page_size = Param::getParamFromGet('page_size');
        $page = Param::getParamFromGet('page');
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
        // 增加一个 Pragma 头，已存在的Pragma 头不会被覆盖。
        $param[ConstantHelper::COUNT] = $count;
        $param[ConstantHelper::PAGE] = $page;
        $param[ConstantHelper::PAGE_SIZE] = $page_size;
        $param[ConstantHelper::PAGE_COUNT] = ceil($count / $page_size);
        $param[ConstantHelper::LISTS] = $list;
        return self::backListFormat($param);
    }

    /**
     * 删除员工
     * @return array|bool
     */
    public function del()
    {
        $this->scenario = 'delete';
        $this->is_delete = self::DELETED;
        if ($this->save()) {
            return true;
        }
        return $this->getErrors();
    }

    /**
     * 冻结员工
     * @return array|bool
     */
    public function lock()
    {
        $this->scenario = 'lock';
        if ($this->is_lock == ConstantHelper::IS_LOCK_TRUE) {
            $this->is_lock = ConstantHelper::IS_LOCK_FALSE;
        } else {
            $this->is_lock = ConstantHelper::IS_LOCK_TRUE;
        }
        if ($this->save()) {
            return true;
        }
        return $this->getErrors();
    }


    /**
     * 修改密码
     * @return array|bool
     */
    public function changePassword($param)
    {
        if (empty($param['password'])) {
            $this->addError('password', '新密码必填');
        }
        if (empty($param['oldpassword'])) {
            $this->addError('oldpassword', '旧密码必填');
        }
        if (!empty($param['oldpassword']) && $this->password != $this->setPassword($param['oldpassword'])) {
            $this->addError('oldpassword', '旧密码错误');
        }
        if (!empty($this->getErrors())) {
            return $this->getErrors();
        }
        $this->password = $param['password'];
        $this->scenario = 'change-password';
        if (!$this->validate()) {
            return $this->getErrors();
        }
        $this->password = $this->setPassword($param['password']);
        if ($this->save()) {
            return true;
        }
        return $this->getErrors();
    }

    public function getAuth()
    {
        $role_ids = explode(',', $this->role_ids);
        $auth_ids = [];
        foreach ($role_ids as $role_id) {
            $roleModel = Role::findOne($role_id);
            if ($roleModel != null) {
                $auth_ids = array_merge($auth_ids, explode(',', $roleModel->auth_ids));
            }
        }
        $auth_ids = array_values(array_filter(array_unique($auth_ids)));
        sort($auth_ids);
        $auths=[];
        foreach ($auth_ids as $auth_id) {
            $authModel = Auth::findOne(['id' => $auth_id, 'is_delete' => ConstantHelper::IS_DELETE_FALSE]);
            if ($authModel!=null){
                $auths[]=[
                    'id'=>$authModel->id,
                    'auth_name'=>$authModel->auth_name,
                    'route_url'=>$authModel->route_url,
                    'is_lock'=>$authModel->is_lock,
                ];
            }
        }
       return $auths;
    }
}

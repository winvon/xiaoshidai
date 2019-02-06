<?php
/**
 **********************************************************
 * @author : YiChaobao [ yichaobao@163.com ]
 * @time : 2019/01/14 15:02
 * @copyright : (c) 2019 YiChaobao All rights reserved.
 **********************************************************
 * @name : UserCert.php
 * @description : 用户证书表
 **/

namespace backend\models;

use Yii;
use  yii\behaviors\TimestampBehavior;
use common\helpers\ConstantHelper;
use yii\data\Pagination;
class UserCert extends \backend\models\BaseModel
{
    public static function tableName()
    {
        return '{{%user_cert}}';
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
            'user_id' => '会员编号',
            'user_role_id' => '会员角色',
            'username' => '姓名',
            'id_card' => '证件号',
            'cert_no' => '证书编号',
            'user_info' => '用户快照',
            'counts' => '生成次数',
            'created_at' => '生成时间',
            'updated_at' => '更新时间',
        ];
    }

    public function rules()
    {
        return [
            [['user_id', 'user_role_id', 'username','id_card','cert_no','user_info','counts'], 'required', 'message' => '{attribute}不能为空'],//必填字段 【创建|更新】
            ['cert_no', 'unique', 'targetClass' => '\backend\models\UserCert', 'message' => '该证书编号已使用'],
        ];
    }

    public function create_data($data)
    {
        $last_cert_info = self::find()->orderBy('cert_no Desc')->one();
        $cert_no = '1000000';
        if(!empty($last_cert_info['cert_no'])){
            $cert_no = (int)$last_cert_info['cert_no']+1;
        }

        $data['cert_no'] = $cert_no;
        $data['counts'] = 0;
        $user_info = User::find()->where(['id'=>$data['user_id']])->one();
        $data['user_info'] = json_encode($user_info);


        $model = new self();

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

    public function info_data($id)
    {
        $info = self::findOne($id);
        if ($info) {
            return [
                'status' => true,
                'data' => $info,
            ];
        }
        return [
            'status' => false,
            'error_code' => 50201
        ];
    }

    public function update_data($id, $data)
    {
        $info = self::findOne($id);
        if ($info) {
            unset($data['cert_no']);
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
            'error_code' => 50201
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
            'error_code' => 50201
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
        $lists = $model->select(['c.id', 'c.user_id','c.id_card','cert_no', 'counts', 'c.created_at', 'c.updated_at','c.user_role_id', 'mobile', 'c.username'])->orderBy($order)
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
        $_lists = [];
        foreach ($lists as $key => $value) {
//            $image_one = ProductImage::findOne([
//                'product_id'=>$value['id']
//            ]);
//            if(!empty($image_one)){
//                $value['image_path']=$image_one['image_path'];
//            }
            $value['user_role_name']="普通会员";
            $_lists[] = $value;
        }

        $param[ConstantHelper::COUNT] = (int)$pagination->totalCount;
        $param[ConstantHelper::LISTS] = $_lists;
        return self::backListFormat($param);
    }

    public function getQuery($where)
    {
        $query = self::find()->alias('c')
            ->leftJoin('{{%user}}', 'c.user_id = {{%user}}.id')
//            ->leftJoin('{{%category}}', 'g.category_id = {{%category}}.id')
            ->where(['c.is_delete' => self::DELETE_NOT])
            ->andFilterWhere(['like', 'c.username', $where['username']])
            ->andFilterWhere(['like', 'c.id_card', $where['id_card']])
            ->andFilterWhere(['like', 'mobile', $where['mobile']])
            ->andFilterWhere(['user_role_id' => $where['user_role_id']]);

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
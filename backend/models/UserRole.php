<?php
/**
 **********************************************************
 * @author : YiChaobao [ yichaobao@163.com ]
 * @time : 2019/01/14 10:25
 * @copyright : (c) 2019 YiChaobao All rights reserved.
 **********************************************************
 * @name : UserRole.php
 * @description :
 **/

namespace backend\models;

use Yii;
use  yii\behaviors\TimestampBehavior;
use common\helpers\ConstantHelper;

class UserRole extends \backend\models\BaseModel
{
    public static function tableName()
    {
        return '{{%user_role}}';
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
            'user_role_name' => '角色名称',
            'mark' => '标识',
            'role_icon' => 'ICON',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function rules()
    {
        return [
            [['user_role_name', 'mark', 'role_icon'], 'required', 'message' => '{attribute}不能为空'],//必填字段 【创建|更新】
            ['mark', 'unique', 'targetClass' => '\backend\models\UserRole', 'message' => '该标识已使用'],
        ];
    }

    /**
     * @desc: 返回全部数据列表
     * @name: lists_data
     * @return UserRole[]
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
    public function lists_data()
    {
        return self::find()->orderBy('created_at DESC')->all();
    }

    /**
     * @desc: 创建
     * @name: create_data
     * @param $data
     * @return array
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
    public function create_data($data)
    {
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

    /**
     * @desc: 更新
     * @name: update_data
     * @param $id
     * @param $data
     * @return array
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
    public function update_data($id, $data)
    {
        $info = self::findOne($id);
        if ($info) {
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
            'error_code' => 50101
        ];
    }

    /**
     * @desc: 返回详情
     * @name: info_data
     * @param $id
     * @return array
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
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
            'error_code' => 50101
        ];
    }

    /**
     * @desc: 删除数据
     * @name: delete_data
     * @param $id
     * @return array
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
    public function delete_data($id)
    {
        $info = self::findOne($id);
        if ($info) {
            if ($info->delete()) {
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
            'error_code' => 50101
        ];
    }
}
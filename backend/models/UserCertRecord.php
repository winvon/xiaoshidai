<?php
/**
 **********************************************************
 * @author : YiChaobao [ yichaobao@163.com ]
 * @time : 2019/01/14 17:15
 * @copyright : (c) 2019 YiChaobao All rights reserved.
 **********************************************************
 * @name : UserCertRecord.php
 * @description :user_cert_record
 **/
namespace backend\models;

use Yii;
use  yii\behaviors\TimestampBehavior;
use common\helpers\ConstantHelper;
class UserCertRecord extends \backend\models\BaseModel
{
    public static function tableName()
    {
        return '{{%user_cert_record}}';
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
            'user_id' => '用户编号',
            'user_cert_id' => '证书ID',
            'cert_file_path' => '证书路径',
            'user_info' => '用户快照信息',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function rules()
    {
        return [
            [['user_id', 'user_cert_id', 'cert_file_path','user_info'], 'required', 'message' => '{attribute}不能为空'],//必填字段
        ];
    }

    public function getLists($user_cert_id){
        $model = self::find();
        $totalCount = $model->count();
        $lists = $model->where(['user_cert_id'=>$user_cert_id])->orderBy('created_at DESC')->all();
        $param[ConstantHelper::COUNT] = (int)$totalCount;
        $param[ConstantHelper::LISTS] = $lists;
        return self::backListFormat($param);
    }
}
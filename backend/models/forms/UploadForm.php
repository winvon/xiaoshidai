<?php
/**
 * Created by von.
 * User: FOCUS
 * Date: 2019/1/4
 * Time: 17:46
 */

namespace backend\models\forms;

use yii\base\Model;
use yii\web\UploadedFile;

/**
 * 图片上传
 * Class uploadFormextends
 * @package backend\models\forms
 * @author von
 */
class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $file;

    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => ['jpg', 'jpeg', 'gif', 'png'],'maxFiles' => 4],
        ];
    }

    public function upload()
    {

        if ($this->validate()) {
            $type=\Yii::$app->request->get('type');
            switch ((int)$type) {
                case 1:$file_name = 'profile';break;
                case 2 :$file_name =  'banner';break;
                case 3 :$file_name = 'goods';break;
                default:return false;break;
            }
            $new_file = "upload/" . $file_name . "/" . date('Ymd', time()) . "1/";
            if (!file_exists($new_file)) {
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0777, true);
            }
            $file_path=$new_file . $this->imageFile->extension;
            $this->imageFile->saveAs($file_path);
            return $file_path;
        } else {
            return $this->getErrors();
        }
    }
}
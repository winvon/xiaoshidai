<?php
/**
 * Created by PhpStorm.
 * User: FOCUS
 * Date: 2018/12/24
 * Time: 15:32
 */

namespace common\helpers;
use Yii;
class Upload
{
    /**文件类型**/
    public $type = '';

    /**文件后缀名**/
    public $extension = '';//得到文件后缀，并且都转化成小写

    /**文件类容**/
    public $content;

    /**文件大小**/
    public $size;

    /**文件夹路径**/
    public $file_path = '';

    /**返回的文件名**/
    public $file_name = '';

    /**
     * Upload constructor.
     * @param $content
     */
    public function __construct($file)
    {
        $this->content = $file['tmp_name'];
        $this->type = explode('/', $file['type'])[0];
        $this->size = $file['size'];
        $this->extension = strtolower(substr($file['name'], strrpos($file['name'], '.') + 1));
    }

    /**
     * 保存文件
     * @return bool|string
     * @author von
     */
    public function save()
    {
        if ($this->createFile() !== true){
            Yii::error("创建文件失败");
            return ['创建文件失败'];
        }
        return self::saveFile();
    }

    /**
     * 保存文件
     * @return bool|string
     * @author von
     */
    public function saveFile()
    {
        $this->file_name = $this->file_path . rand(1000, 9999) . time() . ".{$this->extension}";
        if (move_uploaded_file($this->content, $this->file_name)) {
            return '/' . $this->file_name;
        }
        Yii::error("保存文件失败");
        return  ['保存文件失败'];
    }

    /**
     * 创建文件夹
     * @param $type
     * @return string
     * @author von
     */
    public function createFile()
    {
        $this->file_path = "upload/" . $this->type . "/" . date('Ymd', time()) . "/";
        if (!file_exists($this->file_path)) {
            //检查是否有该文件夹,如果没有就创建，并给予最高权限
            mkdir($this->file_path, 0777, true);
        };
        return true;
    }

    public function configImage()
    {
        return;
    }

    public function configFile()
    {

    }

    public function configVideo()
    {
    }

    public function configudio()
    {
    }


    public static function base64_image_content($base64_image_content, $file_name)
    {
        //匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
            $type = $result[2];
            $new_file = "upload/" . $file_name . "/" . date('Ymd', time()) . "1/";
            if (!file_exists($new_file)) {
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0777, true);
            }
            $new_file = $new_file . rand(1000, 9999) . time() . ".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                return '/' . $new_file;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function uploadByFile($file, $file_name)
    {
        $name = $file['name'];
        $type = strtolower(substr($name, strrpos($name, '.') + 1)); //得到文件类型，并且都转化成小写
        $allow_type = array('jpg', 'jpeg', 'gif', 'png');
        if (!in_array($type, $allow_type)) {
            //如果不被允许，则直接停止程序运行
            return false;
        }
        if (!is_uploaded_file($file['tmp_name'])) {
            return false;
        }
        $new_file = "upload/image/" . $file_name . "/" . date('Ymd', time()) . "/";
        if (!file_exists($new_file)) {
            //检查是否有该文件夹,如果没有就创建，并给予最高权限
            mkdir($new_file, 0777, true);
        }
        $file_path = $new_file . rand(1000, 9999) . time() . ".{$type}";
        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            return '/' . $file_path;
        }
        return false;
    }

}
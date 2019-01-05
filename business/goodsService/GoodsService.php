<?php
/**
 **********************************************************
 * @author : YiChaobao [ yichaobao@163.com ]
 * @time : 2019/01/01 00:00
 * @copyright : (c) 2019 YiChaobao All rights reserved.
 **********************************************************
 * @name : ProductService.php
 * @description : 产品服务
 **/
namespace business\goodsService;
use Yii;
use common\helpers\ErrorCode;
use common\helpers\CodeHelper;
use common\helpers\WeHelper;
use common\helpers\BackendErrorCode;

use business\interfaceService\goods\IGoodsService;
use backend\models\Goods;
use backend\models\GoodsImage;
use backend\models\GoodsFile;
class GoodsService implements IGoodsService
{
    private $model;

    public function __construct()
    {
        $this->model = new Goods();
    }

    public function addGoods($data = []){
        $addGoodsTransaction = Yii::$app->db->beginTransaction();
        try {
            $res = $this->model->create_data($data);
            if ($res['status'] === true) {
                if(empty($data['image_lists'])){
                    return WeHelper::jsonReturn([],3010);
                }
                $goods_image = $data['image_lists'];
                foreach ($goods_image as $goods_image_item) {
                    $goods_image_item['goods_id'] = $res['data']['id'];
                    $GoodsImageModel = new GoodsImage();
                    $goods_image_item_res = $GoodsImageModel->create_data($goods_image_item);
                    if($goods_image_item_res['status'] !== true){
                        return WeHelper::jsonReturn($goods_image_item_res['data'],ErrorCode::ERR_MODEL_VALIDATE);
                    }
                }
                if(isset($data['file_lists'])){
                    $goods_file = $data['file_lists'];
                    foreach ($goods_file as $goods_file_item) {
                        $goods_file_item['goods_id'] = $res['data']['id'];
                        $goodsFileModel = new GoodsFile();
                        $goods_file_item_res = $goodsFileModel->create_data($goods_file_item);
                        if($goods_file_item_res['status'] !== true){
                            return WeHelper::jsonReturn($goods_file_item_res['data'],BackendErrorCode::ERR_MODEL_VALIDATE);
                        }
                    }
                }
            } else {
                return WeHelper::jsonReturn($res['data'],BackendErrorCode::ERR_MODEL_VALIDATE);
            }
            $addGoodsTransaction->commit();
            return WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_SUCCESS);
        } catch (\Exception $e) {
            $addGoodsTransaction->rollBack();
            return WeHelper::jsonReturn($e, BackendErrorCode::ERR_DB);
        }
    }

    /**
     * @desc: 更新商品
     * @name: updateGoods
     * @param null $goods_id
     * @param array $data
     * @return array|string
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
    public function updateGoods($goods_id = null, $data = [])
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $res = $this->model->update_data($goods_id, $data);
            if ($res['status'] === true) {
                $goods_image = $data['image_lists'];
                if(empty($goods_image)){
                    return WeHelper::jsonReturn([],3010);
                }
                $goods_images_all = [];
                foreach ($goods_image as $goods_image_item) {
                    $goods_image_item['goods_id'] = $goods_id;
                    $GoodsImageModel = new GoodsImage();
                    if(empty($goods_image_item['id'])){
                        $goods_image_item_res = $GoodsImageModel->create_data($goods_image_item);
                    }else{
                        $goods_image_item_res = $GoodsImageModel->update_data($goods_image_item);
                    }
                    if($goods_image_item_res['status'] !== true){
                        return WeHelper::jsonReturn($goods_image_item_res['data'],ErrorCode::ERR_MODEL_VALIDATE);
                    }else{
                        $goods_images_all[] = $goods_image_item_res['data'];
                    }
                }
                $delGoodsImageModel = new GoodsImage();
                $delGoodsImageModel->delete_notin_data($goods_images_all,$goods_id);

                $goods_file = $data['file_lists'];

                if(isset($goods_file)){
                    $goods_file_all = [];
                    foreach ($goods_file as $goods_file_item) {
                        $goods_file_item['goods_id'] = $goods_id;
                        $goodsFileModel = new GoodsFile();
                        if(empty($goods_image_item['id'])){
                            $goods_file_item_res = $goodsFileModel->create_data($goods_file_item);
                        }else{
                            $goods_file_item_res = $goodsFileModel->update_data($goods_file_item);
                        }

                        if($goods_file_item_res['status'] !== true){
                            return WeHelper::jsonReturn($goods_file_item_res['data'],ErrorCode::ERR_MODEL_VALIDATE);
                        }else{
                            $goods_file_all[] = $goods_file_item_res['data'];
                        }
                    }
                    $delGoodsFileModel = new GoodsFile();
                    $delGoodsFileModel->delete_notin_data($goods_file_all,$goods_id);
                }else{
                    $delGoodsFileModel = new GoodsFile();
                    $delGoodsFileModel->delete_goods_all($goods_id);
                }




                $transaction->commit();
                return WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_SUCCESS);
            } else {
                return WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_MODEL_VALIDATE);
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            var_dump(1);
            die();
            return WeHelper::jsonReturn($e, BackendErrorCode::ERR_DB);
        }
    }

    public function deleteGoods($goods_id = null)
    {
        try {
            $res = $this->model->delete_data($goods_id);
            if ($res['status'] === true) {
                $return_data = WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_SUCCESS);
            } else {
                $return_data = WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_MODEL_VALIDATE);
            }
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return $return_data;
    }

    public function getGoodsInfo($user_id = ''){
        try {
            $res = $this->model->info_data($user_id);
            if ($res['status'] === true) {
                $return_data = WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_SUCCESS);
            } else {
                $return_data = WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_MODEL_VALIDATE);
            }
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return $return_data;
    }

    public function getGoodsList($where = [],$order = 'created_at desc',$pageSize = 20){
        try {
            $res = $this->model->lists_data($where,$order,$pageSize);
            return WeHelper::jsonReturn($res, BackendErrorCode::ERR_SUCCESS);
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
    }

}
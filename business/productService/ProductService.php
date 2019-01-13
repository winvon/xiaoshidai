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

namespace business\productService;

use Yii;
use common\helpers\ErrorCode;
use common\helpers\WeHelper;
use common\helpers\BackendErrorCode;

use business\interfaceService\product\IProductService;
use backend\models\Product;
use backend\models\ProductImage;

class ProductService implements IProductService
{
    private $model;

    public function __construct()
    {
        $this->model = new Product();
    }

    public function addProduct($data = [])
    {
        $addGoodsTransaction = Yii::$app->db->beginTransaction();
        try {
            $res = $this->model->create_data($data);
            if ($res['status'] === true) {
                if (empty($data['image_lists'])) {
                    return WeHelper::jsonReturn([], 3010);
                }
                $goods_image = $data['image_lists'];
                foreach ($goods_image as $goods_image_item) {
                    $goods_image_item['product_id'] = $res['data']['id'];
                    $GoodsImageModel = new ProductImage();
                    $goods_image_item_res = $GoodsImageModel->create_data($goods_image_item);
                    if ($goods_image_item_res['status'] !== true) {
                        return WeHelper::jsonReturn($goods_image_item_res['data'], ErrorCode::ERR_MODEL_VALIDATE);
                    }
                }
            } else {
                return WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_MODEL_VALIDATE);
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
    public function updateProduct($goods_id = null, $data = [])
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $res = $this->model->update_data($goods_id, $data);
            if ($res['status'] === true) {
                if (empty($data['image_lists'])) {
                    return WeHelper::jsonReturn([], 3010);
                }
                $goods_image = $data['image_lists'];
                ProductImage::deleteAll(['product_id' => $goods_id]);

                foreach ($goods_image as $goods_image_item) {
                    $goods_image_item['product_id'] = $goods_id;
                    $GoodsImageModel = new ProductImage();
                    $goods_image_item_res = $GoodsImageModel->create_data($goods_image_item);
                    if ($goods_image_item_res['status'] !== true) {
                        return WeHelper::jsonReturn($goods_image_item_res['data'], ErrorCode::ERR_MODEL_VALIDATE);
                    }
                }
                $transaction->commit();
                return WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_SUCCESS);
            } else {
                return WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_MODEL_VALIDATE);
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return WeHelper::jsonReturn($e, BackendErrorCode::ERR_DB);
        }
    }

    public function deleteProduct($goods_id = null)
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

    public function getProductInfo($goods_id = '')
    {
        try {
            $res = $this->model->info_data($goods_id);
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


    public function getProductList($where = [], $order = 'created_at desc', $pageSize = 20)
    {
        try {
            $res = $this->model->lists_data($where, $order, $pageSize);
            return WeHelper::jsonReturn($res, BackendErrorCode::ERR_SUCCESS);
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
    }

}
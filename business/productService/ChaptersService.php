<?php
/**
 **********************************************************
 * @author : YiChaobao [ yichaobao@163.com ]
 * @time : 2019/01/01 00:00
 * @copyright : (c) 2019 YiChaobao All rights reserved.
 **********************************************************
 * @name : ItemService.php
 * @description : 商品分集
 **/

namespace business\productService;

use Yii;
use common\helpers\ErrorCode;
use common\helpers\CodeHelper;
use common\helpers\WeHelper;
use common\helpers\BackendErrorCode;
use business\interfaceService\product\IChaptersService;
use backend\models\ProductChapters;
use backend\models\ProductChaptersFile;

class ChaptersService implements IChaptersService
{
    private $model;

    public function __construct()
    {
        $this->model = new ProductChapters();
    }

    /**
     * @desc: 添加商品分集
     * @name: addGoodsItem
     * @param array $data
     * @return array|string
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
    public function addProductChapters($data = [])
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $res = $this->model->create_data($data);
            if ($res['status'] === true) {
                if (!empty($data['file_lists'])) {
                    $file_lists = $data['file_lists'];
                    foreach ($file_lists as $file_lists_item) {
                        $file_lists_item['product_chapter_id'] = $res['data']['id'];
                        $file_lists_item['product_id'] = $res['data']['product_id'];
                        $productChaptersFileModel = new ProductChaptersFile();
                        $file_lists_item_res = $productChaptersFileModel->create_data($file_lists_item);
                        if ($file_lists_item_res['status'] !== true) {
                            return WeHelper::jsonReturn($file_lists_item_res['data'], ErrorCode::ERR_MODEL_VALIDATE);
                        }
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

    public function getGoodsItemInfo($goods_item_id = '')
    {
        try {
            $res = $this->model->info_data($goods_item_id);
            if ($res['status'] === true) {
                return WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_SUCCESS);
            } else {
                return WeHelper::jsonReturn($res['data'], $res['status']);
            }
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
    }

    public function updateProductChapters($goods_item_id = null, $data = [])
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $res = $this->model->update_data($goods_item_id, $data);
            if ($res['status'] === true) {
                ProductChaptersFile::deleteAll(['product_chapter_id' => $goods_item_id]);
                if (!empty($data['file_lists'])) {
                    $file_lists = $data['file_lists'];
                    foreach ($file_lists as $file_lists_item) {
                        $file_lists_item['product_chapter_id'] = $res['data']['id'];
                        $file_lists_item['product_id'] = $res['data']['product_id'];
                        $productChaptersFileModel = new ProductChaptersFile();
                        $file_lists_item_res = $productChaptersFileModel->create_data($file_lists_item);
                        if ($file_lists_item_res['status'] !== true) {
                            return WeHelper::jsonReturn($file_lists_item_res['data'], ErrorCode::ERR_MODEL_VALIDATE);
                        }
                    }
                }
                $transaction->commit();
                return WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_SUCCESS);
            } else {
                return WeHelper::jsonReturn($res['data'], $res['status']);
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
    }

    public function deleteProductChapters($goods_item_id = null)
    {
        try {
            $res = $this->model->delete_data($goods_item_id);
            if ($res['status'] === true) {
                return WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_SUCCESS);
            } else {
                return WeHelper::jsonReturn($res['data'], $res['status']);
            }
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
    }

    public function getProductChaptersList($where = [], $order = 'created_at desc', $pageSize = 20)
    {
        try {
            $res = $this->model->lists_data($where, $order, $pageSize);
            return WeHelper::jsonReturn($res, BackendErrorCode::ERR_SUCCESS);
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
    }

}
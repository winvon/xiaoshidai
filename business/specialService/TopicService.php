<?php
/**
 **********************************************************
 * @author : YiChaobao [ yichaobao@163.com ]
 * @time : 2019/01/16 10:27
 * @copyright : (c) 2019 YiChaobao All rights reserved.
 **********************************************************
 * @name : TopicService.php
 * @description :
 **/

namespace business\specialService;

use Yii;
use business\interfaceService\special\ITopicService;
use common\helpers\WeHelper;
use common\helpers\BackendErrorCode;
use backend\models\Topic;
use backend\models\TopicProduct;
class TopicService implements ITopicService
{
    private $model;
    private $topicProductModel;
    public function __construct()
    {
        $this->model = new Topic();
        $this->topicProductModel = new TopicProduct();
    }

    public function addData($data){
        $transaction = Yii::$app->db->beginTransaction();
//        var_dump($data);
//        $res = $this->model->create_data($data);
        try {
            $res = $this->model->create_data($data);
            if ($res['status'] === true) {
                if(!empty($data['product_list'])){//添加关联商品
                    foreach ($data['product_list'] as $datum) {
                        $datum['topic_id'] = $res['data']['id'];
                        $topic_product_res = $this->topicProductModel->create_data($datum);
//                        var_dump($topic_product_res);
//                        die();
                        if($topic_product_res['status'] !== true){
//                            var_dump($topic_product_res);
                            $transaction->rollBack();
                            return WeHelper::jsonReturn($topic_product_res['data'], BackendErrorCode::ERR_MODEL_VALIDATE);
                        }
                    }
                }
//                die();
                $transaction->commit();
                return WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_SUCCESS);
            } else {
                $transaction->rollBack();
                return WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_MODEL_VALIDATE);
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
    }

    public function getInfo($id){
        try {
            $res = $this->model->info_data($id);
            if ($res['status'] === true) {
                $product_list_res = $this->topicProductModel->list_data($id);
                $coupon_list = $this->model->coupon_list($res['data']['coupon_ids']);/*获取优惠券详情*/
                $res['data']['product_list'] = $product_list_res;
                $res['data']['coupon_list'] = $coupon_list;
                $return_data = WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_SUCCESS);
            } else {
                $return_data = WeHelper::jsonReturn([], $res['error_code']);
            }
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return $return_data;
    }

    public function deleteData($id){
        try {
            $res = $this->model->delete_data($id);
            if ($res['status'] === true) {
                $return_data = WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_SUCCESS);
            } else {
                $return_data = WeHelper::jsonReturn(null, $res['error_code']);
            }
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return $return_data;
    }

    public function lockStatus($id,$status=0){
        try {
            $res = $this->model->lock_data($id,$status);
            if ($res['status'] === true) {
                $return_data = WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_SUCCESS);
            } else {
                return WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_MODEL_VALIDATE);
            }
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
        return $return_data;
    }

    public function updateData($id, $data){
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $res = $this->model->update_data($id,$data);
            $product_list_ids = [];
            if ($res['status'] === true) {
                if(!empty($data['product_list'])){//添加关联商品
                    foreach ($data['product_list'] as $datum) {
                        $datum['topic_id'] = $id;
                        $topic_product_res = $this->topicProductModel->save_data($datum);
                        if($topic_product_res['status'] !== true){
                            $transaction->rollBack();
                            return WeHelper::jsonReturn($topic_product_res['data'], BackendErrorCode::ERR_MODEL_VALIDATE);
                        }else{
                            $product_list_ids[] = $topic_product_res['data']['id'];
                        }
                    }
                }
//                return $product_list_ids;
                if(count($product_list_ids)>0){
                    $this->topicProductModel->deleteAllData($id,$product_list_ids);
                }
                $transaction->commit();
                return WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_SUCCESS);
            } else {
                $transaction->rollBack();
                return WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_MODEL_VALIDATE);
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
    }


    public function getListData($where = [], $order = 'created_at desc', $pageSize = 20)
    {
        try {
            $res = $this->model->lists_data($where, $order, $pageSize);
            return WeHelper::jsonReturn($res, BackendErrorCode::ERR_SUCCESS);
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
    }
}
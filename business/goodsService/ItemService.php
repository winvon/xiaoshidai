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
namespace business\goodsService;
use Yii;
use common\helpers\ErrorCode;
use common\helpers\CodeHelper;
use common\helpers\WeHelper;
use common\helpers\BackendErrorCode;
use business\interfaceService\goods\IItemService;
use backend\models\Goods;
use backend\models\GoodsItem;
class ItemService implements IItemService
{
    private $model;
    public function __construct()
    {
        $this->model = new GoodsItem();
    }

    /**
     * @desc: 添加商品分集
     * @name: addGoodsItem
     * @param array $data
     * @return array|string
     * @author：yichaobao [yichaobao@163.com]
     * @version : V1.0.0
     */
    public function addGoodsItem($data = []){
        try {
            $res = $this->model->create_data($data);
            if ($res['status'] === true) {
                return WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_SUCCESS);
            } else {
                return WeHelper::jsonReturn($res['data'],BackendErrorCode::ERR_MODEL_VALIDATE);
            }
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
    }

    public function getGoodsItemInfo($goods_item_id = ''){
        try {
            $res = $this->model->info_data($goods_item_id);
            if ($res['status'] === true) {
                return WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_SUCCESS);
            } else {
                return WeHelper::jsonReturn($res['data'],$res['status']);
            }
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
    }

    public function updateGoodsItem($goods_item_id = null, $data = [])
    {
        try {
            $res = $this->model->update_data($goods_item_id, $data);
            if ($res['status'] === true) {
                return WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_SUCCESS);
            } else {
                return WeHelper::jsonReturn($res['data'],$res['status']);
            }
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
    }

    public function deleteGoodsItem($goods_item_id = null)
    {
        try {
            $res = $this->model->delete_data($goods_item_id);
            if ($res['status'] === true) {
                return WeHelper::jsonReturn($res['data'], BackendErrorCode::ERR_SUCCESS);
            } else {
                return WeHelper::jsonReturn($res['data'],$res['status']);
            }
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
    }

    public function getGoodsItemList($where = [],$order = 'created_at desc',$pageSize = 20){
        try {
            $res = $this->model->lists_data($where,$order,$pageSize);
            return WeHelper::jsonReturn($res, BackendErrorCode::ERR_SUCCESS);
        } catch (\Exception $e) {
            return WeHelper::jsonReturn(null, BackendErrorCode::ERR_DB);
        }
    }

}
<?php


namespace app\common\event;

use app\common\model\Brand;
use think\Db;
use app\common\model\Goods;

class GoodsEvent extends BaseEvent
{

    public function setState($brandID, $state) {
        if ($state != 0 && $state != 1) {
            return array_err(1951299, '状态设置错误');
        }

        //检查数据是否存在
        $mGoods = new Goods();
        $brandRes = array();
        $flag = $this->checkGoodsID($brandID, $brandRes, true);

        if ($flag['code'] > 0) {
            return $flag;
        }

        if ($state == $brandRes[$brandID]['status']) {
            return array_err(1951296, '状态没有发生变化,不需要修改');
        }

        $editFlag = $mGoods->save([
            'status' => $state,
        ], ['id' => $brandID]);

        if ($editFlag === false) {
            return array_err(1951296, '修改状态失败');
        }

        return array_err(0, '修改状态成功');
    }

    /**
     * 检查品牌是否正确根据主键
     * @param $brandID
     * @param $brandRes
     * @param bool $lock
     * @return array
     */
    public function checkGoodsID($brandID, &$brandRes, $lock = false) {
        $mGoods = new Goods();
        if (empty($brandID)) {
            return array_err(1951298, '品牌标识不能为空');
        }
        $brandRes = $mGoods->getGoodsCateByKV($brandID, $lock);

        if (count($brandID) != count($brandRes)) {
            return array_err(1951297, '存在非法数据');
        }

        if (empty($brandRes)) {
            return array_err(1951296, '没有查找到您要的标识');
        }
        return array_err(0, 'success');
    }

    /**
     * 修改品牌数据
     * @param $brandID
     * @param $data
     * @return array
     */
    public function editGoods($brandID, $data) {
        $brandRes = array();
        $flag = $this->checkGoodsID($brandID, $brandRes, true);
        if ($flag['code'] > 0) {
            return $flag;
        }

        $flag = $this->checkGoodsData($data, $brandID);

        if ($flag['code'] > 0) {
            return $flag;
        }

        //这边判断数据是否有变化
        foreach ($data as $k => $v) {
            if ($v == $brandRes[$brandID][$k]) {
                unset($data[$k]);
            }
        }

        if (empty($data)) {
            return array_err(0, '数据没有发生变化无需要修改');
        }

        $mGoods = new Goods();

        $editFlag = $mGoods->save($data, ['id' => $brandID]);

        if ($editFlag === false) {
            return array_err(1951296, '修改品牌数据失败');
        }

        return array_err(0, '修改品牌数据成功');
    }


    /**
     * 数据排序
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function setSort($data) {
        $brandRes = array();

        $brandID = array_keys($data);
        $flag = $this->checkGoodsID($brandID, $brandRes, true);
        if ($flag['code'] > 0) {
            return $flag;
        }

        $sortRes = array();
        foreach ($data as $k => $v) {
            if ($v == $brandRes[$k]['sort']) {
                continue;
            }
            $sortRes['id'] = $k;
            $sortRes['sort'] = $v;
            $editFlag = Db::name('category')->update($sortRes);

            if ($editFlag === false) {
                return array_err(1951296, '修改数据排序失败');
            }
        }

        return array_err(0, '修改排序数据成功');
    }

    public function addGoods($goodsBase, $goodsPrice, $goodsAttr){
        $dataRes = $this->_addGoods($goodsBase, $goodsPrice, $goodsAttr);
        if($dataRes['code']  > 0){
            return $dataRes;
        }

        $mGoods = new Goods();

        $data['goods_name'] = $dataRes['goods_base']['goods_name'];
        $data['markte_price'] = $dataRes['goods_base']['markte_price'];
        $data['shop_price'] = $dataRes['goods_base']['shop_price'];
        $data['goods_weight'] = $dataRes['goods_base']['goods_weight'];
        $data['category_id'] = $dataRes['goods_base']['category_id'];
        $data['brand_id'] = $dataRes['goods_base']['brand_id'];
        $data['on_sale'] = $dataRes['goods_base']['on_sale'];
        $data['og_thumb'] = $dataRes['goods_base']['og_thumb'];
        $data['goods_name'] = $dataRes['goods_base']['goods_name'];
        $data['goods_name'] = $dataRes['goods_base']['goods_name'];
        $data['goods_name'] = $dataRes['goods_base']['goods_name'];
        $data['goods_des'] = $dataRes['goods_base']['goods_des'];
        $editFlag = $mGoods->save($data);


        $data['goods_name'] = $dataRes['goods_price']['goods_name'];

        $data['goods_name'] = $dataRes['goods_attr']['goods_name'];

        if ($editFlag === false) {
            return array_err(1951296, '添加品牌数据失败');
        }

        return array_err(0, '添加品牌数据成功');
    }

    /**
     * 添加商品数据
     * @param $goodsBase
     * @param $goodsPrice
     * @param $goodsAttr
     * @return array
     */
    protected function _addGoods($goodsBase, $goodsPrice, $goodsAttr) {
        $checkGoodsFlag = $this->checkGoodsData($goodsBase);
        if($checkGoodsFlag['code'] > 0 ){
            return $checkGoodsFlag;
        }
        if ($goodsBase['og_thumb']) {
            $pathInfo = pathinfo($goodsBase['og_thumb']);
            $goodsBase['sm_thumb'] = $pathInfo['dirname'].'/sm_'.$pathInfo['basename'];
            $goodsBase['mid_thumb'] = $pathInfo['dirname'].'/mid_'.$pathInfo['basename'];
            $goodsBase['big_thumb'] = $pathInfo['dirname'].'/big_'.$pathInfo['basename'];
        }

        $checkGoodsPriceFlag = $this->checkGoodsPrice($goodsPrice);
        if($checkGoodsPriceFlag['code'] > 0 ){
            return $checkGoodsPriceFlag;
        }
        $checkGoodsAttrFlag = $this->checkGoodsAttr($goodsAttr);

        if($checkGoodsAttrFlag['code'] > 0 ){
            return $checkGoodsAttrFlag;
        }
        $data = array_err(0,'success');

        $data['goods_base'] = $goodsBase;
        $data['goods_price'] = $goodsPrice;
        $data['goods_attr'] = $goodsAttr;

        return $data;
    }

    /**
     * 删除品牌数据
     * @param $brandID
     * @return array
     */
    public function delGoods($brandID) {
        $brandRes = array();
        $flag = $this->checkGoodsID($brandID, $brandRes, true);

        if ($flag['code'] > 0) {
            return $flag;
        }

        $mGoods = new Goods();

        $delFlag = $mGoods::destroy($brandID);

        if ($delFlag === false) {
            return array_err(1951296, '删除品牌数据失败');
        }

        return array_err(0, '删除品牌数据成功');
    }

    /**
     * 检查商品分类是否正确
     * @param $cateID
     * @param bool $lock
     * @param $cateGoryRes
     * @return array
     */
    public function checkCateGoryID($cateID, $lock = false, &$cateGoryRes) {
        if (empty($cateID)) {
            return array_err(19869, '分类标识不能为空');
        }

        $mGoods = new Goods();
        $cateGoryRes = $mGoods->getGoodsCateByKV($cateID, $lock);
        return array_err(0, 'success');
    }

    /**
     * 检查商品数据
     * @param $goodsBase [] 商品基础数据
     * @param $goodsAttr 商品属性数据
     * @return array
     */
    public function checkGoodsData($goodsBase) {
        if (empty($goodsBase['goods_name'])) {
            return array_err(92299, '商品名称不能为空');
        }

        if (empty($goodsBase['markte_price'])) {
            return array_err(92299, '市场价格为必填');
        }

        if (empty($goodsBase['shop_price'])) {
            return array_err(92299, '零售价格为必填');
        }

        if (isset($goodsBase['category_id'])) {
            $mGoods = new Goods();
            if (!empty($goodsBase['category_id'])) {
                $cateRes = $mGoods->getGoodsCateByKV($goodsBase['category_id'], false, 'id,show_cate');
                if (empty($cateRes)) {
                    return array_err(197318, '请设置正确的所属分类');
                }
                if ($cateRes[$goodsBase['category_id']] == 0) {
                    return array_err(197317, '所属分类为关闭状态,请先开启');
                }
            } else {
                return array_err(197318, '所属分类不能为空');
            }
        }

        if (isset($goodsBase['brand_id'])) {
            $mBrand = new Brand();
            if (!empty($goodsBase['brand_id'])) {
                $cateRes = $mBrand->getBreadByKV($goodsBase['brand_id'], false, 'id,status');
                if (empty($cateRes)) {
                    return array_err(197318, '请设置正确的品牌');
                }
                if ($cateRes[$goodsBase['brand_id']] == 0) {
                    return array_err(197317, '所属品牌为关闭状态,请先开启');
                }
            }
        }

        if (isset($goodsBase['goods_weight']) && !is_numeric($goodsBase['goods_weight'])) {
            return array_err(197317, '商品重量必须是数字');
        }

        if (!is_numeric($goodsBase['markte_price']) || !is_numeric($goodsBase['shop_price'])) {
            return array_err(197317, '价格必须是数字');
        }

        if ($goodsBase['on_sale'] != 1 && $goodsBase['on_sale'] != 0) {
            return array_err(197317, '是否上下架标识错误');
        }

        return array_err(0, 'success');
    }

    /**
     * @param $goodsPrice
     * @return array
     */
    public function checkGoodsPrice($goodsPrice) {
        if (!empty($goodsPrice)) {
            $eMemberEvent = new MemberEvent();
            $checkLevelFlag = $eMemberEvent->checkLevelID(array_keys($goodsPrice), $levelRes);
            if ($checkLevelFlag['err_code'] > 0) {
                return $checkLevelFlag;
            }

            foreach ($goodsPrice as $k => $v) {
                if (!is_numeric($v)) {
                    return array_err(9777, '等级价格必须为数字');
                }
            }

            return array_err(0, 'success');
        }
    }

    /**
     * @param $goodsAttr
     * @return array
     */
    public function checkGoodsAttr($goodsAttr) {
        if (!empty($goodsAttr)) {
            $mTypeEvent = new TypeEvent();
            $typeRes = [];
            $checkTypeFlag = $mTypeEvent->checkTypeID($goodsAttr['type_id'], $typeRes);
            if ($checkTypeFlag['err_code'] > 0) {
                return $checkTypeFlag;
            }

            $arrAttrID = array_column($goodsAttr, 'attr_id');
            $attrRes = [];
            $checkAttrFlag = $mTypeEvent->checkAttrID($arrAttrID, $attrRes);
            if ($checkAttrFlag['err_code'] > 0) {
                return $checkAttrFlag;
            }
            return array_err(0, 'success');
        }
    }

    /**
     * 添加商品分类数据
     * @param $data
     * @return array
     */
    public function addCateGory($data) {
        $checkFlag = $this->_checkCateGory($data);
        if ($checkFlag['code'] > 0) {
            return $checkFlag;
        }

        $flag = Db::name('category')->insert($data);
        if ($flag === false) {
            return array_err(197315, '添加分类失败');
        }

        return array_err(0, '添加分类数据成功');
    }

    /**
     * 修改商品分类数据
     * @param $data
     * @param $cateGoryID
     * @return array
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function editCateGory($data, $cateGoryID) {
        $checkFlag = $this->_checkCateGory($data, $cateGoryID);
        if ($checkFlag['code'] > 0) {
            return $checkFlag;
        }

        $flag = Db::name('category')->where('id', $cateGoryID)->update($data);
        if ($flag === false) {
            return array_err(197315, '修改分类失败');
        }

        return array_err(0, '修改分类数据成功');
    }

    protected function _checkCateGory(&$data, $cateID = '') {
        if (empty($data['cate_name'])) {
            return array_err(197319, '分类名称不能为空');
        }
        $mGoods = new Goods();

        if (!empty($data['pid'])) {
            $cateRes = $mGoods->getGoodsCateByKV($data['pid']);
            if (empty($cateRes)) {
                return array_err(197318, '请设置正确的上级分类');
            }
        }

        $param['cate_name'] = $data['cate_name'];
        $param['pid'] = $data['pid'];
        if ($cateID) {
            if ($cateID == $data['pid']) {
                return array_err(197314, '不能选择自身分类');
            }

            $param['id'] = array('neq', $cateID);
        }

        $cateGoryRes = $mGoods->cateGoryParam($param);

        if ($cateGoryRes) {
            return array_err(197317, '该分类下存在相同名称,请更换');
        }

        if ($data['show_cate'] != 0 && $data['show_cate'] != 1) {
            return array_err(197316, '是否开启设置错误,请核实');
        }

        return array_err(0, 'success');
    }

}
<?php


namespace app\common\event;

use app\common\model\Brand;
use app\common\util\apiUtil;
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
    public function checkGoodsID($goodsID, &$goodsBaseRes, $lock = false) {
        $mGoods = new Goods();
        if (empty($goodsID)) {
            return array_err(1951298, '商品标识不能为空');
        }
        $goodsBaseRes = $mGoods->getGoodsByKV($goodsID, $lock);

        if (count($goodsID) != count($goodsBaseRes)) {
            return array_err(1951297, '存在非法数据');
        }

        if (empty($goodsBaseRes)) {
            return array_err(1951296, '没有查找到您要的标识');
        }
        return array_err(0, 'success');
    }

    /**
     * 修改品牌数据
     * @param $goodsID
     * @param $data
     * @return array
     */
    public function editGoods($goodsID, $goodsBase, $goodsPrice, $goodsAttr) {
        $this->_editGoods($goodsID, $goodsBase, $goodsPrice, $goodsAttr);
    }


    /**
     * 修改品牌数据
     * @param $goodsID
     * @param $data
     * @return array
     */
    public function _editGoods($goodsID, $goodsBase, $goodsPrice, $goodsAttr) {
        $goodsBaseRes = array();
        $flag = $this->checkGoodsID($goodsID, $goodsBaseRes, true);
        if ($flag['code'] > 0) {
            return $flag;
        }

        $checkGoodsFlag = $this->checkGoodsData($goodsBase);
        if ($checkGoodsFlag['code'] > 0) {
            return $checkGoodsFlag;
        }

        //比对商品数据是否需要修改
        $compareCols = 'goods_name,og_thumb,markte_price,shop_price,on_sale,category_id,brand_id,type_id,goods_des,goods_weight,weight_unit';//设置需要对比的字段
        $this->_compareOrdInfo($goodsBaseRes, $goodsBase, $goodsID, $compareCols);

        //设置商品编码
        $seq = new apiUtil('GOODS_CODE', false);
        $goodsBase['goods_code'] = $seq->next_val();

        $checkGoodsPriceFlag = $this->checkGoodsPrice($goodsPrice);
        if ($checkGoodsPriceFlag['code'] > 0) {
            return $checkGoodsPriceFlag;
        }
        $checkGoodsAttrFlag = $this->checkGoodsAttr($goodsAttr);

        if ($checkGoodsAttrFlag['code'] > 0) {
            return $checkGoodsAttrFlag;
        }
        $data = array_err(0, 'success');

        $data['goods_base'] = $goodsBase;
        $data['goods_price'] = $goodsPrice;
        $data['goods_attr'] = $goodsAttr;

        return $data;
    }


    protected function _compareOrdInfo($dbArray, $updatetArray, $goodsID, $compareCols) {
        /**
         *  * 更新比对
         * @param array $dbArray 数据表数组
         * @param array $updatetArray 待更新数组
         * @param string $key 主键
         * @param array $compareCols 需比对的字段
         * @return mixed 比对数组
         */
        $res = updateCompare($dbArray, $updatetArray, $goodsID, $compareCols);
        echo '<pre>';
        print_r($res);
        echo '</pre>';
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

    /**
     * 商品库存的设置
     * @param $goodsID
     * @param $productData
     * @return array
     */
    public function setProduct($goodsID, $productData) {
        $flag = $this->checkGoodsID($goodsID, $brandRes, false);
        if ($flag['code'] > 0) {
            return $flag;
        }
        $productData = json_decode_html($productData);
        $checkProductFlag = $this->checkProduct($goodsID, $productData);
        if ($checkProductFlag['code'] > 0) {
            return $checkProductFlag;
        }

        $mProduct = Db::name('product');
        $productRes = $mProduct->where('goods_id', $goodsID)->find();
        if ($productRes) {
            $delFlag = $mProduct->where('goods_id', $goodsID)->delete();
            if ($delFlag === false) {
                return array_err(2323, '设置商品库存失败');
            }
        }

        foreach ($productData as $key => $value) {
            $productData[$key]['goods_id'] = $goodsID;
            $productData[$key]['goods_number'] = $value['goods_number'] ?? 0;
        }
        $addFlag = Db::name('product')->insertAll($productData);

        if ($addFlag === false) {
            return array_err(9877, '添加库存失败');
        }
        return array_err(0, '添加库存成功');
    }

    /**
     * 校验商品库存是否正确
     * @param $goodsID
     * @param $productData
     * @return array
     */
    public function checkProduct($goodsID, $productData) {// todo @!!!!未完待续
        if ($productData) {
            $mGoods = new Goods();
            $formatProduct = [];
            foreach ($productData as $k => $v) {
                $formatProduct[$v['goods_attr']][] = $v;
                if (count($formatProduct[$v['goods_attr']]) >= 2) {
                    return array_err(985, '设置了相同的属性库存,请修改');
                }
            }

            $productDataArr = array_unique(explode(',', implode(',', array_column($productData, 'goods_attr'))));

            $goodsAttr = $mGoods->getGoodsAttrByID($productDataArr);

            foreach ($productDataArr as $k => $v) {
                if (empty($goodsAttr[$v])) {
                    return array_err(986, '没有您要的商品属性标识,请核实');
                }
            }

            foreach ($goodsAttr as $key => $value) {
                if ($value['goods_id'] != $goodsID) {
                    return array_err(987, '不是该商品的属性标识,请核实');
                }
            }

            return array_err(0, 'success');
        }
    }

    /**
     * 商品添加
     * @param $goodsBase
     * @param $goodsPrice
     * @param $goodsAttr
     * @param $goodsPhoto
     * @return array
     */
    public function addGoods($goodsBase, $goodsPrice, $goodsAttr, $goodsPhoto) {
        $dataRes = $this->_addGoods($goodsBase, $goodsPrice, $goodsAttr);
        if ($dataRes['code'] > 0) {
            return $dataRes;
        }

        $mGoods = new Goods();

        $data['goods_name'] = $dataRes['goods_base']['goods_name'];
        $data['markte_price'] = $dataRes['goods_base']['markte_price'];
        $data['shop_price'] = $dataRes['goods_base']['shop_price'];
        $data['goods_weight'] = $dataRes['goods_base']['goods_weight'];
        $data['category_id'] = $dataRes['goods_base']['category_id'];
        $data['brand_id'] = $dataRes['goods_base']['brand_id'] ?? 0;
        $data['on_sale'] = $dataRes['goods_base']['on_sale'];
        $data['og_thumb'] = $dataRes['goods_base']['og_thumb'];
        $data['sm_thumb'] = $dataRes['goods_base']['sm_thumb'];
        $data['mid_thumb'] = $dataRes['goods_base']['mid_thumb'];
        $data['big_thumb'] = $dataRes['goods_base']['big_thumb'];
        $data['goods_code'] = $dataRes['goods_base']['goods_code'];
        $data['type_id'] = $dataRes['goods_attr']['type_id'] ?? 0;
        $data['goods_des'] = $dataRes['goods_base']['goods_des'];
        $data['time'] = time();

        $addGoodsFlag = $mGoods->save($data);

        if ($addGoodsFlag === false) {
            return array_err(92499, '增加商品基础数据失败');
        }


        if (!empty($dataRes['goods_price'])) {
            $allPrice = [];
            foreach ($dataRes['goods_price'] as $k => $v) {
                $dataPrice['mprice'] = $v;
                $dataPrice['mlevel_id'] = $k;
                $dataPrice['goods_id'] = $mGoods->id;
                $allPrice[] = $dataPrice;
            }

            $addGoodsPriceFlag = Db::name('member_price')->insertAll($allPrice);

            if ($addGoodsPriceFlag === false) {
                return array_err(92499, '增加商品基础数据失败');
            }
        }

        if (!empty($dataRes['goods_attr'])) {
            foreach ($dataRes['goods_attr']['attr_lists'] as $k => $v) {
                $dataRes['goods_attr']['attr_lists'][$k]['goods_id'] = $mGoods->id;
            }

            $addGoodsAttrFlag = Db::name('goods_attr')->insertAll($dataRes['goods_attr']['attr_lists']);

            if ($addGoodsAttrFlag === false) {
                return array_err(92499, '增加商品基础数据失败');
            }
        }


        if (!empty($goodsPhoto)) {
            $goodsPhotoArr = [];
            foreach ($goodsPhoto as $k => $v) {
                $pathInfo = pathinfo($v);
                $goodsPhotoArr[$k]['goods_id'] = $mGoods->id;
                $goodsPhotoArr[$k]['og_photo'] = $v;
                $goodsPhotoArr[$k]['sm_photo'] = $pathInfo['dirname'] . '/sm_' . $pathInfo['basename'];
                $goodsPhotoArr[$k]['mid_photo'] = $pathInfo['dirname'] . '/mid_' . $pathInfo['basename'];
                $goodsPhotoArr[$k]['big_photo'] = $pathInfo['dirname'] . '/big_' . $pathInfo['basename'];
            }

            $addGoodsPhotoFlag = Db::name('goods_photo')->insertAll($goodsPhotoArr);

            if ($addGoodsPhotoFlag === false) {
                return array_err(92499, '增加商品基础数据失败');
            }
        }

        return array_err(0, '添加商品数据成功');
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
        if ($checkGoodsFlag['code'] > 0) {
            return $checkGoodsFlag;
        }
        if ($goodsBase['og_thumb']) {
            $pathInfo = pathinfo($goodsBase['og_thumb']);
            $goodsBase['sm_thumb'] = $pathInfo['dirname'] . '/sm_' . $pathInfo['basename'];
            $goodsBase['mid_thumb'] = $pathInfo['dirname'] . '/mid_' . $pathInfo['basename'];
            $goodsBase['big_thumb'] = $pathInfo['dirname'] . '/big_' . $pathInfo['basename'];
        }

        //设置商品编码
        $seq = new apiUtil('GOODS_CODE', false);
        $goodsBase['goods_code'] = $seq->next_val();

        $checkGoodsPriceFlag = $this->checkGoodsPrice($goodsPrice);
        if ($checkGoodsPriceFlag['code'] > 0) {
            return $checkGoodsPriceFlag;
        }
        $checkGoodsAttrFlag = $this->checkGoodsAttr($goodsAttr);

        if ($checkGoodsAttrFlag['code'] > 0) {
            return $checkGoodsAttrFlag;
        }
        $data = array_err(0, 'success');

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
            if ($checkLevelFlag['code'] > 0) {
                return $checkLevelFlag;
            }

            foreach ($goodsPrice as $k => $v) {
                if (!is_numeric($v)) {
                    return array_err(9777, '等级价格必须为数字');
                }
            }

        }
        return array_err(0, 'success');
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
            if ($checkTypeFlag['code'] > 0) {
                return $checkTypeFlag;
            }


            $arrAttrID = array_unique(array_column($goodsAttr['attr_lists'], 'attr_id'));
            $attrRes = [];
            $checkAttrFlag = $mTypeEvent->checkAttrID($arrAttrID, $attrRes);
            if ($checkAttrFlag['code'] > 0) {
                return $checkAttrFlag;
            }
        }
        return array_err(0, 'success');
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
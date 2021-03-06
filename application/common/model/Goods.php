<?php

namespace app\common\model;

use think\Db;

/**
 * 商品模型
 */
class Goods extends BaseModel
{
    /**
     * 根据主键查询数据
     * @param $cateGoryID
     * @param bool $lock
     * @param string $field
     * @return array
     */
    public function getGoodsCateByKV($cateGoryID, $lock = false, $field = '*') {
        $where['id'] = ['in', $cateGoryID];
        if ($lock) {
            return Db::name('category')->where($where)->master()->lock($lock)->column($field);
        }
        return Db::name('category')->where($where)->column($field);
    }

    /**
     * 根据主键查询数据
     * @param $param
     * @param string $field
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function cateGoryParam($param, $field = '*') {
        return Db::name('category')->field($field)->where($param)->select();
    }

    /**
     * 根据主键查询数据
     * @param $goodsID
     * @param bool $lock
     * @param string $field
     * @return array
     */
    public function getGoodsByKV($goodsID, $lock = false, $field = '*') {
        $where['id'] = ['in', $goodsID];
        if ($lock) {
            return $this->where($where)->master()->lock($lock)->column($field);
        }
        return $this->where($where)->column($field);
    }

    /**
     * 查询数据的数量
     * @param array $param
     * @return int|string
     */
    public function getGoodsByParamCnt($param = array()) {
        $where = $this->_makeParam($param, 'g');
        return $this->alias($where['alias'])->where($where['where'])->join($where['join'])->count();
    }

    /**
     * 获取数据
     * @param array $param
     * @param string $field
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getGoodsByParam($param = array()) {
        $where = $this->_makeParam($param, 'g');
        return $this->where($where['where'])->field($where['field'])
            ->alias($where['alias'])
            ->join($where['join'])
            ->limit(($param['curr_page'] - 1) * $param['page_count'], $param['page_count'])
            ->order('time desc')
            ->group('id')
            ->select();
    }

    /**
     * 根据商品名称获取数据
     * @param $param
     * @param string $field
     * @return array
     */
    public function getGoodsByName($param, $field = '*') {
        return $this->where($param)->column($field);
    }


    public function _makeParam($param, $prefix = '') {
        $code = ['field' => 'g.*', 'alias' => $prefix];
        $where = array();
        if (!empty($param['id'])) {
            $where['id'] = ['in', $param['id']];
        }

        if (!empty($param['goods_name'])) {
            $where['goods_name'] = ['LIKE', "%" . $param['goods_name'] . "%"];
        }

        $code['where'] = $where;

        if ($param['if_brand']) {
            $code['field'] .= ',ifnull(b.brand_name,"") brand_name';
            $code['join'][] = ['tp_brand b', "{$prefix}.brand_id = b.id", 'left'];
        }

        if ($param['if_category']) {
            $code['field'] .= ',ifnull(c.cate_name,"") cate_name';
            $code['join'][] = ['tp_category c', "{$prefix}.category_id = c.id", 'left'];
        }

        if ($param['if_type']) {
            $code['field'] .= ',ifnull(t.type_name,"") type_name';
            $code['join'][] = ['tp_type t', "{$prefix}.type_id = t.id", 'left'];
        }

        if ($param['if_product']) {
            $code['field'] .= ',ifnull(goods_number,0) goods_number';
            $code['join'][] = ['tp_product tp', "{$prefix}.id = tp.goods_id", 'left'];
        }

        return $code;
    }


    /**
     * 根据商品标识获取属性值
     * @param $goodsAttrID
     * @param string $field
     * @return array
     */
    public function getGoodsAttrByID($goodsAttrID, $field = '*') {
        $param['id'] = ['in', $goodsAttrID];
        return Db::name('goods_attr')->where($param)->column($field);
    }

    /**
     * 根据商品标识获取属性值
     * @param $goodsID
     * @param string $field
     * @return array
     */
    public function getGoodsAttrByGoodsID($goodsID, $field = '*') {
        $param['goods_id'] = $goodsID;
        return Db::name('goods_attr')->where($param)->column($field);
    }

    /**
     * 根据商品标识获取相册
     * @param $goodsID
     * @param string $field
     * @return array
     */
    public function getGoodsPhotoByGoodsID($goodsID, $field = '*') {
        $param['goods_id'] = $goodsID;
        return Db::name('goods_photo')->where($param)->column($field);
    }

    /**
     * 根据商品标识获取商品的库存
     * @param $goodsID
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getGoodsProductByGoodsID($goodsID) {
        $param['goods_id'] = $goodsID;
        return Db::name('product')->where($param)->select();
    }

    /**
     * 修改商品价格
     * @param $arrPriceUpdate
     * @return array
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function alterGoodsPrice($arrPriceUpdate) {

        if ($arrPriceUpdate['M']) {
            unset($arrPriceUpdate['M']['key']);

            foreach ($arrPriceUpdate['M'] as $k => $v) {
                if (false == Db::name('member_price')->where('id', '=', $k)->update($v)) {
                    return array_err(9867, '修改会员价格错误,请联系管理员');
                }
            }
        }

        if ($arrPriceUpdate['A']) {
            unset($arrPriceUpdate['A']['key']);
            if (false == Db::name('member_price')->insertAll($arrPriceUpdate['A'])) {
                return array_err(9868, '新增会员价格错误,请联系管理员');
            }
        }

        if ($arrPriceUpdate['D']) {
            unset($arrPriceUpdate['D']['key']);
            if (false == Db::name('member_price')->delete($arrPriceUpdate['D'])) {
                return array_err(9869, '删除会员价格错误,请联系管理员');
            }
        }

        return array_err(0, 'success');

    }


    public function alterGoods($goodsBase,$goodsID) {
        if($this->where('id', '=', $goodsID)->update($goodsBase) === false){
            return array_err(7765,'修改基础商品失败');
        }

        return array_err(0, 'success');

    }

    /**
     * 修改商品属性
     * @param $arrAttrUpdate
     * @return array
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function alterGoodsAttr($arrAttrUpdate) {
        if ($arrAttrUpdate['M']) {
            unset($arrAttrUpdate['M']['key']);

            foreach ($arrAttrUpdate['M'] as $k => $v) {
                if (false == Db::name('goods_attr')->where('id', '=', $k)->update($v)) {
                    return array_err(9767, '修改属性错误,请联系管理员');
                }
            }
        }

        if ($arrAttrUpdate['A']) {
            unset($arrAttrUpdate['A']['key']);
            if (false == Db::name('goods_attr')->insertAll($arrAttrUpdate['A'])) {
                return array_err(9768, '新增属性错误,请联系管理员');
            }
        }

        if ($arrAttrUpdate['D']) {
            unset($arrAttrUpdate['D']['key']);
            if (false == Db::name('goods_attr')->delete($arrAttrUpdate['D'])) {
                return array_err(9769, '删除属性错误,请联系管理员');
            }
        }

        return array_err(0, 'success');

    }
}
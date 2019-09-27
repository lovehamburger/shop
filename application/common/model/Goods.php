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
     * @param string $field
     * @return Goods
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
     * @param $cateGoryID
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
        $where = $this->_makeParam($param);
        return $this->where($where)->count();
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
    public function getGoodsByParam($param = array(), $field = '*') {
        $where = $this->_makeParam($param);
        return $this->where($where)->field($field)
            ->join()
            ->limit(($param['curr_page'] - 1) * $param['page_count'], $param['page_count'])
            ->order('time')
            ->select();
    }


    public function _makeParam($param, $prefix = '', $field = '*') {
        $code = ['field' => $field, 'join' => ''];
        $where = array();
        if (!empty($param['id'])) {
            $where['id'] = ['in', $param['id']];
        }

        if (!empty($param['brand_name'])) {
            $where['goods_name'] = ['LIKE', "%" . $param['goods_name'] . "%"];
        }

        $code['where'] = $where;

        if ($param['if_brand']) {
            $code['field'] = '';
            $code['join'] = 'left join on';
        }

        return $code;
    }
}
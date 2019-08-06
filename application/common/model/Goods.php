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
     * @param $brandID
     * @param string $field
     * @return Goods
     */
    public function cateGoryParam($param, $field = '*') {
        return Db::name('category')->field($field)->where($param)->select();
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
    public function getGoodsByParam($param = array(), $field = 'id,brand_name,brand_url,brand_img,brand_description,sort,status') {
        $where = $this->_makeParam($param);
        return $this->where($where)->field($field)
                                ->limit(($param['curr_page'] - 1) * $param['page_count'], $param['page_count'])
                                ->order('sort')
                                ->select();
    }


    public function _makeParam($param) {
        $where = array();
        if (!empty($param['id'])) {
            $where['id'] = ['in', $param['id']];
        }

        if (!empty($param['brand_name'])) {
            $where['brand_name'] = ['LIKE', "%" . $param['brand_name'] . "%"];
        }

        return $where;
    }
}
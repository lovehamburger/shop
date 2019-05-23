<?php

namespace app\common\model;
/**
 * 商品品牌模型
 */
class Brand extends BaseModel
{
    /**
     * 根据主键查询品牌数据
     * @param $brandID
     * @param string $field
     * @return Brand
     */
    public function getBreadByKV($brandID, $lock = false, $field = 'id,brand_name,brand_url,brand_img,brand_description,sort,status') {
        $where['id'] = ['in', $brandID];
        if ($lock) {
            return $this->where($where)->master()->lock($lock)->column($field);
        }
        return $this->where($where)->column($field);
    }

    /**
     * 查询品牌数据的数量
     * @param array $param
     * @return int|string
     */
    public function getBrandByParamCnt($param = array()) {
        $where = $this->_makeParam($param);
        return $this->where($where)->count();
    }

    /**
     * 获取品牌数据
     * @param array $param
     * @param string $field
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getBrandByParam($param = array(), $field = 'id,brand_name,brand_url,brand_img,brand_description,sort,status') {
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
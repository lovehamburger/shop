<?php

namespace app\common\model;
/**
 * 商品属性模型
 */
class Attr extends BaseModel
{

    public function getAttrTypeNameAttr($value)
    {
        $attrtype = [1=>'单选属性',2=>'唯一属性'];
        return $attrtype[$value];
    }

    /**
     * 根据主键查询类型属性数据
     * @param $attrID
     * @param string $field
     * @return Attr
     */
    public function getAttrByKV($attrID, $lock = false, $field = 'id,attr_name') {
        $where['id'] = ['in', $attrID];
        if ($lock) {
            return $this->where($where)->master()->lock($lock)->column($field);
        }
        return $this->where($where)->column($field);
    }


    /**
     * 查询类型属性数据的数量
     * @param array $param
     * @return int|string
     */
    public function getAttrByParamCnt($param = array()) {
        $where = $this->_makeParam($param);
        return $this->where($where)->count();
    }

    /**
     * 获取类型属性数据
     * @param array $param
     * @param string $field
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getAttrByParam($param = array(), $field = 'id,attr_name,attr_values,type_id') {
        $where = $this->_makeParam($param);
        return $this->where($where)->field($field)
                                ->limit(($param['curr_page'] - 1) * $param['page_count'], $param['page_count'])
                                ->select();
    }


    public function _makeParam($param) {
        $where = array();
        if (!empty($param['id'])) {
            $where['id'] = ['in', $param['id']];
        }

        if (!empty($param['type_id'])) {
            $where['type_id'] = ['in', $param['type_id']];
        }

        if (!empty($param['attr_name'])) {
            $where['attr_name'] = $param['attr_name'];
        }

        return $where;
    }
}
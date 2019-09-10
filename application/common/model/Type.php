<?php

namespace app\common\model;
/**
 * 商品类型模型
 */
class Type extends BaseModel
{
    /**
     * 根据主键查询类型数据
     * @param $typeID
     * @param string $field
     * @return Type
     */
    public function getTypeByKV($typeID, $lock = false, $field = 'id,type_name') {
        $where['id'] = ['in', $typeID];
        if ($lock) {
            return $this->where($where)->master()->lock($lock)->column($field);
        }
        return $this->where($where)->column($field);
    }

    /**
     * 查询类型数据的数量
     * @param array $param
     * @return int|string
     */
    public function getTypeByParamCnt($param = array()) {
        $where = $this->_makeParam($param);
        return $this->where($where)->count();
    }

    /**
     * 获取类型数据
     * @param array $param
     * @param string $field
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getTypeByParam($param = array(), $field = 'id,type_name') {
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

        if (!empty($param['type_name'])) {
            $where['type_name'] = ['LIKE', "%" . $param['type_name'] . "%"];
        }

        return $where;
    }
}
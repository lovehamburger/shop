<?php

namespace app\common\model;
/**
 * 推荐位模型
 */
class Recpos extends BaseModel
{

    public function getrectypeAttr($value)
    {
        $type = [1=>'商品',2=>'分类'];
        return $type[$value];
    }
    /**
     * 根据主键查询推荐位数据
     * @param $brandID
     * @param string $field
     * @return Recpos
     */
    public function getRecposByKV($brandID, $lock = false, $field = 'id,rec_name,rec_type,status') {
        $where['id'] = ['in', $brandID];
        if ($lock) {
            return $this->where($where)->master()->lock($lock)->column($field);
        }
        return $this->where($where)->column($field);
    }

    /**
     * 查询推荐位数据的数量
     * @param array $param
     * @return int|string
     */
    public function getRecposByParamCnt($param = array()) {
        $where = $this->_makeParam($param);
        return $this->where($where)->count();
    }

    /**
     * 获取推荐位数据
     * @param array $param
     * @param string $field
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getRecposByParam($param = array(), $field = '*,rec_type rec_type_key') {
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

        if (!empty($param['rec_name'])) {
            $where['rec_name'] = ['LIKE', "%" . $param['rec_name'] . "%"];
        }

        return $where;
    }
}
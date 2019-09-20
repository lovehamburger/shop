<?php

namespace app\common\model;
/**
 * 等级模型
 */
class MemberLevel extends BaseModel
{
    /**
     * 根据主键查询等级数据
     * @param $memberLevelID
     * @param bool $lock
     * @param string $field
     * @return array
     */
    public function getMemberLevelByKV($memberLevelID, $lock = false, $field = 'id,level_name') {
        $where['id'] = ['in', $memberLevelID];
        if ($lock) {
            return $this->where($where)->master()->lock($lock)->column($field);
        }
        return $this->where($where)->column($field);
    }

    /**
     * 查询等级数据的数量
     * @param array $param
     * @return int|string
     */
    public function getMemberLevelByParamCnt($param = array()) {
        $where = $this->_makeParam($param);
        return $this->where($where)->count();
    }

    /**
     * 获取等级数据
     * @param array $param
     * @param string $field
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getMemberLevelByParam($param = array(), $field = 'id,level_name,bom_point,top_point,rate') {
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

        if (!empty($param['not_id'])) {
            $where['id'] = ['in', $param['id']];
        }

        if (!empty($param['level_name'])) {
            $where['level_name'] = $param['level_name'];
        }

        return $where;
    }
}
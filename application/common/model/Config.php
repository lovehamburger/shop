<?php

namespace app\common\model;
/**
 * 商品配置模型
 */
class Config extends BaseModel
{

    public function getConfTypeNameAttr($value) {
        $conftype = [1 => '网店配置', 2 => '商品配置'];
        return $conftype[$value];
    }


    /**
     * 根据主键查询配置数据
     * @param $configID
     * @param string $field
     * @return Config
     */
    public function getConfigByKV($configID, $lock = false, $field = 'id,ename,cname,form_type,conf_type,values,value,sort,status') {
        $where['id'] = ['in', $configID];
        if ($lock) {
            return $this->where($where)->master()->lock($lock)->column($field);
        }
        return $this->where($where)->column($field);
    }

    /**
     * 查询配置数据的数量
     * @param array $param
     * @return int|string
     */
    public function getConfigByParamCnt($param = array()) {
        $where = $this->_makeParam($param);
        return $this->where($where)->count();
    }

    public function getConfigByEn($eName, $field = 'id,values,value') {
        $where['ename'] = ['in', $eName];
        return $this->where($where)->field($field)->order('sort')->select();
    }

    /**
     * 获取配置数据
     * @param array $param
     * @param string $field
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getConfigByParam($param = array(), $field = 'id,ename,cname,form_type,conf_type,values,value,sort,status') {
        $where = $this->_makeParam($param);
        return $this->where($where)->field($field)
            ->limit(($param['curr_page'] - 1) * $param['page_count'], $param['page_count'])
            ->order('sort,id desc')
            ->select();
    }


    public function _makeParam($param) {
        $where = array();
        if (!empty($param['id'])) {
            $where['id'] = ['in', $param['id']];
        }

        if (!empty($param['cname'])) {
            $where['cname'] = ['LIKE', "%" . $param['cname'] . "%"];
        }

        if (!empty($param['ename'])) {
            $where['ename'] = ['=', $param['ename']];
        }

        if (!empty($param['conf_type'])) {
            $where['conf_type'] = ['=', $param['conf_type']];
        }

        return $where;
    }
}
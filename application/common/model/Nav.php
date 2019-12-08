<?php

namespace app\common\model;
/**
 * 导航链接模型
 */
class Nav extends BaseModel
{

    protected $resultSetType = 'collection';

    public function getOpenAttr($value) {
        $open = [1 => '新标签', 2 => '当前页'];
        return $open[$value];
    }

    /**
     * 根据主键查询友情链接数据
     * @param $brandID
     * @param string $field
     * @return Nav
     */
    public function getNavByKV($brandID, $lock = false, $field = 'id,nav_name,nav_url,open,pos,sort,status') {
        $where['id'] = ['in', $brandID];
        if ($lock) {
            return $this->where($where)->master()->lock($lock)->column($field);
        }
        return $this->where($where)->column($field);
    }

    /**
     * 查询友情链接数据的数量
     * @param array $param
     * @return int|string
     */
    public function getNavByParamCnt($param = array()) {
        $where = $this->_makeParam($param);
        return $this->where($where)->count();
    }

    /**
     * 获取友情链接数据
     * @param array $param
     * @param string $field
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getNavByParam($param = array(), $field = '*,open open_key') {
        $where = $this->_makeParam($param);
        return $this->where($where)->field($field)
            ->limit(($param['curr_page'] - 1) * $param['page_count'], $param['page_count'])
            ->select();
    }

    /**
     * 获取友情链接数据
     * @param array $param
     * @param string $field
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getNavGroupPos($field = '*,open open_key') {
        $navRes = $this->field($field)
            ->where('status', '=', '1')
            ->select()->toArray();
        $navFormatRes = [];
        if ($navRes) {
            foreach ($navRes as $k =>$v){
                $navFormatRes[$v['pos']][] = $v;
            }
        }
        return $navFormatRes;
    }


    public function _makeParam($param) {
        $where = array();
        if (!empty($param['id'])) {
            $where['id'] = ['in', $param['id']];
        }

        if (!empty($param['nav_name'])) {
            $where['nav_name'] = ['LIKE', "%" . $param['nav_name'] . "%"];
        }

        return $where;
    }
}
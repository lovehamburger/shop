<?php

namespace app\common\model;
/**
 * 商品友情链接模型
 */
class Link extends BaseModel
{

    public function getTypeAttr($value)
    {
        $type = [1=>'文字',2=>'图片'];
        return $type[$value];
    }
    /**
     * 根据主键查询友情链接数据
     * @param $brandID
     * @param string $field
     * @return Link
     */
    public function getLinkByKV($brandID, $lock = false, $field = 'id,title,link_url,logo,description,status') {
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
    public function getLinkByParamCnt($param = array()) {
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
    public function getLinkByParam($param = array(), $field = 'id,title,link_url,logo,description,type,status,type type_key') {
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

        if (!empty($param['title'])) {
            $where['title'] = ['LIKE', "%" . $param['title'] . "%"];
        }

        return $where;
    }
}
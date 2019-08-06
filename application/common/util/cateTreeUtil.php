<?php

namespace app\common\util;


class cateTreeUtil extends BaseUtil
{

    /**
     *树形递归二维数组
     * @param $data
     * @param int $parent_id
     * @param int $level
     * @return array
     */
    public function setSort($data, $parent_id = 0, $level = 0) {
        static $res = array();
        foreach ($data as $k => $v) {
            if ($v['pid'] == $parent_id) {
                // 把level值放到这个分类里，这样就可以知道这个分类是第几级的
                $v['level'] = $level;
                $v['html'] = str_repeat('|-----', ($level * 1));
                $v['level_name'] = $v['html'] . $v['cate_name'];
                $res[] = $v;
                // 再找这个分类的子分类
                $this->setSort($data, $v['id'], $level + 1);
            }
        }
        return $res;
    }

    /**
     * 树形递归存在child
     * @param $data
     * @param int $pId
     * @param int $step
     * @return array
     */
    public function sortChild($data, $pId = 0, $step = 0) {
        $tree = array();
        foreach ($data as $k => $v) {
            if ($v['pid'] == $pId) {
                //父亲找到儿子
                $v['level'] = $step;
                $v['title'] = $v['cate_name'];
                $v['children'] = $this->sortChild($data, $v['id'], $step + 1);
                $tree[] = $v;
            }
        }
        return $tree;
    }

    /**
     *树形递归获取父亲节点
     * @param $data
     * @param int $parent_id
     * @return array
     */
    public function getParent($data, $parent_id = 0) {
        static $res = array();
        foreach ($data as $k => $v) {
            if ($v['id'] == $parent_id) {
                // 把level值放到这个分类里，这样就可以知道这个分类是第几级的
                $res[] = $v;
                // 再找这个分类的子分类
                $this->getParent($data, $v['pid']);
            }
        }
        return $res;
    }

}
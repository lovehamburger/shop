<?php

namespace app\common\util;


class cateTreeUtil extends BaseUtil
{

    //测试
    public function _reSort($data, $parent_id=0, $level=0)
    {
        static $ret = array();
        foreach ($data as $k => $v) {
            if ($v['pid'] == $parent_id) {
                // 把level值放到这个分类里，这样就可以知道这个分类是第几级的
                $v['level'] = $level;
//                $v['html'] = str_repeat('&nbsp;&nbsp;|----------', ($level * 1));
                $ret[] = $v;
                // 再找这个分类的子分类
                $this->_reSort($data, $v['id'], $level + 1);
            }
        }
        return $ret;
    }


    public function sortTwo($data, $pId = 0, $step = 0){
        $tree = array();
        foreach($data as $k => $v){
            if($v['pid'] == $pId){
                //父亲找到儿子
                $v['level'] = $step;
                $v['child'] = $this->sortTwo($data, $v['id'], $step+1);
                $tree[] = $v;
            }
        }
        return $tree;
    }

}
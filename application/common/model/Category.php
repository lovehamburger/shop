<?php

namespace app\common\model;

use think\Db;

/**
 * 分类模型
 */
class Category extends BaseModel
{

    // 设置返回数据集的对象名
    protected $resultSetType = 'collection';

    public function getCategoryLevelTwo($value_type = 2, $recpos_id = 5) {
        $str = '';
        if($value_type && $recpos_id){
            $str .= "and  value_type = {$value_type} and recpos_id = {$recpos_id}";
        }
        $res = $this->alias('Category')
            ->field('Category.*,ri.recpos_id')
            ->join('Category c', 'Category.pid = c.id', 'left')
            ->join('rec_item ri', "Category.id = ri.value_id {$str}")
            ->group('Category.id')
            ->select()->toArray();
        $oneCate = [];
        foreach ($res as $k => $v) {
            if ($v['pid'] == 0) {
                $oneCate[$v['id']] = $v;
                foreach ($res as $k1 => $v1) {
                    if ($v1['pid'] == $v['id']) {
                        $oneCate[$v['id']]['child'][] = $v1;
                    }
                }
            }
        }
        return $oneCate;
    }
}
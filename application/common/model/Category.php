<?php

namespace app\common\model;

use app\common\util\cateTreeUtil;
use think\Db;

/**
 * 分类模型
 */
class Category extends BaseModel
{

    // 设置返回数据集的对象名
    protected $resultSetType = 'collection';


    /**
     * 获取推荐分类
     * @param int $value_type
     * @param int $recpos_id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getRecommendCategory($value_type = 2, $recpos_id = 5) {
        $where['category.show_cate'] = 1;
        if ($value_type && $recpos_id) {
            $where['value_type'] = $value_type;
            $where['recpos_id'] = $recpos_id;
        }
        $res = $this->alias('category')
            ->field('category.*,ri.recpos_id')
            ->join('Category c', 'category.pid = c.id', 'left')
            ->join('rec_item ri', "category.id = ri.value_id")
            ->where($where)
            ->group('Category.id')
            ->select()->toArray();

        $commendCate = [];
        foreach ($res as $k => $v) {
            if ($v['pid'] == 0) {
                $commendCate[$v['id']] = $v;
                foreach ($res as $k1 => $v1) {
                    if ($v1['pid'] == $v['id']) {
                        $commendCate[$v['id']]['child'][$v1['id']] = $v1;
                    }
                }
            }
        }
        return $commendCate;
    }

    /**
     * 获取推荐分类
     * @param int $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getCategotyByID($id) {
        $categoryData = Db::name('category')->order('sort desc')->where('show_cate','=','1')->select();
        $data = [];
        $childCate = [];
        foreach ($categoryData as $k =>$v){
            if($v['pid'] == $id){
                $childCate[] = $v;
            }
        }
        $data['cate'] = $childCate;
        $mCateTreeUtil = new cateTreeUtil();
        $childRes = $mCateTreeUtil->setSort($categoryData,$id);
        $childCateID = array_column($childRes,'id');
        $childCateID[] = $id;
        $data['brand'] = Db::name('brand')->where('category_id','in',$childCateID)->where('status','=','1')->select();
        return $data;
    }

    public function getCommendPosGoods($value_type = 2, $recpos_id = 5){
        $recommendCate = $this->getRecommendCategory($value_type,$recpos_id);
        if($recommendCate){
            $categoryData = Db::name('category')->order('sort desc')->where('show_cate','=','1')->select();
            $recommendCateChild = [];
            $uCateTreeUtil = new  cateTreeUtil();
            foreach ($recommendCate as $k =>$v){
                $recommendCateChild[$v['id']] = $uCateTreeUtil->setSort($categoryData,$v['id'],0,true);
                $recommendCateChild[$k][] = ['id'=>$v['id'],'cate_name'=>$v['cate_name'],'pid'=>$v['pid']];

            }
            echo'<pre>';
            print_r($recommendCateChild);
            echo'</pre>';
        }
    }

}
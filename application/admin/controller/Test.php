<?php
/**
 * 商品品牌
 */

namespace app\admin\controller;

use think\Db;
use app\common\model\Brand as BrandModel;
use app\common\event\BrandEvent;
use app\common\util\FilesUtil;
use app\common\util\cateTreeUtil;

class Test extends Base
{
    /**
     * 分类显示
     * @return \think\response\View
     */
    public function cate() {
        $cateRes = Db::table('tp_cate')->order('sort', 'asc')->where(array('id'=>array('in','23,24')))->select();
        $cateSortRes = (new cateTreeUtil())->sortTwo($cateRes);

        echo'<pre>';
        print_r($cateSortRes);
        echo'</pre>';
        $this->assign('cateSortRes', $cateSortRes);
        return view();
    }

    /**
     * 分类显示
     * @return \think\response\View
     */
    public function cateTree() {
        $this->_ajaxInput();
        $dbObj = $this->getDbObj();

        $cateSortRes = err(0, 'success');
        if (!cache('cateTree')) {
            $cateRes = $dbObj::table('tp_cate')->order('sort', 'asc')->select();
            $cacheCateTree = (new Catetree())->classTree($cateRes);
            cache('cateTree', $cacheCateTree);
        }

        $cateSortRes['data'] = cache('cateTree');


        return $cateSortRes;
    }
}

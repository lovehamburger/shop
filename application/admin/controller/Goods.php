<?php
/**
 * 商品
 */

namespace app\admin\controller;

use app\common\util\cateTreeUtil;
use think\Db;
use app\common\model\Goods as GoodsModel;
use app\common\event\GoodsEvent;
use app\common\util\FilesUtil;

class Goods extends Base
{
    /**
     * 列表页面
     * @return mixed
     */
    public function index() {
        return $this->fetch();
    }


    /**
     * 获取数据
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getData() {
        //todo权限
        $this->_inputAjax();
        $mGoods = new GoodsModel();

        $param = json_decode_html(input('param'));
        $param['curr_page'] = input('page/d');
        $param['page_count'] = input('limit/d');
        //条数的限制
        //dealPage();
        $data = array_err(0, 'success');
        $count = $mGoods->getGoodsByParamCnt($param);
        $data['count'] = $count;
        $brandRes = array();
        if ($count) {
            $brandRes = $mGoods->getGoodsByParam($param);
        }

        $data['data'] = $brandRes;
        return $data;
    }

    /**
     * 设置商品页面
     */
    public function setGoods(){
        return $this->fetch();
    }

    /**
     * 修改状态
     * @return array
     */
    public function setState() {
        $this->_inputAjax();
        $state = input('state/d');
        $brandID = input('id/d');
        $mGoods = new GoodsEvent();

        Db::startTrans();
        $flag = $mGoods->setState($brandID, $state);

        if ($flag['code'] > 0) {
            Db::rollback();
        } else {
            Db::commit();
        }
        return $flag;
    }


    /**
     * 修改数据
     * @return array
     */
    public function editGoods() {
        $this->_inputAjax();
        $data = json_decode_html(input('data'));
        $brandID = input('id/d');
        Db::startTrans();
        $mGoods = new GoodsEvent();

        $flag = $mGoods->editGoods($brandID, $data);
        if ($flag['code'] > 0) {
            Db::rollback();
        } else {
            Db::commit();
        }
        return $flag;
    }

    public function addGoods() {
        $this->_inputAjax();
        $data = json_decode_html(input('data'));

        $mGoods = new GoodsEvent();

        $flag = $mGoods->addGoods($data);

        return $flag;
    }

    /**
     * 删除
     * @return array
     */
    public function delGoods() {
        $this->_inputAjax();
        $brandID = json_decode_html(input('id'));
        Db::startTrans();
        $mGoods = new GoodsEvent();

        $flag = $mGoods->delGoods($brandID);
        if ($flag['code'] > 0) {
            Db::rollback();
        } else {
            Db::commit();
        }
        return $flag;
    }

    /**
     * 图片文件上传
     * @return array
     */
    public function upload() {
        $this->_inputAjax();
        $files = new FilesUtil();
        $files->size = config('uploads.goods_brand')['size'];
        $files->saveDir = config('uploads.goods_brand')['save_dir'];
        $files->ext = config('uploads.goods_brand')['ext'];
        $files->thumb = config('uploads.goods_brand')['thumb'];
        return $flag = $files->uploads('image');
    }

    //====================================================================================================

    /**
     * 获取商品分类
     */
    public function getCateGory() {
        //权限
        //获取所有的商品分类列表
        $categorydata = Db::name('category')->select();
        $mCateTree = new cateTreeUtil();
        $categoryRes = $mCateTree->setSort($categorydata);
        $this->assign('categoryRes', $categoryRes);
        return $this->fetch('goods/category');
    }

    /**
     * 获取商品分类
     */
    public function cateGory() {
        //权限
        //获取所有的商品分类列表
        $categoryData = Db::name('category')->order('sort desc')->select();
        $mCateTree = new cateTreeUtil();
        $categoryRes = array_err(0, 'success');
        $categoryRes['data'] = $mCateTree->setSort($categoryData);
        return $categoryRes;
    }

    public function cateGoryChild(){
        //权限
        //获取所有的商品分类列表
        $categoryData = Db::name('category')->order('sort desc')->select();
        $mCateTree = new cateTreeUtil();
        $categoryRes = array_err(0, 'success');
        $categoryRes['data'] = $mCateTree->sortChild($categoryData);

        return $categoryRes;
    }

    /**
     * 增加商品分类
     */
    public function addCateGory() {
        //todo权限
        $this->_inputAjax();
        $cateGoryData = json_decode_html(input('data'));

        $eGoods = new GoodsEvent();
        $flag = $eGoods->addCateGory($cateGoryData);
        return $flag;
    }

    /**
     * 删除商品分类
     */
    public function delCateGory() {
        //todo权限
        $this->_inputAjax();
        $categoryID = json_decode_html(input('id'));
        Db::startTrans();
        $categoryData = Db::name('category')->order('sort desc')->select();
        $mCateTree = new cateTreeUtil();
        $categoryRes = $mCateTree->setSort($categoryData, $categoryID[0]);
        $cateArrID = array();
        if (!empty($categoryRes)) {
            $cateArrID = array_column($categoryRes, 'id');
        }
        $flag = Db::name('category')->delete(array_merge($categoryID, $cateArrID));
        if ($flag === false) {
            Db::rollback();
            return array_err(987, '删除失败');
        } else {
            Db::commit();
            return array_err(0, '删除成功');
        }
    }

    /**
     * 修改状态
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function setCategoryState() {
        $this->_inputAjax();
        $state = input('state/d');
        $categoryID = input('id/d');
        //获取所有的商品分类列表
        $categoryData = Db::name('category')->order('sort desc')->select();
        $mCateTree = new cateTreeUtil();

        if($state == 1){
            $categoryRes = $mCateTree->getParent($categoryData, $categoryID);
        }else{
            $categoryRes = $mCateTree->setSort($categoryData, $categoryID);
            $categoryRes = array_merge(['0'=>['id'=>$categoryID]], $categoryRes);
        }

        $cateArrID = array();
        if (!empty($categoryRes)) {
            $cateArrID = array_column($categoryRes, 'id');
        }
        Db::startTrans();

        foreach ($cateArrID as $k => $v) {
            $flag = Db::name('category')->where('id', $v)->setField('show_cate', $state);
            if ($flag === false) {
                Db::rollback();
                return array_err(987, '修改失败');
            }
        }

        Db::commit();
        return array_err(0, '修改成功');

    }

    /**
     * 修改商品分类
     */
    public function editCateGory() {
        $this->_inputAjax();
        $cateGoryData = json_decode_html(input('data'));
        $cateGoryID = input('id');

        $eGoods = new GoodsEvent();
        $cateGoryRes = array();
        Db::startTrans();
        $checkCateFlag = $eGoods->checkCateGoryID($cateGoryID,true,$cateGoryRes);
        if($checkCateFlag['code'] > 0){
            Db::rollback();
            return $checkCateFlag;
        }

        $flag = $eGoods->editCateGory($cateGoryData,$cateGoryID);
        if($flag['code'] > 0){
            Db::rollback();

        }
        Db::commit();
        return $flag;
    }


    /**
     * 设置排序
     * @return array
     * @throws \Exception
     */
    public function setCateSort() {
        $this->_inputAjax();
        $sort = json_decode_html(input('sort'));
        Db::startTrans();
        $mGoods = new GoodsEvent();

        $flag = $mGoods->setSort($sort);
        if ($flag['code'] > 0) {
            Db::rollback();
        } else {
            Db::commit();
        }
        return $flag;
    }


    /**
     * 图片文件上传
     * @return array
     */
    public function cateUpload() {
        $this->_inputAjax();
        $files = new FilesUtil();
        $files->size = config('uploads.category')['size'];
        $files->saveDir = config('uploads.category')['save_dir'];
        $files->ext = config('uploads.category')['ext'];
        $files->thumb = config('uploads.category')['thumb'];
        return $flag = $files->uploads('image');
    }

}

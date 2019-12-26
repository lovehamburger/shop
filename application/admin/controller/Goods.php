<?php
/**
 * 商品
 */

namespace app\admin\controller;

use app\common\model\MemberLevel;
use app\common\util\cateTreeUtil;
use think\Db;
use app\common\model\Goods as GoodsModel;
use app\common\model\Recpos;
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
     * 获取商品数据
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

        $param['if_brand'] = true;
        $param['if_category'] = true;
        $param['if_type'] = true;
        $param['if_product'] = true;
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
     * 获取数据
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getProductData() {
        //todo权限
        $this->_inputAjax();
        $mGoods = new GoodsModel();

        $goodsID = input('goods_id/d');
        //条数的限制
        //dealPage();
        if (empty($goodsID)) {
            return array_err(9876, '请设置合法的商品标识');
        }
        $where['goods_id'] = $goodsID;
        $where['attr_type'] = 1;
        $goodsAttr = db('goods_attr')->field('ga.*,attr.attr_name')
            ->alias('ga')
            ->join('attr', 'ga.attr_id = attr.id', 'left')
            ->where($where)
            ->select();
        $goodsAttrFormat = [];
        foreach ($goodsAttr as $k => $v) {
            $goodsAttrFormat[$v['attr_name']][] = $v;
        }
        $goodsProduct = $mGoods->getGoodsProductByGoodsID($goodsID);
        $data = array_err(0, 'success');
        $data['data'] = $goodsAttrFormat;
        $data['product'] = $goodsProduct;
        return $data;
    }

    public function setProduct() {
        //todo权限
        // $this->_inputAjax();
        $goodsID = input('goods_id/d');
        $productData = input('data');
        $mGoods = new GoodsEvent();
        return $mGoods->setProduct($goodsID, $productData);

    }


    /**
     * 设置商品页面
     */
    public function setGoods() {
        //todo 权限
        $goodsID = input('id/d');
        $mRecpos = new Recpos();
        if ($goodsID && $this->_inputAjax()['code'] == 0) {
            $mGoods = new GoodsModel();
            $goodsRes = array_err(0, 'success');
            $goodsRes['goods'] = $mGoods->getGoodsByKV($goodsID)[$goodsID];

            if (empty($goodsRes)) {
                return array_err('8989', '没有您需要修改的商品');
            }
            $goodsRes['attr'] = [];
            if ($goodsRes['goods']['type_id']) {
                foreach ($mGoods->getGoodsAttrByGoodsID($goodsID) as $k => $v) {
                    $goodsRes['attr'][$v['attr_id']][] = $v;
                }
            }

            $mMemberLevel = new MemberLevel();
            $goodsRes['member_price'] = $mMemberLevel->getMemberPriceByGoodsID($goodsID, 'mlevel_id,mprice,id');

            $goodsRes['photo'] = $mGoods->getGoodsPhotoByGoodsID($goodsID);


            $goodsRes['recpos'] = $mRecpos->getRecTtim($goodsID,1,true);
            return $goodsRes;
        }

        $param['rec_type'] = 1;
        $param['status'] = 1;
        $recposRes = $mRecpos->getRecposByParam($param);
        $this->assign('recposRes',$recposRes);
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
        $goodsID = input('goods_id');
        $goodsBase = json_decode_html(input('goods_base'));
        $goodsPrice = json_decode_html(input('goods_price'));
        $goodsAttr = json_decode_html(input('goods_attr'));
        $goodsPhoto = json_decode_html(input('goods_photo'));
        $recposRes = json_decode_html(input('goods_recpos'));
        Db::startTrans();
        $mGoods = new GoodsEvent();

        $flag = $mGoods->editGoods($goodsID, $goodsBase, $goodsPrice, $goodsAttr, $goodsPhoto,$recposRes);
        if ($flag['code'] > 0) {
            Db::rollback();
        } else {
            Db::commit();
        }
        return $flag;
    }

    public function addGoods() {
        $this->_inputAjax();
        $goodsBase = json_decode_html(input('goods_base'));
        $goodsPrice = json_decode_html(input('goods_price'));
        $goodsAttr = json_decode_html(input('goods_attr'));
        $goodsPhoto = json_decode_html(input('goods_photo'));
        $recposRes = json_decode_html(input('goods_recpos'));
        Db::startTrans();
        $mGoods = new GoodsEvent();

        $flag = $mGoods->addGoods($goodsBase, $goodsPrice, $goodsAttr, $goodsPhoto,$recposRes);
        if ($flag['code'] > 0) {
            Db::rollback();
        } else {
            Db::commit();
        }
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
        $files->size = config('uploads.goods')['size'];
        $files->saveDir = config('uploads.goods')['save_dir'];
        $files->ext = config('uploads.goods')['ext'];
        $files->thumb = config('uploads.goods')['thumb'];
        return $flag = $files->uploads('image');
    }

    //====================================================================================================

    /**
     * 获取商品分类
     */
    public function getCateGory() {
        //权限
        //获取所有的商品分类列表
        $categoryData = Db::name('category')->select();
        $mCateTree = new cateTreeUtil();
        $categoryRes = $mCateTree->setSort($categoryData);
        $this->assign('categoryRes', $categoryRes);
        $mRecpos = new Recpos();
        $param['rec_type'] = 2;
        $param['status'] = 1;
        $recposRes = $mRecpos->getRecposByParam($param);
        $this->assign('recposRes',$recposRes);
        return $this->fetch('goods/category');
    }

    /**
     * 获取商品分类
     */
    public function cateGory() {
        //权限
        //获取所有的商品分类列表
        $categoryData = Db::name('category')->alias('c')->join('rec_item', 'c.id = rec_item.value_id and value_type = 2', 'left')
            ->field('*,group_concat(recpos_id) recpos_id')
            ->order('sort desc')
            ->group('c.id')
            ->select();
        $mCateTree = new cateTreeUtil();
        $categoryRes = array_err(0, 'success');
        $categoryRes['data'] = $mCateTree->setSort($categoryData);
        return $categoryRes;
    }

    public function cateGoryChild() {
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
        $recposRes = json_decode_html(input('recpos'));

        $eGoods = new GoodsEvent();
        $flag = $eGoods->addCateGory($cateGoryData,$recposRes);
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

        if ($state == 1) {
            $categoryRes = $mCateTree->getParent($categoryData, $categoryID);
        } else {
            $categoryRes = $mCateTree->setSort($categoryData, $categoryID);
            $categoryRes = array_merge(['0' => ['id' => $categoryID]], $categoryRes);
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
    public function editCategory() {
        $this->_inputAjax();
        $cateGoryData = json_decode_html(input('data'));
        $recposRes = json_decode_html(input('recpos'));
        $cateGoryID = input('id');

        $eGoods = new GoodsEvent();
        $cateGoryRes = array();
        Db::startTrans();
        $checkCateFlag = $eGoods->checkCateGoryID($cateGoryID, true, $cateGoryRes);
        if ($checkCateFlag['code'] > 0) {
            Db::rollback();
            return $checkCateFlag;
        }

        $flag = $eGoods->editCategory($cateGoryData, $cateGoryID,$recposRes);
        if ($flag['code'] > 0) {
            Db::rollback();
            return $flag;
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

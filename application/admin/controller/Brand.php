<?php
/**
 * 商品品牌
 */

namespace app\admin\controller;

use think\Db;
use app\common\model\Brand as BrandModel;
use app\common\event\BrandEvent;
use app\common\util\FilesUtil;

class Brand extends Base
{
    /**
     * 品牌列表页面
     * @return mixed
     */
    public function index() {
        return $this->fetch();
    }


    /**
     * 获取品牌数据
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getData() {
        //todo权限
        $this->_inputAjax();
        $mBrand = new BrandModel();

        $param = json_decode_html(input('param'));
        $param['curr_page'] = input('page/d');
        $param['page_count'] = input('limit/d');
        //条数的限制
        //dealPage();
        $data = array_err(0, 'success');
        $count = $mBrand->getBrandByParamCnt($param);
        $data['count'] = $count;
        $brandRes = array();
        if ($count) {
            $brandRes = $mBrand->getBrandByParam($param);
        }

        $data['data'] = $brandRes;
        return $data;
    }

    /**
     * 修改品牌状态
     * @return array
     */
    public function setState() {
        $this->_inputAjax();
        $state = input('state/d');
        $brandID = input('id/d');
        $mBrand = new BrandEvent();

        Db::startTrans();
        $flag = $mBrand->setState($brandID, $state);

        if ($flag['code'] > 0) {
            Db::rollback();
        } else {
            Db::commit();
        }
        return $flag;
    }

    /**
     * 设置排序
     * @return array
     * @throws \Exception
     */
    public function setSort() {
        $this->_inputAjax();
        $sort = json_decode_html(input('sort'));
        Db::startTrans();
        $mBrand = new BrandEvent();

        $flag = $mBrand->setSort($sort);
        if ($flag['code'] > 0) {
            Db::rollback();
        } else {
            Db::commit();
        }
        return $flag;
    }

    /**
     * 修改品牌数据
     * @return array
     */
    public function editBrand() {
        $this->_inputAjax();
        $data = json_decode_html(input('data'));
        $brandID = input('id/d');
        Db::startTrans();
        $mBrand = new BrandEvent();

        $flag = $mBrand->editBrand($brandID, $data);
        if ($flag['code'] > 0) {
            Db::rollback();
        } else {
            Db::commit();
        }
        return $flag;
    }

    public function addBrand() {
        $this->_inputAjax();
        $data = json_decode_html(input('data'));

        $mBrand = new BrandEvent();

        $flag = $mBrand->addBrand($data);

        return $flag;
    }

    /**
     * 删除品牌
     * @return array
     */
    public function delBrand() {
        $this->_inputAjax();
        $brandID = json_decode_html(input('id'));
        Db::startTrans();
        $mBrand = new BrandEvent();

        $flag = $mBrand->delBrand($brandID);
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
}

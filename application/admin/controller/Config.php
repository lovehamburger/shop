<?php
/**
 * 商品基本配置
 */

namespace app\admin\controller;

use think\Db;
use app\common\model\Config as ConfigModel;
use app\common\event\ConfigEvent;
use app\common\util\FilesUtil;

class Config extends Base
{

    /**
     * 配置列表页面
     * @return mixed
     */
    public function index() {
        return $this->fetch();
    }


    /**
     * 获取配置数据
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getData() {
        //todo权限
        $this->_inputAjax();
        $mConfig = new ConfigModel();

        $param = json_decode_html(input('param'));
        $param['curr_page'] = input('page/d');
        $param['page_count'] = input('limit/d');
        //条数的限制
        //dealPage();
        $data = array_err(0, 'success');
        $count = $mConfig->getConfigByParamCnt($param);
        $data['count'] = $count;
        $brandRes = array();
        if ($count) {
            $field = 'id,ename,cname,form_type,conf_type,conf_type conf_type_name,values,value,sort,status';
            $brandRes = $mConfig->getConfigByParam($param, $field);
        }

        $data['data'] = $brandRes;
        return $data;
    }

    /**
     * 修改配置状态
     * @return array
     */
    public function setState() {
        $this->_inputAjax();
        $state = input('state/d');
        $brandID = input('id/d');
        $mConfig = new ConfigEvent();

        Db::startTrans();
        $flag = $mConfig->setState($brandID, $state);

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
        $mConfig = new ConfigEvent();

        $flag = $mConfig->setSort($sort);
        if ($flag['code'] > 0) {
            Db::rollback();
        } else {
            Db::commit();
        }
        return $flag;
    }

    /**
     * 修改配置数据
     * @return array
     */
    public function editConfig() {
        $this->_inputAjax();
        $data = json_decode_html(input('data'));
        $brandID = input('id/d');
        Db::startTrans();
        $mConfig = new ConfigEvent();

        $flag = $mConfig->editConfig($brandID, $data);
        if ($flag['code'] > 0) {
            Db::rollback();
        } else {
            Db::commit();
        }
        return $flag;
    }

    public function addConfig() {

        $this->_inputAjax();
        $data = json_decode_html(input('data'));

        $mConfig = new ConfigEvent();

        $flag = $mConfig->addConfig($data);

        return $flag;
    }

    /**
     * 删除配置
     * @return array
     */
    public function delConfig() {
        $this->_inputAjax();
        $brandID = json_decode_html(input('id'));
        Db::startTrans();
        $mConfig = new ConfigEvent();

        $flag = $mConfig->delConfig($brandID);
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


    /**
     * 配置展示列表
     * @return mixed
     */
    public function lst() {
        return $this->fetch();
    }
}

<?php
/**
 * 商品类型
 */

namespace app\admin\controller;

use think\Db;
use app\common\model\Type as TypeModel;
use app\common\event\TypeEvent;
use app\common\util\FilesUtil;

class Type extends Base
{
    /**
     * 类型列表页面
     * @return mixed
     */
    public function index() {
        return $this->fetch();
    }


    /**
     * 获取类型数据
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getData() {
        //todo权限
        $this->_inputAjax();
        $mType = new TypeModel();

        $param = json_decode_html(input('param'));
        $param['curr_page'] = input('page/d');
        $param['page_count'] = input('limit/d');
        //条数的限制
        //dealPage();
        $data = array_err(0, 'success');
        $count = $mType->getTypeByParamCnt($param);
        $data['count'] = $count;
        $TypeRes = array();
        if ($count) {
            $TypeRes = $mType->getTypeByParam($param);
        }

        $data['data'] = $TypeRes;
        return $data;
    }

    /**
     * 修改类型状态
     * @return array
     */
    public function setState() {
        $this->_inputAjax();
        $state = input('state/d');
        $TypeID = input('id/d');
        $mType = new TypeEvent();

        Db::startTrans();
        $flag = $mType->setState($TypeID, $state);

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
        $mType = new TypeEvent();

        $flag = $mType->setSort($sort);
        if ($flag['code'] > 0) {
            Db::rollback();
        } else {
            Db::commit();
        }
        return $flag;
    }

    /**
     * 修改类型数据
     * @return array
     */
    public function editType() {
        $this->_inputAjax();
        $data = json_decode_html(input('data'));
        $TypeID = input('id/d');
        Db::startTrans();
        $mType = new TypeEvent();

        $flag = $mType->editType($TypeID, $data);
        if ($flag['code'] > 0) {
            Db::rollback();
        } else {
            Db::commit();
        }
        return $flag;
    }

    public function addType() {
        $this->_inputAjax();
        $data = json_decode_html(input('data'));

        $mType = new TypeEvent();

        $flag = $mType->addType($data);

        return $flag;
    }

    /**
     * 删除类型
     * @return array
     */
    public function delType() {
        $this->_inputAjax();
        $TypeID = json_decode_html(input('id'));
        Db::startTrans();
        $mType = new TypeEvent();

        $flag = $mType->delType($TypeID);
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
        $files->size = config('uploads.goods_Type')['size'];
        $files->saveDir = config('uploads.goods_Type')['save_dir'];
        $files->ext = config('uploads.goods_Type')['ext'];
        $files->thumb = config('uploads.goods_Type')['thumb'];
        return $flag = $files->uploads('image');
    }
}

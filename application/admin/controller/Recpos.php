<?php
/**
 * 推荐位
 */

namespace app\admin\controller;

use think\Db;
use app\common\model\Recpos as RecposModel;
use app\common\event\RecposEvent;
use app\common\util\FilesUtil;

class Recpos extends Base
{
    /**
     * 推荐位列表页面
     * @return mixed
     */
    public function index() {
        return $this->fetch();
    }


    /**
     * 获取推荐位数据
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getData() {
        //todo权限
        $this->_inputAjax();
        $mRecpos = new RecposModel();

        $param = json_decode_html(input('param'));
        $param['curr_page'] = input('page/d');
        $param['page_count'] = input('limit/d');
        //条数的限制
        //dealPage();
        $data = array_err(0, 'success');
        $count = $mRecpos->getRecposByParamCnt($param);
        $data['count'] = $count;
        $linkRes = array();
        if ($count) {

            $linkRes = $mRecpos->getRecposByParam($param);
        }

        $data['data'] = $linkRes;
        return $data;
    }

    /**
     * 修改推荐位状态
     * @return array
     */
    public function setState() {
        $this->_inputAjax();
        $state = input('state/d');
        $linkID = input('id/d');
        $mRecpos = new RecposEvent();

        Db::startTrans();
        $flag = $mRecpos->setState($linkID, $state);

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
        $mRecpos = new RecposEvent();

        $flag = $mRecpos->setSort($sort);
        if ($flag['code'] > 0) {
            Db::rollback();
        } else {
            Db::commit();
        }
        return $flag;
    }

    /**
     * 修改推荐位数据
     * @return array
     */
    public function editRecpos() {
        $this->_inputAjax();
        $data = json_decode_html(input('data'));
        $linkID = input('id/d');
        Db::startTrans();
        $mRecpos = new RecposEvent();

        $flag = $mRecpos->editRecpos($linkID, $data);
        if ($flag['code'] > 0) {
            Db::rollback();
        } else {
            Db::commit();
        }
        return $flag;
    }

    /**
     * 添加推荐位
     * @return mixed
     */
    public function addRecpos() {
        $this->_inputAjax();
        $data = json_decode_html(input('data'));

        $mRecpos = new RecposEvent();

        $flag = $mRecpos->addRecpos($data);

        return $flag;
    }

    /**
     * 删除推荐位
     * @return array
     */
    public function delRecpos() {
        $this->_inputAjax();
        $linkID = json_decode_html(input('id'));
        Db::startTrans();
        $mRecpos = new RecposEvent();

        $flag = $mRecpos->delRecpos($linkID);
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
        $files->size = config('uploads.link')['size'];
        $files->saveDir = config('uploads.link')['save_dir'];
        $files->ext = config('uploads.link')['ext'];
        $files->thumb = config('uploads.link')['thumb'];
        return $flag = $files->uploads('image');
    }
}

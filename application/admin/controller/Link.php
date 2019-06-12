<?php
/**
 * 商品友情链接
 */

namespace app\admin\controller;

use think\Db;
use app\common\model\Link as LinkModel;
use app\common\event\LinkEvent;
use app\common\util\FilesUtil;

class Link extends Base
{
    /**
     * 友情链接列表页面
     * @return mixed
     */
    public function index() {
        return $this->fetch();
    }


    /**
     * 获取友情链接数据
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getData() {
        //todo权限
        $this->_inputAjax();
        $mLink = new LinkModel();

        $param = json_decode_html(input('param'));
        $param['curr_page'] = input('page/d');
        $param['page_count'] = input('limit/d');
        //条数的限制
        //dealPage();
        $data = array_err(0, 'success');
        $count = $mLink->getLinkByParamCnt($param);
        $data['count'] = $count;
        $linkRes = array();
        if ($count) {

            $linkRes = $mLink->getLinkByParam($param);
        }

        $data['data'] = $linkRes;
        return $data;
    }

    /**
     * 修改友情链接状态
     * @return array
     */
    public function setState() {
        $this->_inputAjax();
        $state = input('state/d');
        $linkID = input('id/d');
        $mLink = new LinkEvent();

        Db::startTrans();
        $flag = $mLink->setState($linkID, $state);

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
        $mLink = new LinkEvent();

        $flag = $mLink->setSort($sort);
        if ($flag['code'] > 0) {
            Db::rollback();
        } else {
            Db::commit();
        }
        return $flag;
    }

    /**
     * 修改友情链接数据
     * @return array
     */
    public function editLink() {
        $this->_inputAjax();
        $data = json_decode_html(input('data'));
        $linkID = input('id/d');
        Db::startTrans();
        $mLink = new LinkEvent();

        $flag = $mLink->editLink($linkID, $data);
        if ($flag['code'] > 0) {
            Db::rollback();
        } else {
            Db::commit();
        }
        return $flag;
    }

    /**
     * 添加友情链接
     * @return mixed
     */
    public function addLink() {
        $this->_inputAjax();
        $data = json_decode_html(input('data'));

        $mLink = new LinkEvent();

        $flag = $mLink->addLink($data);

        return $flag;
    }

    /**
     * 删除友情链接
     * @return array
     */
    public function delLink() {
        $this->_inputAjax();
        $linkID = json_decode_html(input('id'));
        Db::startTrans();
        $mLink = new LinkEvent();

        $flag = $mLink->delLink($linkID);
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

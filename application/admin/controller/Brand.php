<?php
/**
 * 商品品牌
 */

namespace app\admin\controller;

use think\Db;

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

    }
}

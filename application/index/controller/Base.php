<?php

namespace app\index\controller;

use app\common\model\Config as ConfigModel;
use app\common\model\Nav;
use app\common\model\Category;
use think\Controller;
use think\Request;

class Base extends Controller
{
    protected $nav = [];
    protected $configRes = [];
    protected $recommendCat = [];
    public function __construct(Request $request = null) {
        parent::__construct($request);
    }

    public function _initialize(){
        $mNav = new Nav();
        $this->nav = $mNav->getNavGroupPos();

        $mConfig = new ConfigModel();
        $field = 'ename,cname,value';
        $config = [];

        foreach ($mConfig->getConfigByParam(array(), $field) as $k=>$v){
            $config[$v['ename']] = $v['value'];
        }

        $this->configRes = $config;

        $mCategory = new Category();
        $this->recommendCat = $mCategory->getRecommendCategory(2,5);

        $this->globalAssign();
    }

    /**
     * 判断是否是AJAX
     */
    public function _inputAjax(){
        if(!$this->request->isAjax()){
            return array_err(8000,'非法请求');
        }
        return array_err(0,'success');
    }

    /**
     * 判断是否是POST
     */
    public function _inputPost(){
        if(!$this->request->isPost()){
            return array_err(8000,'非法请求');
        }
        return array_err(0,'success');
    }

    /**
     * 全局输出数据
     */
    public function globalAssign(){
        $this->assign('nav',$this->nav);
        $this->assign('configRes',$this->configRes);
        $this->assign('recommendCat',$this->recommendCat);
    }
}

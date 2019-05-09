<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Base extends Controller
{

    public function __construct(Request $request = null) {
        parent::__construct($request);

        $this->globalAssign();
    }

    /**
     * 判断是否是AJAX
     */
    public function _inputAjax(){
        if(!$this->request->isAjax()){
            return array_err(8000,'非法请求');
        }
    }

    /**
     * 全局输出数据
     */
    public function globalAssign(){


    }
}

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
     * 全局输出数据
     */
    public function globalAssign(){


    }
}

<?php
namespace app\index\controller;

class Goods extends Base
{
    public function index() {
        return $this->fetch();
    }

    public function goodsList()
    {
        return $this->fetch('goods/goods_list');
    }
}

<?php
namespace app\index\controller;

class Goods extends Base
{
    public function index() {
        echo "777";
        echo "666";
        return $this->fetch();
    }

    public function goodsList()
    {
        return $this->fetch('goods/goods_list');
    }
}

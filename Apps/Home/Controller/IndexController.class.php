<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        echo "44455554";
       echo S('test111');
       echo session('name');
    }
}

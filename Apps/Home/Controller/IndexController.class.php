<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        echo "444555511114";
       echo S('test111');
       echo session('name');
    }
}

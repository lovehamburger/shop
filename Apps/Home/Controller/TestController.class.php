<?php
namespace Home\Controller;
use Think\Controller;
class TestController extends Controller {
    public function index(){
        echo "551111522222";
   echo  S('test111',wwww); 
   $res = M('User')->select();
echo D()->getLastSql();
   echo'<pre>'; 
   print_r($res); 
   echo'</pre>'; 
           
    session('name','clh');
               
    }
}

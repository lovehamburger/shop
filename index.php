<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用入口文件

// 检测PHP环境
$touch = $_GET['touch'];
$version = $_GET['version'];
if(empty($touch)){
    echo "合并的文件不能为空";
    return;
}

if(empty($version)){
    echo "版本号不能为空";
    return;
}

$updataShell = "sudo git fetch 2>&1";
exec($updataShell, $result, $status);

if ($status) {
    echo "更新命令执行失败";
    echo'<pre>';
    print_r($result);
    echo'</pre>';
    echo'<pre>';
    print_r('状态'.$status);
    echo'</pre>';
    return;
} else {
    echo "更新命令成功执行";
    echo'<pre>';
    print_r($result);
    echo'</pre>';
    echo'<pre>';
    print_r('状态'.$status);
    echo'</pre>';
}
$shell = "sudo git checkout -m ".$version $touch."2>&1";
#$shell = "git --git-dir=/var/www/html/test/shop/.git pull 2>&1";
#$shell = "ls 2>&1";
exec($shell, $result, $status);
var_dump($result, $status);

if ($status) {
    echo "合并文件失败";
    echo'<pre>';
    print_r($result);
    echo'</pre>';
    echo'<pre>';
    print_r('合并状态'.$status);
    echo'</pre>';
    return;
} else {
    echo "合并文件成功":$touch;
    echo'<pre>';
    print_r($result);
    echo'</pre>';
    echo'<pre>';
    print_r('合并状态'.$status);
    echo'</pre>';
}
die();
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',True);

// 定义应用目录
define('APP_PATH','./Apps/');

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单

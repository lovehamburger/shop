<?php

return [
    'view_replace_str' => [
        '__PUBLIC__'=>'/public',
        '__ROOT__' => '/',
        '__PLUGINS__'=>'/public/plugins',//插件目录
        '__SKIN__' => '/public/static/'.request()->module(),//静态模块目录
    ],
]
?>
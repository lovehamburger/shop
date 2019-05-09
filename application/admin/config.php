<?php

return [
    'view_replace_str' => [
        '__PUBLIC__'=>'/public',
        '__PLUGINS__'=>'/public/plugins',
        '__ROOT__' => '/',
        '__SKIN__' => '/public/static/'.request()->module(),
    ],
]
?>
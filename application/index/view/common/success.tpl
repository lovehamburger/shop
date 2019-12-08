<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>成功页面</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport"
        content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="__PLUGINS__/layuiadmin/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="__PLUGINS__/layuiadmin/style/admin.css" media="all">
</head>
<body>


<div class="layui-fluid">
  <div class="layadmin-tips">
    <i class="layui-icon" face>&#xe664;</i>
    <div class="layui-text">
      <?php switch ($code) {?>
      <?php case 1:?>
      <h1>
      <span class="layui-anim layui-anim-loop layui-anim- layui-anim-rotate">O</span>
      <span class="layui-anim layui-anim-loop layui-anim- ">K</span>
      </h1>
      <?php break;?>
      <?php case 0:?>
      <h1>
      <div style="font-size: 20px;">
      好像出错了呢
      </div>
      </h1>
      <?php break;?>
      <?php } ?>

      <p class="detail"></p>
      <p class="jump">
        页面自动 <a id="href" href="<?php echo($url);?>">跳转</a> 等待时间： <b id="wait"><?php echo($wait);?></b>
      </p>
    </div>
  </div>
</div>

<script type="text/javascript">
  (function(){
    var wait = document.getElementById('wait'),
            href = document.getElementById('href').href;
    var interval = setInterval(function(){
      var time = --wait.innerHTML;
      if(time <= 0) {
        location.href = href;
        clearInterval(interval);
      };
    }, 1000);
  })();
</script>
</body>
</html>
<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html><head>
	    <meta charset="utf-8">
    <title>童老师ThinkPHP交流群：484519446</title>

    <meta name="description" content="Dashboard">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!--Basic Styles-->
    <link href="Public/Admin/css/bootstrap.css" rel="stylesheet"></link>
    <link href="Public/Admin/css/font-awesome.css" rel="stylesheet"></link>
    <link href="Public/Admin/css/weather-icons.css" rel="stylesheet"></link>
    <!--Beyond styles-->
    <link href="Public/Admin/css/beyond.css" id="beyond-link" rel="stylesheet" type="text/css"></link>
    <link href="Public/Admin/css/demo.css" rel="stylesheet"></link>
    <link href="Public/Admin/css/typicons.css" rel="stylesheet"></link>
    <link href="Public/Admin/css/animate.css" rel="stylesheet"></link>

    <script src="Public/Admin/js/jquery_002.js"></script>
    <script src="Public/Admin/js/bootstrap.js"></script>
    <script src="Public/Admin/js/jquery.js"></script>
    <!-- 引入ueditor -->
    <script src="Public/ueditor/ueditor.config.js"></script>
    <script src="Public/ueditor/ueditor.all.min.js"></script>
    <script src="Public/ueditor/lang/zh-cn/zh-cn.js"></script>
    <script type="text/javascript" src="Public/Admin/js/goods/lee_pic.js"></script>
    <script type="text/javascript" src="Public/uploadify/jquery.uploadify.min.js"></script>
    <link rel="stylesheet" href="Public/uploadify/uploadify.css">
    <script type="text/javascript">
        var ThinkPHP = {
        'UPLOADIFY' : 'Public/uploadify',
    };
    </script>
    <style type="text/css">
        .text{
            text-align: center;
            width: 222px;
            position: absolute;
            z-index: 1;
        }
    </style>
</head>
<body>
	<!-- 头部 -->
	<div class="navbar">
            <div class="navbar-inner">
                <div class="navbar-container">
                    <!-- Navbar Barnd -->
                    <div class="navbar-header pull-left">
                        <a class="navbar-brand" href="#">
                            <small>
                                <img alt="" src="Public/Admin/img/logo.png">
                                </img>
                            </small>
                        </a>
                    </div>
                    <!-- /Navbar Barnd -->
                    <!-- Sidebar Collapse -->
                    <div class="sidebar-collapse" id="sidebar-collapse">
                        <i class="collapse-icon fa fa-bars">
                        </i>
                    </div>
                    <!-- /Sidebar Collapse -->
                    <!-- Account Area and Settings -->
                    <div class="navbar-header pull-right">
                        <div class="navbar-account">
                            <ul class="account-area">
                                <li>
                                    <a class="login-area dropdown-toggle" data-toggle="dropdown">
                                        <div class="avatar" title="View your public profile">
                                            <img src="Public/Admin/img/adam-jansen.jpg">
                                            </img>
                                        </div>
                                        <section>
                                            <h2>
                                                <span class="profile">
                                                    <span>
                                                    <?php echo session('AdminInfo')['username'];?>
                                                    </span>
                                                </span>
                                            </h2>
                                        </section>
                                    </a>
                                    <!--Login Area Dropdown-->
                                    <ul class="pull-right dropdown-menu dropdown-arrow dropdown-login-area">
                                        <li class="username">
                                            <a>
                                                David Stevenson
                                            </a>
                                        </li>
                                        <li class="dropdown-footer">
                                            <a href="/Admin-admin-loginOut">
                                                退出登录
                                            </a>
                                        </li>
                                        <li class="dropdown-footer">
                                            <a href="/Admin-admin-publish-adminId-<?php echo session('AdminInfo')['id'];?>">
                                                修改密码
                                            </a>
                                        </li>
                                    </ul>
                                    <!--/Login Area Dropdown-->
                                </li>
                                <!-- /Account Area -->
                                <!--Note: notice that setting div must start right after account area list.
                            no space must be between these elements-->
                                <!-- Settings -->
                            </ul>
                        </div>
                    </div>
                    <!-- /Account Area and Settings -->
                </div>
            </div>
        </div>
	<!-- /头部 -->
	
	<div class="main-container container-fluid">
		<div class="page-container">
			            <!-- Page Sidebar -->
            <div class="page-sidebar" id="sidebar">
    <!-- Page Sidebar Header-->
    <div class="sidebar-header-wrapper">
        <input class="searchinput" type="text">
            <i class="searchicon fa fa-search">
            </i>
            <div class="searchhelper">
                Search Reports, Charts, Emails or Notifications
            </div>
        </input>
    </div>
    <!-- /Page Sidebar Header -->
    <!-- Sidebar Menu -->
    <ul class="nav sidebar-menu">
        <!--Dashboard-->
        <li>
            <a href="/admin/main/index.html">
                <i class="menu-icon fa fa-gear">
                </i>
                <span class="menu-text">
                    控制面板
                </span>
                <i class="menu-expand">
                </i>
            </a>
        </li>
        <li>
            <a class="menu-dropdown" href="#">
                <i class="menu-icon fa fa-gear">
                </i>
                <span class="menu-text">
                    商品管理
                </span>
                <i class="menu-expand">
                </i>
            </a>
            <ul class="submenu">
                <li>
                    <a href="<?php echo U('Cate-index');?>">
                        <span class="menu-text">
                            商品分类
                        </span>
                        <i class="menu-expand">
                        </i>
                    </a>
                </li>
                <li>
                    <a href=<?php echo U('Goods-index');?>>
                        <span class="menu-text">
                            商品列表
                        </span>
                        <i class="menu-expand">
                        </i>
                    </a>
                </li>
                <li>
                    <a href=<?php echo U('Goods-getType');?>>
                        <span class="menu-text">
                            商品类型
                        </span>
                        <i class="menu-expand">
                        </i>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a class="menu-dropdown" href="#">
                <i class="menu-icon fa fa-gear">
                </i>
                <span class="menu-text">
                    品牌管理
                </span>
                <i class="menu-expand">
                </i>
            </a>
            <ul class="submenu">
                <li>
                    <a href=<?php echo U('brand-index');?>>
                        <span class="menu-text">
                            品牌列表
                        </span>
                        <i class="menu-expand">
                        </i>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a class="menu-dropdown" href="#">
                <i class="menu-icon fa fa-gear">
                </i>
                <span class="menu-text">
                    会员管理
                </span>
                <i class="menu-expand">
                </i>
            </a>
            <ul class="submenu">
                <li>
                    <a href=<?php echo U('Member-index');?>>
                        <span class="menu-text">
                            会员列表
                        </span>
                        <i class="menu-expand">
                        </i>
                    </a>
                </li>
                <li>
                    <a href=<?php echo U('Member-level');?>>
                        <span class="menu-text">
                            会员等级
                        </span>
                        <i class="menu-expand">
                        </i>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a class="menu-dropdown" href="#">
                <i class="menu-icon fa fa-gear">
                </i>
                <span class="menu-text">
                    管理员管理
                </span>
                <i class="menu-expand">
                </i>
            </a>
            <ul class="submenu">
                <li>
                    <a href="<?php echo U('admin-list');?>">
                        <span class="menu-text">
                            管理员列表
                        </span>
                        <i class="menu-expand">
                        </i>
                    </a>
                </li>
                <li>
                    <a href=<?php echo U('admin-publish');?>>
                        <span class="menu-text">
                            用户添加
                        </span>
                        <i class="menu-expand">
                        </i>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a class="menu-dropdown" href="#">
                <i class="menu-icon fa fa-gear">
                </i>
                <span class="menu-text">
                    文章管理
                </span>
                <i class="menu-expand">
                </i>
            </a>
            <ul class="submenu">
                <li>
                    <a href="/admin/document/index.html">
                        <span class="menu-text">
                            文章列表
                        </span>
                        <i class="menu-expand">
                        </i>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a class="menu-dropdown" href="#">
                <i class="menu-icon fa fa-gear">
                </i>
                <span class="menu-text">
                    系统
                </span>
                <i class="menu-expand">
                </i>
            </a>
            <ul class="submenu">
                <li>
                    <a href="/admin/user/index.html">
                        <span class="menu-text">
                            用户管理
                        </span>
                        <i class="menu-expand">
                        </i>
                    </a>
                </li>
                <li>
                    <a href="/admin/auth_group/index.html">
                        <span class="menu-text">
                            角色管理
                        </span>
                        <i class="menu-expand">
                        </i>
                    </a>
                </li>
                <li>
                    <a href="/admin/auth_rule/index.html">
                        <span class="menu-text">
                            权限列表
                        </span>
                        <i class="menu-expand">
                        </i>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
    <!-- /Sidebar Menu -->
</div>
            <!-- /Page Sidebar -->
            <!-- Page Content -->
            <div class="page-content">
                <!-- Page Breadcrumb -->
                <div class="page-breadcrumbs">
                    <ul class="breadcrumb">
                                        <li>
                        <a href="/Admin/Index/index">系统</a>
                    </li>
                                        <li>
                        <a href="/Admin-Goods/lst">商品列表</a>
                    </li>
                                        <li class="active">添加商品</li>
                                        </ul>
                </div>
                <!-- /Page Breadcrumb -->

                <!-- Page Body -->
                <div class="page-body">
                    
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="widget">
            <div class="widget-header bordered-bottom bordered-blue">
                <span class="widget-caption">新增商品</span>
            </div>
            <div class="widget-body">
                <div id="horizontal-form">
                    <div class="form-horizontal" >
                        <!-- 111111111111111111111111111111111 -->
                        <div class="tabbable">
                        <input type="hidden" name="goodsId" value="<?php echo I('get.goodsId');?>">
                            <ul id="myTab11" class="nav nav-tabs tabs-flat">
                                <li class="active">
                                    <a href="#home11" data-toggle="tab">
                                        基本信息
                                    </a>
                                </li>
                                <li class="">
                                    <a href="#profile11" data-toggle="tab">
                                        商品描述
                                    </a>
                                </li>
                                <li class="">
                                    <a href="#profile12" data-toggle="tab">
                                        会员价格
                                    </a>
                                </li>
                                <li class="">
                                    <a href="#profile13" data-toggle="tab">
                                        商品属性
                                    </a>
                                </li>
                                <li class="">
                                    <a href="#profile14" class="profile14" data-toggle="tab">
                                        商品图片
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content tabs-flat">
                                <div class="tab-pane active" id="home11">

                                   <div class="form-group">
                                        <label class="col-sm-2 control-label no-padding-right" for="username">商品名称</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="goods_name" placeholder="" value="<?php echo ($goodRes["goods_name"]); ?>" class="form-control">
                                        </div>
                                        <p class="help-block col-sm-4 red">* 必填</p>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label no-padding-right" for="username">商品缩略图</label>
                                        <div class="col-sm-4">
                                            <input type="file"  name="original">
                                        </div>
                                        <iframe style="display: none" src="" name="up"></iframe>
                                        <input type="hidden" value='<?php echo ($goodRes["original"]); ?>' id="imgurl">
                                         <div  class="col-sm-6" class="img"><img  class="img-responsive" style="max-width: 70%;max-height: 90px;" src='<?php echo ($goodRes["sm_thumb"]); ?>'  id="imgid"/></div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label no-padding-right" for="username">所属分类</label>
                                        <div class="col-sm-6">
                                            <select id='cate_id' name="cate_id">
                                                <option value="">请选择</option>
                                                <?php if(is_array($cateRes)): $i = 0; $__LIST__ = $cateRes;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if($goodRes['cate_id'] == $vo['id']): ?>selected<?php endif; ?>><?php echo ($vo["html"]); echo ($vo["catename"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                            </select>
                                        </div>
                                        <p class="help-block col-sm-4 red">* 必填</p>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label no-padding-right" for="username">所属品牌</label>
                                        <div class="col-sm-6">
                                            <select id="brand_id" name="brand_id">
                                            <option value="0">请选择</option>
                                            <?php if(is_array($brandRes)): $i = 0; $__LIST__ = $brandRes;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if($goodRes['brand_id'] == $vo['id']): ?>selected<?php endif; ?>><?php echo ($vo["brand_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label no-padding-right" for="username">市场价格</label>
                                        <div class="col-sm-6">
                                            <input type="text"  name="market_price" placeholder="" value="<?php echo ($goodRes["market_price"]); ?>"  class="form-control" >
                                        </div>
                                        <p class="help-block col-sm-4 red">* 必填</p>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label no-padding-right" for="username">本店价格</label>
                                        <div class="col-sm-6">
                                            <input type="text"  name="shop_price" placeholder="" value="<?php echo ($goodRes["shop_price"]); ?>" class="form-control" >
                                        </div>
                                        <p class="help-block col-sm-4 red">* 必填</p>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label no-padding-right" for="username">商品重量</label>
                                        <div class="col-sm-6">
                                            <input type="text"  name="goods_weight" placeholder="" value="<?php echo ($goodRes["goods_weight"]); ?>" style="width:80%; float:left; margin-right:10px;" class="form-control" >
                                            <select name="weight_unit">
                                                <option value="g">克</option>
                                                <option value="kg">千克</option>
                                            </select>
                                        </div>
                                        <p class="help-block col-sm-4 red">* 必填</p>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label no-padding-right" for="username">是否上架</label>
                                       <div class="col-sm-6">
                                           <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="onsale"  value='' class="colored-success" <?php if($goodRes['onsale'] == 1): ?>checked="checked"<?php endif; ?>>
                                                    <span class="text">上架</span>
                                                </label>
                                            </div>
                                        </div>
                                        <p class="help-block col-sm-4 red">* 必填</p>
                                    </div>
                                    <!-- <div class="form-group">
                                        <label class="col-sm-2 control-label no-padding-right" for="username">商品推荐</label>
                                       <div class="col-sm-6">
                                           <div class="checkbox">
                                                <?php if(is_array($recposres)): $i = 0; $__LIST__ = $recposres;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$recpos): $mod = ($i % 2 );++$i;?><label style="margin-right:15px;">
                                                        <input type="checkbox" name="recid[]" value="<?php echo ($recpos["id"]); ?>"  class="colored-success">
                                                        <span class="text"><?php echo ($recpos["recname"]); ?></span>
                                                    </label><?php endforeach; endif; else: echo "" ;endif; ?>
                                            </div>
                                        </div>
                                    </div> -->

                                </div>

                                <div class="tab-pane" id="profile11">
                                    <textarea class="textarea_editor span8" id="goods_desc" name="goods_desc"><?php echo ($goodRes["goods_desc"]); ?></textarea>
                                </div>

                                <div class="tab-pane" id="profile12">
                                    <?php if(is_array($Levels)): $i = 0; $__LIST__ = $Levels;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="username"><?php echo ($vo["level_name"]); ?></label>
                                            <div class="col-sm-4">
                                                <input type="text" value="<?php echo ($PriceArr[$vo['id']]); ?>" level='<?php echo ($vo["id"]); ?>' name="mp[<?php echo ($vo["id"]); ?>]" class="form-control mp">
                                            </div>
                                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                                </div>

                                <div class="tab-pane" id="profile13">
                                    <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right" for="username">选择商品类型</label>
                                            <div class="col-sm-6">
                                               <select <?php if(I('goodsId')): ?>disabled="disabled"<?php endif; ?> id='type_id' name="type_id">
                                                   <option value=''>选择商品类型</option>
                                                   <?php if(is_array($goodsType)): $i = 0; $__LIST__ = $goodsType;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if($goodRes['type_id'] == $vo['id']): ?>selected<?php endif; ?>><?php echo ($vo["type_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                               </select>
                                            </div>
                                    </div>
                                    <div>
                                        <ul id="attr">
                                            <?php if(I('goodsId') && $attr): if(is_array($attr)): foreach($attr as $k=>$vo): ?><div val="<?php echo ($vo["id"]); ?>" class='form-group'>
                        <label class='col-sm-2 control-label no-padding-right'  for='username'><?php echo ($vo["attr_name"]); ?></label>
                        <div class='col-sm-6'>
                            <?php if($vo['attr_type'] == 0 && $vo['attr_values'] == '' ): ?><input type='text' val="<?php echo ($goodsAttrs[$vo['id']][0]['id']); ?>" name='old_attr_value' placeholder='' value="<?php echo ($goodsAttrs[$vo['id']][0]['attr_value']); ?>" class='form-control'><?php endif; ?>
                            <?php if($vo['attr_type'] == 0 && $vo['attr_values'] != '' ): ?><select val="<?php echo ($goodsAttrs[$vo['id']][0]['id']); ?>" name='old_attr_value'>
                                    <?php $_result=explode(',',$vo['attr_values']);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($i % 2 );++$i;?><option <?php if($goodsAttrs[$vo['id']][0]['attr_value'] == $value): ?>selected<?php endif; ?>><?php echo ($value); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </select><?php endif; ?>
                            <?php if($vo['attr_type'] == 1 && $vo['attr_values'] != '' ): ?><div class='form-group row only'>
                                    <div class='col-sm-2'>
                                    <select val="<?php echo ($vo["id"]); ?>" name='old_attr_value'>
                                        <?php $_result=explode(',',$vo['attr_values']);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($i % 2 );++$i;?><option values="<?php echo ($goodsAttrs[$vo['id']][$key]['id']); ?>" <?php if($goodsAttrs[$vo['id']][0]['attr_value'] == $value): ?>selected<?php endif; ?>><?php echo ($value); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                   </div>
                                   <label class='col-sm-2 control-label no-padding-right' for='username'>价格</label>
                                   <div class='col-sm-3'>
                                   <input type='text' name='old_attr_price' value="<?php echo ($goodsAttrs[$vo['id']][0]['attr_price']); ?>" placeholder='' class='form-control'>
                                   </div>
                                    <span class='addAttr' value="<?php echo ($goodsAttrs[$vo['id']][0]['id']); ?>">[+]</span>
                                </div>
                                <?php if(is_array($goodsAttrs[$vo['id']])): foreach($goodsAttrs[$vo['id']] as $keys=>$values): if($keys > 0): ?><div class='form-group'>
                                    <div class='col-sm-2'>
                                    <select val="<?php echo ($vo["id"]); ?>" name='old_attr_value'>
                                        <?php $_result=explode(',',$vo['attr_values']);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($i % 2 );++$i;?><option values="<?php echo ($goodsAttrs[$vo['id']][$key]['id']); ?>" <?php if($values['attr_value'] == $value): ?>selected<?php endif; ?>><?php echo ($value); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                   </div>
                                   <label class='col-sm-2 control-label no-padding-right' for='username'>价格</label>
                                   <div class='col-sm-3'>
                                   <input type='text' value="<?php echo ($values['attr_price']); ?>" name='old_attr_price' placeholder='' class='form-control'>
                                   </div>
                                    <span class='addAttr' value="<?php echo ($values['id']); ?>">[-]</span>
                                    </div><?php endif; endforeach; endif; endif; ?>
                        </div>
                    </div><?php endforeach; endif; endif; ?>
                                        </ul>
                                    </div>
                                </div>

                                <div class="tab-pane" id="profile14">
                                    <input type="file" name="file" id="file">
                                    <div class="goods_pic_list">
                                        <?php if(is_array($goodsPicRes)): foreach($goodsPicRes as $key=>$value): ?><div class="pic_content" style="display:inline;"><span class="remove"></span><span class="text delPic" onclick="delPic(<?php echo ($value['id']); ?>)" val="<?php echo ($value['id']); ?>">删除</span><img src="<?php echo ($value["sm_thumb"]); ?>" class="pic_list" style="display: inline; left: -61px; top: -19px;"></div><?php endforeach; endif; ?>
                                    </div>
                                    <div class="pic_img"></div>
                                </div>
                            </div>
                        </div>
                        <button style="width:150px;" class="btn btn-darkorange btn-block goods">添加商品</button>
                        <!-- 111111111111111111111111111111111 -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

                </div>
                <!-- /Page Body -->
            </div>
            <!-- /Page Content -->
		</div>	
	</div>

    <!--Beyond Scripts-->
    <script src="Public/Admin/js/beyond.js"></script>
    <script src="Public/Admin/js/goods/add.js"></script>
    <script src="Public/Admin/js/goods/lee_pic.js"></script>
    <script src="Public/layer/layer.js"></script>
</body></html>
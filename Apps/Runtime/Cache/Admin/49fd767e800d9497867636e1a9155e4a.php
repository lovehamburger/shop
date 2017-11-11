<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
            <title>
                陈龙辉
            </title>
            <meta content="Dashboard" name="description"></meta>
            <meta content="width=device-width, initial-scale=1.0" name="viewport"></meta>
            <meta content="IE=edge" http-equiv="X-UA-Compatible"></meta>
            <meta content="text/html; charset=utf-8" http-equiv="Content-Type"></meta>
            <!--Basic Styles-->
            <link href="Public/Admin/css/bootstrap.css" rel="stylesheet"></link>
            <link href="Public/Admin/css/font-awesome.css" rel="stylesheet"></link>
            <link href="Public/Admin/css/weather-icons.css" rel="stylesheet"></link>
            <!--Beyond styles-->
            <link href="Public/Admin/css/beyond.css" id="beyond-link" rel="stylesheet" type="text/css"></link>
            <link href="Public/Admin/css/demo.css" rel="stylesheet"></link>
            <link href="Public/Admin/css/typicons.css" rel="stylesheet"></link>
            <link href="Public/Admin/css/animate.css" rel="stylesheet"></link>
            <link href="Public/layer/skin/layui.css" rel="stylesheet"></link>
            <link rel="stylesheet" href="Public/laypage/laypage.css" />
            <script src="Public/Admin/js/jquery_002.js"></script>
            <script src="Public/Admin/js/bootstrap.js"></script>
            <script src="Public/Admin/js/jquery.js"></script>
            <!-- 引入ueditor -->
            <script src="Public/ueditor/ueditor.config.js"></script>
            <script src="Public/ueditor/ueditor.all.min.js"></script>
            <script src="Public/ueditor/lang/zh-cn/zh-cn.js"></script>
        </meta>
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
                    <a href="<?php echo U('Article-index');?>">
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
                    <!-- Page articlecrumb -->
                    <div class="page-breadcrumbs">
                        <ul class="breadcrumb">
                            <li>
                                <a href="#">
                                    文章
                                </a>
                            </li>
                            <li class="active">
                                文章管理
                            </li>
                        </ul>
                    </div>
                    <!-- /Page articlecrumb -->
                    <!-- Page Body -->
                    <div class="page-body">
                        <div class="row">
                            <div class="col-lg-12 col-sm-12 col-xs-12">
                                <div class="widget">
                                <input type="hidden" id=articleId value="<?php echo ($articleRes["id"]); ?>">
                                    <div class="widget-header bordered-bottom bordered-blue">
                                        <span class="widget-caption">
                                        <?php if($publishInfo): echo ($publishInfo); ?>
                                        <?php else: ?>
                                            新增文章<?php endif; ?>
                                        </span>
                                    </div>
                                    <div class="widget-body">
                                        <div id="horizontal-form">
                                            <div class="form-horizontal" method="post" role="form">
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label no-padding-right" for="article_name">
                                                        文章标题:
                                                    </label>
                                                    <div class="col-sm-6">
                                                        <input class="form-control" id="title" name="title" placeholder="" value="<?php echo ($articleRes["title"]); ?>" required="" type="text">
                                                        </input>
                                                    </div>
                                                    <p class="help-block col-sm-4 red">
                                                        * 必填
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label no-padding-right" for="article_name">
                                                        文章栏目:
                                                    </label>
                                                    <div class="col-sm-6">
                                                        <select name='articleCate' id='cate_id'>
                                                            <?php if(is_array($articleCate)): $i = 0; $__LIST__ = $articleCate;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value='<?php echo ($vo["id"]); ?>'><?php echo ($vo["catename"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                                        </select>
                                                    </div>
                                                    <p class="help-block col-sm-4 red">
                                                        * 必填
                                                    </p>
                                                </div>
                                                <div class="form-group span4">
                                                    <label class="col-sm-2 control-label no-padding-right" for="article_name">
                                                        文章内容:
                                                    </label>
                                                    <div class="tab-pane" id="profile11">
                                                        <textarea class="textarea_editor span4" id="article_desc" name="article_desc"><?php echo ($articleRes["content"]); ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-offset-2 col-sm-10">
                                                        <button class="btn btn-default sub_btn article">
                                                            保存信息
                                                        </button>
                                                    </div>
                                                </div>
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
        <script src="Public/Admin/js/article/add.js"></script>
        <script src="Public/layer/layer.js"></script>
    </body>
</html>
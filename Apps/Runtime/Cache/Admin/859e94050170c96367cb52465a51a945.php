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
                            <li class="active">
                                控制面板
                            </li>
                        </ul>
                    </div>
                    <!-- /Page Breadcrumb -->
                    <!-- Page Body -->
                    <div class="page-body">
                        <div style="text-align:center; line-height:1000%; font-size:24px;">
                            童老师THinkPHP第五季 大型商城项目开发
                            <br/>
                            <p style="color:#aaa;">
                                ThinkPHP交流群：484519446
                            </p>
                        </div>
                    </div>
                </div>
                <!-- /Page Body -->
            </div>
            <!-- /Page Content -->
        </div>
    </body>
</html>
<!--Basic Scripts-->
<script src="Public/Admin/js/jquery_002.js">
</script>
<script src="Public/Admin/js/bootstrap.js">
</script>
<script src="Public/Admin/js/jquery.js">
</script>
<!--Beyond Scripts-->
<script src="Public/Admin/js/beyond.js">
</script>
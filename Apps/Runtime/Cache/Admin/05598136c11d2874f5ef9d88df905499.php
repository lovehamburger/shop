<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<!--Head-->
	<head>
		<meta charset="utf-8">
			<title>
				童老师ThinkPHP交流群：484519446
			</title>
			<meta content="login page" name="description">
				<meta content="width=device-width, initial-scale=1.0" name="viewport">
					<meta content="IE=edge" http-equiv="X-UA-Compatible">
						<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
							<!--Basic Styles-->
							<link href="Public/Admin/css/bootstrap.css" rel="stylesheet">
								<link href="Public/Admin/css/font-awesome.css" rel="stylesheet">
									<!--Beyond styles-->
									<link href="Public/Admin/css/beyond.css" id="beyond-link" rel="stylesheet">
										<link href="Public/Admin/css/demo.css" rel="stylesheet">
											<link href="Public/Admin/css/animate.css" rel="stylesheet">
											</link>
										</link>
									</link>
								</link>
							</link>
						</meta>
					</meta>
				</meta>
			</meta>
		</meta>
	</head>
	<!--Head Ends-->
	<!--Body-->
	<body>
		<div class="login-container animated fadeInDown">
				<div class="loginbox bg-white">
					<div class="loginbox-title">
						商城SHOP
					</div>
					<div class="loginbox-textbox">
						<input class="form-control" name="username" placeholder="username" type="text">
						</input>
					</div>
					<div class="loginbox-textbox">
						<input class="form-control" name="password" placeholder="password" type="password">
						</input>
					</div>
					<div class="loginbox-textbox">
						<input class="form-control" style="width: 50%;display: inline;" name="verify" placeholder="验证码" type="text">
						</input>
						<img src="Admin-Login-setVerify" onclick="this.src='Admin-Login-setVerify?'+Math.random()"  style="width: 48%">
					</div>
					<div class="loginbox-submit">
						<input class="btn btn-primary btn-block login" type="submit" value="Login">
						</input>
					</div>
				</div>
				<!-- <div class="logobox">
					<p class="text-center">clh</p>
				</div> -->
		</div>
		<!--Basic Scripts-->
		<script src="Public/Admin/js/jquery_002.js"></script>
        <script src="Public/Admin/js/bootstrap.js"></script>
        <script src="Public/Admin/js/jquery.js"></script>
        <!--Beyond Scripts-->
        <script src="Public/Admin/js/beyond.js"></script>
        <script src="Public/layer/layer.js"></script>
		<script src="Public/Admin/js/login.js">
		</script>
	</body>
	<!--Body Ends-->
</html>
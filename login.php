<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>蓝翔技校登录界面</title>

<link rel="stylesheet" href="Public/css/font-awesome.min.css"/>
<link rel="stylesheet" href="Public/css/loginMy.css"/>

<style>
html,body{width:100%;}
</style>

</head>
<body>

<div class="main">

	<div class="center">
		<form action="http://localhost:8081/mytkp/index.php/Home/User/login" id="formOne" method="post" onsubmit="return submitB()" >
			<i class="fa fa-user Cone">  | </i>
			<input type="text" name="userName" id="userName" placeholder="用户名" onblur="checkUser()"/>
			<span id="user_pass"></span>
			<br/>
			<i class="fa fa-key Cone">  | </i>
			<input type="password" name="userPass" id="userPass" placeholder="密码" onblur="checkUser1()"/>
			<span id="pwd_pass"></span>
			<br/>
			<i class="fa fa-folder-open Cone">  | </i>
			<input type="text" name="surePwd" id="surePwd" placeholder="验证码" onblur="checkUser2()"/>
			<span id="surePwd_pass" ></span><br/>
			<p>a 
				<?php
                        if (isset($_SESSION["loginError"])) {
                            echo $_SESSION["loginError"];
                            session_destroy();
                            unset($_SESSION["loginError"]);
                        }
                    ?>
			</p>
			<input type="submit" value="登录" id="submit" name="submit" />
			<br/>
		</form>
	</div>
	
</div>


<!-- <script type="text/javascript" src="js/loginMy.js"></script> -->
<div style="text-align:center;">
</div>
</body>
</html>
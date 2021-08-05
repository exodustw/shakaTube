<?php
	@session_start();
	if(@$_SESSION["usercode"] == "" || @$_SESSION["usercode"] == NULL){
		$html = "<li class=\"nav-item\"><a href=\"login.php\" class=\"nav-link\">Login</a></li>";
	}else{
		$html = "<li class=\"nav-item\"><a href=\"account.php\" class=\"nav-link\">My Account</a></li>".
		"<li class=\"nav-item\"><a href=\"exec/logout_exec.php\" class=\"nav-link\">Logout</a></li>";;
	}
	echo str_replace("<phpcontent>", $html, file_get_contents('template/header.html'));
?>

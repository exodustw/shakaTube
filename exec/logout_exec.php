<?php
	session_start();
	$_SESSION["usercode"] = "";
	$_SESSION["auth"] = "";
	echo "<script>location.replace('../index.php');</script>";
?>

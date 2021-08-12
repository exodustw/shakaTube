<?php
	require_once('login_check.php');
	if(@$_GET["hash"] != "" && @$_GET["type"] != ""){
		require("exec/pdo_mysql.php");
		$sql = "CALL FAVMediaLikeChange(:op,:hash,:type);";
		$sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(
			":op" => $_SESSION["usercode"],
			":vid" => $_GET["hash"],
			":type" => $_GET["type"]
		));
	}
?>

<?php
	require_once('login_check.php');
	if(@$_REQUEST["hash"] != "" && @$_REQUEST["content"] != ""){
		if (!isset($json)) $json = new stdClass();
		header('Content-Type: application/json');

		require("pdo_mysql.php");
		$sql = "CALL COMAdd(:op,:hash,:content);";
		$sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(
			":op" => $_SESSION["usercode"],
			":hash" => $_REQUEST["hash"],
			":content" => $_REQUEST["content"]
		));
		$row = $sth->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT);
		$json->status = $row[0];
		echo json_encode($json);
	}else{
		http_response_code(422);
	}
?>

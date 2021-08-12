<?php
	if(@$_POST["id"] != "" && @$_POST["name"] != "" && @$_POST["pw"] != "" && @$_POST["pw2"] != ""){
		if($_POST["pw"] == $_POST["pw2"]){
			if (!isset($json)) $json = new stdClass();
			header('Content-Type: application/json');

			require("pdo_mysql.php");
			$sql = "CALL MEMAccountAdd(:id,:pw,:name,:level,:type);";
			$sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$sth->execute(array(
				":id" => $_POST["id"],
				":pw" => hash('sha512', $_POST["pw"]),
				":name" => $_POST["name"],
				":level" => 1,
				":type" => 1
			));
			$row = $sth->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT);
			$json->status = $row[0];
			echo json_encode($json);
		}else{
			http_response_code(422);
			echo "Password Check Not Match.";
		}
	}else{
		http_response_code(422);
		echo "Parameters Inquire.";
	}
?>

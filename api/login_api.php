<?php
	session_start();
	if (!isset($json)) $json = new stdClass();
	header('Content-Type: application/json');

    $userid = @$_POST["email"];
    $userpw = hash('sha512', @$_POST["password"]);

    if($userid != "" && $userpw != ""){
		error_reporting (E_ERROR | E_WARNING | E_PARSE);

		if($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]){
			$ip = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"];
		}elseif($HTTP_SERVER_VARS["HTTP_CLIENT_IP"]){
			$ip = $HTTP_SERVER_VARS["HTTP_CLIENT_IP"];
		}elseif ($HTTP_SERVER_VARS["REMOTE_ADDR"]){
			$ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
		}elseif (getenv("HTTP_X_FORWARDED_FOR")){
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		}elseif (getenv("HTTP_CLIENT_IP")){
			$ip = getenv("HTTP_CLIENT_IP");
		}elseif (getenv("REMOTE_ADDR")){
			$ip = getenv("REMOTE_ADDR");
		}else{
			$ip = "Unknown";
		}

		$app = "PHP: ".$_SERVER['SERVER_NAME'];

		require("pdo_mysql.php");
		$sql = "CALL SYSLoginCheck(:v1,:v2,:v3,:v4);";
		$sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(
			":v1" => $userid,
			":v2" => $userpw,
			":v3" => $ip,
			":v4" => $app));
		$data = $sth->fetchAll();
      	if(count($data) == 1){
        	if($data[0]["登入狀態"] == 1){
          		$_SESSION["usercode"] = $data[0]["會員編號"];
          		$_SESSION["auth"] = $data[0]["等級"];
  				$_SESSION["ip"] = $ip;
				$json->info = "Success";
		    }elseif($data[0]["登入狀態"] == -1){
				http_response_code(401);
				$json->info = "帳號或密碼錯誤";
		    }elseif($data[0]["登入狀態"] == -2){
				http_response_code(401);
				$json->info = "帳號或密碼錯誤";
		    }elseif($data[0]["登入狀態"] == -3){
				http_response_code(401);
				$json->info = "帳號重複";
			}elseif($data[0]["登入狀態"] == -4){
				http_response_code(401);
				$json->info = "帳號為空";
		    }else{
				http_response_code(500);
				$json->info = "SQL ERROR (Invalid Result)";
		    }
      	}else{
        	//echo $query;
			http_response_code(500);
			$json->info = "SQL ERROR (No Response)";
      	}
    }else{
		http_response_code(401);
		$json->info = "帳號密碼為空";
    }
	echo json_encode($json);
?>

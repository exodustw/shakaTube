<?php
	session_start();
    $userid = $_POST["email"];
    $userpw = hash('sha512', $_POST["password"]);

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
		$row = $sth->fetchAll();
      	if(count($row) == 1){
        	if($row[0]["登入狀態"] == 1){
          		$_SESSION["usercode"] = $row[0]["會員編號"];
          		$_SESSION["auth"] = $row[0]["等級"];
  				$_SESSION["ip"] = $ip;
          		echo "<script>location.replace('../index.php');</script>";
		    }elseif($row[0]["登入狀態"] == -1){
				http_response_code(401);
		      	echo "<script>alert('帳號或密碼錯誤');location.replace('../login.php');</script>";
		    }elseif($row[0]["登入狀態"] == -2){
				http_response_code(401);
		      	echo "<script>alert('帳號或密碼錯誤');location.replace('../login.php');</script>";
		    }elseif($row[0]["登入狀態"] == -3){
				http_response_code(401);
		      	echo "<script>alert('帳號重複問題\n請洽管理員處理！');location.replace('../login.php');</script>";
			}elseif($row[0]["登入狀態"] == -4){
				http_response_code(401);
		      	echo "<script>alert('帳號不可為空！');location.replace('../login.php');</script>";
		    }else{
				http_response_code(500);
		      	echo "<script>alert('SQL ERROR (Invalid Result)');location.replace('../ErrorPages/exception.php');</script>";
		    }
      	}else{
        	//echo $query;
			http_response_code(500);
        	echo "<script>alert('SQL ERROR (No Response)');location.replace('../Errorpages/exception.php');</script>";
      	}
    }else{
		http_response_code(401);
      	echo "<script>alert('帳號密碼不得為空');location.replace('../login.php');</script>";
    }
?>

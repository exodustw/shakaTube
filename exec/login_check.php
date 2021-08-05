<?php
    session_start();
    if($_SESSION["usercode"] == NULL || $_SESSION["usercode"] == ""){
		http_response_code(401);
    	header("Location: ../login.php");
	}
?>

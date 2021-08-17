<?php
	session_start();
?>
<!DOCTYPE html>
<html lang="zh-TW" dir="ltr">
	<head>
		<!-- Required meta tags -->
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

		<title>shakaTube</title>
	</head>
	<body>
		<div class="container">
		    <?php require_once('template/header.php'); ?>
	  	</div>
		<div class="container" id="alert"></div>
		<div class="container">
			<h2>Sign Up</h2>
			<form id="reg" action="api/register_api.php" method="POST">
				<div class="mb-3 row">
					<label for="id" class="col-sm-2 col-form-label">Email</label>
					<div class="col-sm-10">
	  					<input type="email" class="form-control" id="id" name="id" placeholder="Please Enter A Valid Email">
					</div>
				</div>
				<div class="mb-3 row">
					<label for="name" class="col-sm-2 col-form-label">Username</label>
					<div class="col-sm-10">
	  					<input type="text" class="form-control" id="name" name="name" placeholder="Will Be Your Channel Name">
					</div>
				</div>
				<div class="mb-3 row">
				    <label for="pw" class="col-sm-2 col-form-label">Password</label>
				    <div class="col-sm-10">
				      	<input type="password" class="form-control" id="pw" name="pw">
				    </div>
				</div>
				<div class="mb-3 row">
				    <label for="pw2" class="col-sm-2 col-form-label">Re-Enter Password</label>
				    <div class="col-sm-10">
				      	<input type="password" class="form-control" id="pw2" name="pw2">
				    </div>
				</div>
				<input class="btn btn-primary" type="submit" value="Submit">
			</form>
	  	</div>
		<script type="text/javascript">
			$(document).ready(function () {
				$("#reg").submit(function (event) {
					var formData = $(this).serialize();

					$.ajax({
						type: "POST",
						url: "api/register_api.php",
						data: formData,
						dataType: "json",
						encode: true,
					}).done(function (data) {
					  	if(data.status > 0){
							$("#alert").html('<div class="alert alert-success" role="alert">Registration Success! Click <a href="login.php" class="alert-link">here</a> to login.</div>');
						}else{
							$("#alert").html('<div class="alert alert-warning" role="alert">Registration Failed! This email address has been used.</div>');
						}
					}).fail(function (jqXHR) {
						$("#alert").html('<div class="alert alert-danger" role="alert">Registration Failed!</div>');
					});

					event.preventDefault();
				});
			});
		</script>
	</body>
</html>

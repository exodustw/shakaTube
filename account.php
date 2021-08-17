<?php
	require_once('exec/login_check.php');
?>
<!DOCTYPE html>
<html lang="zh-TW" dir="ltr">
	<head>
		<!-- Required meta tags -->
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

		<title>shakaTube</title>
	</head>
	<body>
		<div class="container">
		    <?php require_once('template/header.php'); ?>
	  	</div>
		<div class="container">
			<div class="row">
				<nav class="nav-pills flex-column col-2">
					<a class="nav-link" href="account.php?page=myvideos">Your videos</a>
					<a class="nav-link" href="account.php?page=history">History</a>
					<a class="nav-link active" href="upload.php">Upload video</a>
				</nav>
				<div class="col-10">
					<?php
						if(@$_GET['page'] == 'myvideos'){
							echo '<h2>Your Videos</h2>';
							require("exec/pdo_mysql.php");
						    $sql = "CALL MEDListInquire(:op);";
							  $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
							$sth->execute(array(":op" => @$_SESSION["usercode"] == "" ? 0 : $_SESSION["usercode"]));
							while ($row = $sth->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
								echo '<div class="col">
												<div class="card mb-3" style="max-width: 75%;">
													<div class="row g-0">
														<div class="col-md-4">
															<a href="player.php?video='.$row["hash"].'">
																<img src="media/upload/'.$row["hash"].'/thumbnail.png" class="card-img-top"
																alt="..." onerror="this.onerror=null;this.src=\'media/thumbnail/default_image_2.png\';" >
															</a>
														</div>
														<div class="col-md-8">
															<div class="card-body">
																<h5 class="card-title"><a href="player.php?video='.$row["hash"].'">'.$row["標題"].'</a></h5>
																<small class="text-muted">'.$row["上傳時間"].'</small>
															</div>
														</div>
													</div>
												</div>
									  	</div>';
						  }
						}else if(@$_GET['page'] == 'history'){
							echo '<h2>History</h2>';
						}else{
							echo '<h2>My Account</h2>';
						}
					?>
				</div>
	  	</div>
	  </div>
	</body>
</html>

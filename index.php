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

		<title>shakaTube</title>
	</head>
	<body>
		<div class="container">
		    <?php require_once('template/header.php'); ?>
	  	</div>
		<div class="container">
			<h2>New Videos</h2>
			<div class="row row-cols-1 row-cols-md-3 g-4">
				<?php
					require("exec/pdo_mysql.php");
					$sql = "CALL MEDListInquire(:op);";
					$sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
					$sth->execute(array(":op" => @$_SESSION["usercode"] == "" ? 0 : $_SESSION["usercode"]));
					while ($row = $sth->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
						echo '<div class="col">
							    <div class="card h-100">
							      <img src="media/thumbnail/default_image.png" class="card-img-top" alt="...">
							      <div class="card-body">
							        <h5 class="card-title"><a href="player.php?video='.$row["hash"].'">'.$row["標題"].'</a></h5>
							      </div>
							      <div class="card-footer">
							        <small class="text-muted">'.$row["上傳時間"].'</small>
							      </div>
							    </div>
							  </div>';
				    }
				?>
			</div>
	  	</div>

	</body>
</html>

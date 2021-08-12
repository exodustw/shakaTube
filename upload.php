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

		<script type="text/javascript">
			function _(el) {
				return document.getElementById(el);
			}

			function uploadFile() {
				var file = _("file1").files[0];
				// alert(file.name+" | "+file.size+" | "+file.type);
				var formdata = new FormData();
				formdata.append("file1", file);
				var ajax = new XMLHttpRequest();
				ajax.upload.addEventListener("progress", progressHandler, false);
				ajax.addEventListener("load", completeHandler, false);
				ajax.addEventListener("error", errorHandler, false);
				ajax.addEventListener("abort", abortHandler, false);
				ajax.open("POST", "exec/upload_exec.php");
				ajax.send(formdata);
			}

			function progressHandler(event) {
				_("loaded_n_total").innerHTML = "Uploaded " + event.loaded + " bytes of " + event.total;
				var percent = (event.loaded / event.total) * 100;
				_("progressBar").value = Math.round(percent);
				_("status").innerHTML = Math.round(percent) + "% uploaded... please don't close this page.";
			}

			function completeHandler(event) {
				_("status").innerHTML = event.target.responseText;
				_("progressBar").value = 0; //wil clear progress bar after successful upload
			}

			function errorHandler(event) {
				_("status").innerHTML = "Upload Failed";
			}

			function abortHandler(event) {
				_("status").innerHTML = "Upload Aborted";
			}
		</script>
		<title>shakaTube</title>
	</head>
	<body>
		<div class="container">
		    <?php require_once('template/header.php'); ?>
	  	</div>
		<div class="container">
			<h1>Upload Your Video</h1>
			<form id="upload_form" enctype="multipart/form-data" method="post">
				<div class="mb-3">
				  <label for="file1" class="form-label">Select File</label>
				  <input class="form-control" type="file" name="file1" id="file1" onchange="uploadFile()">
				</div>
				<progress id="progressBar" value="0" max="100" style="width:300px;"></progress>
				<h3 id="status"></h3>
				<p id="loaded_n_total"></p>
			</form>
	  	</div>
	</body>
</html>

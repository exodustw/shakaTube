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

		<!-- Shaka Player compiled library: -->
		<script src="js/shaka-player.compiled.js"></script>
		<!-- Shaka Player ui compiled library: -->
		<script src="js/shaka-player.ui.js"></script>
		<!-- Shaka Player ui compiled library default CSS: -->
		<link rel="stylesheet" type="text/css" href="js/controls.css">
		<!-- Chromecast SDK (if you want Chromecast support for your app): -->
		<script defer src="https://www.gstatic.com/cv/js/sender/v1/cast_sender.js"></script>
		<!-- Your application source: -->
		<script>
			async function init() {
				async function initVideo(element, manifestUri, type){
					if(type == 'vod'){
						// When using the UI, the player is made automatically by the UI object.
						const video = document.getElementById(element);
						const ui = video['ui'];
						const controls = ui.getControls();
						const player = controls.getPlayer();

						const config = {
							'seekBarColors': {
								base: 'rgba(255, 255, 255, 0.3)',
								buffered: 'rgba(255, 255, 255, 0.54)',
								played: 'rgb(255, 0, 0)'
							},
							'overflowMenuButtons' : ['cast', 'quality', 'playback_rate']
						}
						ui.configure(config);
						// Attach player and ui to the window to make it easy to access in the JS console.
						window.player = player;
						window.ui = ui;

						// Listen for error events.
						player.addEventListener('error', onPlayerErrorEvent);
						controls.addEventListener('error', onUIErrorEvent);

						// Try to load a manifest.
						// This is an asynchronous process.
						try {
							await player.load(manifestUri);
							// This runs if the asynchronous load is successful.
							console.log('The video has now been loaded!');
						} catch (error) {
							onPlayerError(error);
						}
					}else if(type == 'live'){
						// When using the UI, the player is made automatically by the UI object.
						const video = document.getElementById(element);
						const ui = video['ui'];
						const controls = ui.getControls();
						const player = controls.getPlayer();

						const config = {
							'controlPanelElements': ['play_pause', 'time_and_duration', 'spacer', 'mute', 'volume', 'fullscreen'],
							//'addSeekBar': false
						}
						ui.configure(config);

						player.configure({
							streaming: {
								lowLatencyMode: true,
								inaccurateManifestTolerance: 0,
								rebufferingGoal: 0.01,
							}
						});
						// Attach player and ui to the window to make it easy to access in the JS console.
						window.player = player;
						window.ui = ui;

						// Listen for error events.
						player.addEventListener('error', onPlayerErrorEvent);
						controls.addEventListener('error', onUIErrorEvent);

						// Try to load a manifest.
						// This is an asynchronous process.
						try {
							await player.load(manifestUri);
							// This runs if the asynchronous load is successful.
							console.log('The video has now been loaded!');
						} catch (error) {
							onPlayerError(error);
						}
					}else{
						// When using the UI, the player is made automatically by the UI object.
						const video = document.getElementById(element);
						const ui = video['ui'];
						const controls = ui.getControls();
						const player = controls.getPlayer();

						const config = {
							'seekBarColors': {
								base: 'rgba(255, 255, 255, 0.3)',
								buffered: 'rgba(255, 255, 255, 0.54)',
								played: 'rgb(255, 0, 0)'
							},
							'overflowMenuButtons' : ['cast', 'quality', 'playback_rate']
						}
						ui.configure(config);
						// Attach player and ui to the window to make it easy to access in the JS console.
						window.player = player;
						window.ui = ui;

						// Listen for error events.
						player.addEventListener('error', onPlayerErrorEvent);
						controls.addEventListener('error', onUIErrorEvent);

						// Try to load a manifest.
						// This is an asynchronous process.
						try {
							await player.load(manifestUri);
							// This runs if the asynchronous load is successful.
							console.log('The video has now been loaded!');
						} catch (error) {
							onPlayerError(error);
						}
					}
				}
				initVideo('video','media/upload/<?php if(isset($_GET['video'])) echo $_GET['video']; else echo ''; ?>/dash.mpd', 'vod');
			}

			function onPlayerErrorEvent(errorEvent) {
				// Extract the shaka.util.Error object from the event.
				onPlayerError(event.detail);
			}

			function onPlayerError(error) {
				// Handle player error
				console.error('Error code', error.code, 'object', error);
			}

			function onUIErrorEvent(errorEvent) {
				// Extract the shaka.util.Error object from the event.
				onPlayerError(event.detail);
			}

			function initFailed(errorEvent) {
				// Handle the failure to load; errorEvent.detail.reasonCode has a
				// shaka.ui.FailReasonCode describing why.
				console.error('Unable to load the UI library!');
			}

			// Listen to the custom shaka-ui-loaded event, to wait until the UI is loaded.
			document.addEventListener('shaka-ui-loaded', init);
			// Listen to the custom shaka-ui-load-failed event, in case Shaka Player fails
			// to load (e.g. due to lack of browser support).
			document.addEventListener('shaka-ui-load-failed', initFailed);
		</script>
		<title>shakaTube</title>
	</head>
	<body>
		<div class="container">
		    <?php require_once('template/header.php'); ?>
	  	</div>
		<div class="container">
			<?php
				require("exec/pdo_mysql.php");
				$sql = "CALL MEDInquire(:op,:hash);";
				$sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
				$sth->execute(array(
					":op" => @$_SESSION["usercode"] != "" ? $_SESSION["usercode"] : 0,
					":hash" => @$_GET["video"]
				));
				$row = $sth->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
			?>
			<!-- The data-shaka-player-container tag will make the UI library place the controls in this div.
			The data-shaka-player-cast-receiver-id tag allows you to provide a Cast Application ID that
			the cast button will cast to; the value provided here is the sample cast receiver. -->
			<div data-shaka-player-container style="max-width: 100%"
				data-shaka-player-cast-receiver-id="1BA79154">
				<!-- The data-shaka-player tag will make the UI library use this video element.
				If no video is provided, the UI will automatically make one inside the container div. -->
				<video data-shaka-player id="video" style="width:100%;height:100%"></video>
			</div>
			<h2><?php echo @$row["標題"]; ?></h2>
			<p>上傳時間:<?php echo @$row["上傳時間"]; ?></p>
			<div class="btn-group" role="group">
				<button type="button" class="btn btn-outline-danger" data-bs-toggle="button" autocomplete="off" aria-pressed="true" id="like" onclick="like_ajax(1);">Like</button>
				<button type="button" class="btn btn-outline-dark" data-bs-toggle="button" autocomplete="off" aria-pressed="true" id="dislike" onclick="like_ajax(-1);">Dislike</button>
			</div>

			<h3>Comments</h3>
			<div class="container" id="alert"></div>
			<form id="newcom" action="api/media_comment_add_api.php" method="POST">
				<div class="mb-3">
					<input type="hidden" name="hash" value="<?php if(isset($_GET["video"])) echo $_GET["video"]; ?>">
				  	<label for="comment" class="form-label">Your Comment</label>
				  	<textarea class="form-control" id="comment" name="content" rows="3"></textarea>
				</div>
				<input class="btn btn-primary" type="submit" value="Submit">
			</form>

			<?php
				$sql = "CALL COMInquire(:op,:hash);";
				$sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
				$sth->execute(array(
					":op" => @$_SESSION["usercode"],
					":hash" => @$_GET["video"]
				));
				while($row2 = $sth->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
					echo "<p>".$row2["名稱"]."</p>";
					echo "<p>".$row2["內容"]."(".$row2["時間"].")</p>";
				}

			?>
	  	</div>
		<script type="text/javascript">
			var userfav = <?php if(isset($row["userfav"]))echo $row["userfav"]; else echo 0;?>;
			var like = <?php if(isset($row["like"]))echo $row["like"]; else echo 0;?>;
			var dislike = <?php if(isset($row["dislike"]))echo $row["dislike"]; else echo 0;?>;
			var like_ele = document.getElementById('like');
			var dislike_ele = document.getElementById('dislike');

			function like_refresh(type){
				if(userfav != type){
					if(userfav == 1){
						like -= 1;
						like_ele.setAttribute('onclick', 'like_ajax(1);');
						like_ele.classList.remove("active");
					}else if(userfav == -1){
						dislike -= 1;
						dislike_ele.setAttribute('onclick', 'like_ajax(-1);');
						dislike_ele.classList.remove("active");
					}

					if(type == 1){
						like += 1;
						like_ele.setAttribute('onclick', 'like_ajax(0);');
						like_ele.classList.add("active");
					}else if(type == -1){
						dislike += 1;
						dislike_ele.setAttribute('onclick', 'like_ajax(0);');
						dislike_ele.classList.add("active");
					}
				}
				like_ele.innerHTML = 'Like (' + like + ')';
				dislike_ele.innerHTML = 'Dislike (' + dislike + ')';
				userfav = type;
			}

			function like_ajax(type){
				$.ajax({
					url: 'api/media_like_change_api.php?hash=' + '<?php if(isset($_GET["video"])) echo $_GET["video"]; ?>' +
					'&type=' + type,
					type: 'GET',
					success: function() {
						//alert("success");
						like_refresh(type);
					},
					error: function(xhr, ajaxOptions, thrownError){
						//$("body").append(xhr.status);
						//$("body").append(xhr.responseText);

						alert(thrownError);
					}
				});
			}

			$(document).ready(function () {
				window.onload = function(){
					like_ele.innerHTML = 'Like (' + like + ')';
					dislike_ele.innerHTML = 'Dislike (' + dislike + ')';

					if(userfav == 1){
						like_ele.setAttribute('onclick', 'like_ajax(0);');
						like_ele.classList.add("active");
					}else if(userfav == -1){
						dislike_ele.setAttribute('onclick', 'like_ajax(0);');
						dislike_ele.classList.add("active");
					}
				}

				$("#newcom").submit(function (event) {
					var formData = $(this).serialize();

					$.ajax({
						type: "POST",
						url: "api/media_comment_add_api.php",
						data: formData,
						dataType: "json",
						encode: true,
					}).done(function (data) {
					  	if(data.status > 0){
							$("#alert").html('<div class="alert alert-success" role="alert">Submit Success!</div>');
						}else{
							$("#alert").html('<div class="alert alert-warning" role="alert">Submit Failed!</div>');
						}
					}).fail(function (jqXHR) {
						$("#alert").html('<div class="alert alert-danger" role="alert">Submit Failed!</div>');
					});

					event.preventDefault();
				});
			});
		</script>
	</body>
</html>

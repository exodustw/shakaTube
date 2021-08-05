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
			<h1>shakaPlayer</h1>
			<h2><?php echo @$_GET['video'] ?></h2>
			<!-- The data-shaka-player-container tag will make the UI library place the controls in this div.
			The data-shaka-player-cast-receiver-id tag allows you to provide a Cast Application ID that
			the cast button will cast to; the value provided here is the sample cast receiver. -->
			<div data-shaka-player-container style="max-width:40em"
				data-shaka-player-cast-receiver-id="1BA79154">
				<!-- The data-shaka-player tag will make the UI library use this video element.
				If no video is provided, the UI will automatically make one inside the container div. -->
				<video data-shaka-player id="video" style="width:100%;height:100%"></video>
			</div>

	  	</div>

	</body>
</html>

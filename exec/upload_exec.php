<?php
	$dir = "../media/upload/";

	$fileName = $_FILES["file1"]["name"]; // The file name
	$fileTmpLoc = $_FILES["file1"]["tmp_name"]; // File in the PHP tmp folder
	$fileType = $_FILES["file1"]["type"]; // The type of file it is
	$fileSize = $_FILES["file1"]["size"]; // File size in bytes
	$fileErrorMsg = $_FILES["file1"]["error"]; // 0 for false... and 1 for true
	if (!$fileTmpLoc) { // if file not chosen
	    echo "ERROR: Please browse for a file before clicking the upload button.";
	    exit();
	}
	if(move_uploaded_file($fileTmpLoc, $dir.$fileName)){
		$cmd = 'mkdir '.
		'/var/www/video/media/upload/'.
		str_replace(' ','\\ ',str_replace('.','_',$fileName)).' >/dev/null 2>/dev/null &';
		shell_exec($cmd);
		$cmd = 'ffmpeg -re -i /var/www/video/media/upload/'.str_replace(' ','\\ ',$fileName).
		' -map 0 -map 0 -c:a aac -c:v libx264 '.
		'-b:v:0 800k -b:v:1 300k -s:v:0 1280x720 '.
		'-s:v:1 854x480 -profile:v:1 baseline -profile:v:0 '.
		'main -bf 1 -keyint_min 120 -g 120 -sc_threshold 0 -b_strategy 0 '.
		'-ar:a:1 22050 -use_timeline 1 -use_template 1 '.
		'-adaptation_sets "id=0,streams=v id=1,streams=a" -f dash '.
		'/var/www/video/media/upload/'.
		str_replace(' ','\\ ',str_replace('.','_',$fileName)).
		'/dash.mpd >/dev/null 2>/dev/null &';
		shell_exec($cmd);
	    echo "$fileName upload is complete<br>".$cmd;
	} else {
	    echo "move_uploaded_file function failed";
	}
?>

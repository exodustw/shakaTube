<?php
	require_once('login_check.php');

//	function video_info($vid) {
//		if(file_exists($vid)){
//			$finfo = finfo_open(FILEINFO_MIME_TYPE);
//			$mime_type = finfo_file($finfo, $vid); // check mime type
//			finfo_close($finfo);
//
//			if(preg_match('/video\/*/', $mime_type)){
//				$video_attributes = _get_video_attributes($vid);
//
//				print_r('Codec: ' . $video_attributes['codec'] . '<br/>');
//				print_r('Dimension: ' . $video_attributes['width'] . ' x ' . $video_attributes['height'] . ' <br/>');
//				print_r('Duration: ' . $video_attributes['hours'] . ':' . $video_attributes['mins'] . ':'
//						. $video_attributes['secs'] . '.' . $video_attributes['ms'] . '<br/>');
//
//				print_r('Size:  ' . _filesize(filesize($vid)));
//			}else{
//				print_r('File is not a video.');
//			}
//		}else{
//			print_r('File does not exist.');
//		}
//	}

	function _get_video_attributes($video) {

		$command = 'ffmpeg -i ' . str_replace(' ','\\ ',$video) . ' -vstats 2>&1';
		$output = shell_exec($command);

		//$regex_sizes = "/Video: ([^,]*), ([^,]*), ([0-9]{1,4})x([0-9]{1,4})/";
		$regex_sizes = "/Video: ([^\r\n]*), ([^,]*), ([0-9]{1,4})x([0-9]{1,4})/";
		if (preg_match($regex_sizes, $output, $regs)) {
			$codec = $regs [1] ? $regs [1] : null;
			$width = $regs [3] ? $regs [3] : null;
			$height = $regs [4] ? $regs [4] : null;
		}

		$regex_duration = "/Duration: ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}).([0-9]{1,2})/";
		if (preg_match($regex_duration, $output, $regs)) {
			$hours = $regs [1] ? $regs [1] : null;
			$mins = $regs [2] ? $regs [2] : null;
			$secs = $regs [3] ? $regs [3] : null;
			$ms = $regs [4] ? $regs [4] : null;
		}

		return array('codec' => $codec,
			'width' => $width,
			'height' => $height,
			'hours' => $hours,
			'mins' => $mins,
			'secs' => $secs,
			'ms' => $ms
			//,'command' => $command ,'plain' => $output
		);
	}

	function _filesize($bytes, $decimals = 2) {
		$sz = 'BKMGTP';
		$factor = floor((strlen($bytes) - 1) / 3);
		return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
	}

	$dir = "../media/upload/";

	$fileName = $_FILES["file1"]["name"]; // The file name
	$fileTmpLoc = $_FILES["file1"]["tmp_name"]; // File in the PHP tmp folder
	$fileType = $_FILES["file1"]["type"]; // The type of file it is
	$fileSize = $_FILES["file1"]["size"]; // File size in bytes
	$fileErrorMsg = $_FILES["file1"]["error"]; // 0 for false... and 1 for true

	if(!$fileTmpLoc){ // if file not chosen
	    echo "ERROR: Please browse for a file before clicking the upload button.";
	    exit();
	}

	if(move_uploaded_file($fileTmpLoc, $dir.$fileName)){
		//get video info
		$vinfo = _get_video_attributes('/var/www/video/media/upload/'.$fileName);

		//enroll in database
		$nhash = hash_file('md5', $dir.$fileName);
		do{
			$fhash = $nhash;
			require("pdo_mysql.php");
			$sql = "CALL MEDAdd(:v1,:v2,:v3,:v4,:v5);";
			$sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$sth->execute(array(
				":v1" => $_SESSION["usercode"],
				":v2" => $fileName,
				":v3" => "",
				":v4" => $fhash,
				":v5" => 1));
			$row = $sth->fetchAll();
			$nhash = hash('md5', $fhash);
		}while($row[0][0] <= 0);

		//mkdir
		$cmd1 = 'mkdir /var/www/video/media/upload/'.$fhash;
		shell_exec($cmd1);

		//convert
		$cmd2 = 'ffmpeg -re -i /var/www/video/media/upload/'.str_replace(' ','\\ ',$fileName).
		' -map 0 -map 0 -c:a aac -c:v libx264 '.
		'-b:v:0 2048k -b:v:1 1024k -s:v:0 1280x720 '.
		'-s:v:1 854x480 -profile:v:1 baseline -profile:v:0 '.
		'main -bf 1 -keyint_min 120 -g 120 -sc_threshold 0 -b_strategy 0 '.
		'-ar:a:1 22050 -use_timeline 1 -use_template 1 '.
		'-adaptation_sets "id=0,streams=v id=1,streams=a" -f dash '.
		'/var/www/video/media/upload/'.$fhash.'/dash.mpd >/dev/null 2>/dev/null &';
		shell_exec($cmd2);

	    echo "$fileName upload is complete<br>".$cmd1;
	}else{
	    echo "move_uploaded_file function failed";
	}
?>

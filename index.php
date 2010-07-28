<?php

session_start();

$Error = false;
$upload_name = 'Filedata';

function HandleError($message) {
	$Error = $message;
}

if(isset($_FILES[$upload_name])) {
	// Check post_max_size (http://us3.php.net/manual/en/features.file-upload.php#73762)
	$POST_MAX_SIZE = ini_get('post_max_size');
	$unit = strtoupper(substr($POST_MAX_SIZE, -1));
	$multiplier = ($unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1)));

	if ((int)$_SERVER['CONTENT_LENGTH'] > $multiplier*(int)$POST_MAX_SIZE && $POST_MAX_SIZE) {
		HandleError('File too large');
	}

	// Settings
	@mkdir("./uploads/".session_id(), 0755);
	@mkdir("./uploads/".session_id()."/tmp", 0755);
	@mkdir("./uploads/".session_id()."/imgset", 0755);
	$save_path = "./uploads/".session_id()."/";
	$upload_name = "Filedata";
	$max_file_size_in_bytes = 2147483647;				// 2GB in bytes
	$extension_whitelist = array("jpg", "gif", "png");	// Allowed file extensions
	$valid_chars_regex = '.A-Z0-9_ !@#$%^&()+={}\[\]\',~`-';// Characters allowed in the file name (in a Regular Expression format)
	
	// Other variables	
	$MAX_FILENAME_LENGTH = 260;
	$file_name = "";
	$file_extension = "";
	$uploadErrors = array(
		0=>"There is no error, the file uploaded with success",
		1=>"The uploaded file exceeds the upload_max_filesize directive in php.ini",
		2=>"The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
		3=>"The uploaded file was only partially uploaded",
		4=>"No file was uploaded",
		6=>"Missing a temporary folder"
	);

	// Validate the upload
	if (!isset($_FILES[$upload_name])) {
		HandleError("No upload found in \$_FILES for " . $upload_name);
	} else if (isset($_FILES[$upload_name]["error"]) && $_FILES[$upload_name]["error"] != 0) {
		HandleError($uploadErrors[$_FILES[$upload_name]["error"]]);
	} else if (!isset($_FILES[$upload_name]["tmp_name"]) || !@is_uploaded_file($_FILES[$upload_name]["tmp_name"])) {
		HandleError("Upload failed is_uploaded_file test.");
	} else if (!isset($_FILES[$upload_name]['name'])) {
		HandleError("File has no name.");
	}
	
	// Validate the file size (Warning: the largest files supported by this code is 2GB)
	$file_size = @filesize($_FILES[$upload_name]["tmp_name"]);
	if (!$file_size || $file_size > $max_file_size_in_bytes) {
		HandleError("File exceeds the maximum allowed size");
	}

	if ($file_size <= 0) {
		HandleError("File size outside allowed lower bound");
	}

	// Validate file name (for our purposes we'll just remove invalid characters)
	$file_name = preg_replace('/[^'.$valid_chars_regex.']|\.+$/i', "", basename($_FILES[$upload_name]['name']));
	if (strlen($file_name) == 0 || strlen($file_name) > $MAX_FILENAME_LENGTH) {
		HandleError("Invalid file name");
	}

	// Validate that we won't over-write an existing file
	if (file_exists($save_path . $file_name) && !@unlink($save_path . $file_name)) {
		HandleError("File with this name already exists and cannot be deleted");
	}

	// Validate file extension
	$path_info = pathinfo($_FILES[$upload_name]['name']);
	$file_extension = $path_info["extension"];
	$is_valid_extension = false;
	foreach ($extension_whitelist as $extension) {
		if (strcasecmp($file_extension, $extension) == 0) {
			$is_valid_extension = true;
			break;
		}
	}
	if (!$is_valid_extension || !getimagesize($_FILES["Filedata"]["tmp_name"])) {
		HandleError("Invalid image file or unknown format");
	}

	// Process the file
	if (!(@move_uploaded_file($_FILES[$upload_name]["tmp_name"], $save_path.$file_name) && chmod($save_path.$file_name, 0755))) {
		HandleError("File could not be saved");
	}
	
	if(!$Error) {
		// Success!  Redirect to the file editor
		/* Redirect to a different page in the current directory that was requested */
		
		if($_REQUEST['setname']) $SetName = urlencode($_REQUEST['setname']);
		else $SetName = 'AddArtShow';
		$host  = $_SERVER['HTTP_HOST'];
		$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$extra = 'edit.php?f='.$file_name.'&saveName='.$SetName;
		header("Location: http://$host$uri/$extra");
		exit;
	}
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<title>Upload Image</title>
<link href="css/default.css" rel="stylesheet" type="text/css" />

<!-- includes to image area select and jquery -->
<script type="text/javascript" src="js/jquery-1.3.2.js"></script> 

</head>
<body>
		<div style="margin-left: auto; margin-right: auto; width: 800px;">
		<!-- SWFUpload interfaz -->
		<? if($Error) echo $Error.'<br/>'; ?>
		<div>
			<form id="form1" action="index.php" method="post" enctype="multipart/form-data" style="margin-left: auto; margin-right: auto; width: 800px;">
				<div>
				<label for="Filedata" style="display: inline; clear:none;">Upload Image File: </label><input type="file" name="Filedata" value="" id="Filedata" style="display: inline; clear:none;">
				</div>
				<div>
				<label for="setname" style="display: inline; clear:none;">Image Set Name: </label><input type="text" name="setname" value="MyExhibit" id="setname" style="display: inline; clear:none;">
				</div>
				<input type="submit" name="submit" value="Submit" id="submit" style="margin-left: auto; margin-right: auto; text-align: center;">
			</form>
		</div>
		</div>
</body>
</html>
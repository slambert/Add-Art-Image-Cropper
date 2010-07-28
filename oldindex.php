<?php
	session_start();

	if (count($_FILES)) {
        // Handle degraded form uploads here.  Degraded form uploads are POSTed to index.php.  SWFUpload uploads
		// are POSTed to upload.php
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<title>Image Manipulation Tool</title>
<link href="css/default.css" rel="stylesheet" type="text/css" />

<!-- includes to image area select and jquery -->
<script type="text/javascript" src="js/jquery-1.3.2.js"></script> 
<script type="text/javascript" src="js/jquery.imgareaselect-0.6.2.js"></script> 
<script type="text/javascript" src="js/img.settings.js"></script> 


<?php 
	echo "<script type='text/javascript'>";
	// var to store the session id
	echo "var current_session = '".session_id()."';";
	echo "var img_path = 'uploads/".session_id()."';";
	//echo "alert(current_session);"; 
	echo "</script>";
?>
<!-- Includes to SWFUpload scripts -->
<script type="text/javascript" src="swfupload/swfupload.js"></script>
<script type="text/javascript" src="js/swfupload.queue.js"></script>
<script type="text/javascript" src="js/fileprogress.js"></script>
<script type="text/javascript" src="js/handlers.js"></script>
<script type="text/javascript" src="js/swfupload.settings.js"></script>

<script type="text/javascript">
var upload;
$(window).load(function () { 
	//defining editing as selectable img area starting with the scale in the choiceSize list.
	$('#editing').imgAreaSelect({ 
		aspectRatio: sizes[$('#choiceSize').val()]['w'] + ':' + sizes[$('#choiceSize').val()]['h'], 
		onSelectChange: preview, 
		onSelectEnd: setSizes
	});
	//creating the SWFUpload to manage all about uploads 
	upload = new SWFUpload(settings);
});
</script>
</head>
<body>
	<div style="float:left">
		<!-- SWFUpload interfaz -->
		<div>
			<form id="form1" action="index.php" method="post" enctype="multipart/form-data">
				<div class="fieldset flash" id="fsUploadProgress1">
					<span class="legend">Upload Images</span>
				</div>
				<div style="padding-left: 5px;">
					<span id="spanButtonPlaceholder1"></span>
					<input id="btnCancel1" type="button" value="Cancel Uploads" onclick="cancelQueue(upload1);" disabled="disabled" style="margin-left: 2px; height: 22px; font-size: 8pt;" />
				</div>
			</form>
		</div>
		<!-- list of availables images -->
		<div>
			<select id="choiceImage" >
			</select>
		</div>
		<!-- list of availables sizes -->
		<div>
			<select id="choiceSize" >
				<script type="text/javascript">
					for(size in sizes)
						document.write("<option value='"+size+"'>"+size+"</option>");
				</script>
			</select>
		</div>
	</div>
	<!-- div with the editable image -->
	<div>
		<span class="legend">88x31</span>
	</div>
	<div class="container">
		<p>
			<img id="editing" src="" alt="Images to Edit"
			title="Images to Edit" style="float: left; margin-right: 10px;" />
		</p>
	</div>
	<!-- name to the image -->
	<div>
		<input type="text" id="img-name" />
	</div>
	<!-- when all done -->
	<form name="finished" action="manipulation.php" method="post">
		<div>
			<input type="button" id="done" value="DONE" />
			<input type="hidden" id="xmlinfo" name="xmlinfo" value =""/>
			<input id="sessionfolder" type="hidden" value="<?=session_id()?>" name="sessionfolder" />
		</div>
	</form>
	
	
	</script>
	<!--div>
		<div class="fieldset flash">
			<span class="legend">88x31</span>
		</div>
	</div-->
</body>
</html>

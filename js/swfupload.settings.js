var settings = {
	// Backend Settings
	upload_url: "upload.php",	// Relative to the SWF file (or you can use absolute paths)
	//post_params: {"PHPSESSID" : "<?php echo session_id(); ?>"},
	post_params: {"sessionfolder" : current_session},
	
	// File Upload Settings
	file_size_limit : "102400",	// 100MB
	file_types : "*.jpg;*.gif",
	file_types_description : "Images",
	file_upload_limit : "10",
	file_queue_limit : "0",

	// Event Handler Settings (all my handlers are in the Handler.js file)
	file_dialog_start_handler : fileDialogStart,
	file_queued_handler : fileQueued,
	file_queue_error_handler : fileQueueError,
	file_dialog_complete_handler : fileDialogComplete,
	upload_start_handler : uploadStart,
	upload_progress_handler : uploadProgress,
	upload_error_handler : uploadError,
	upload_success_handler : uploadSuccess,
	upload_complete_handler : uploadComplete,

	// Button Settings
	button_image_url : "./images/XPButtonUploadText_61x22.png",	// Relative to the SWF file
	button_placeholder_id : "spanButtonPlaceholder1",
	button_width: 61,
	button_height: 22,
	
	// Flash Settings
	flash_url : "swfupload/swfupload.swf",
	
	custom_settings : {
		progressTarget : "fsUploadProgress1",
		cancelButtonId : "btnCancel1"
	},
	
	// Debug Settings
	debug: false	
};
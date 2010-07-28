<?

session_start();

if(isset($_POST['exhibit_name']) && strlen(trim($_POST['exhibit_name'])) > 0) {
	$ExhibitName = $_POST['exhibit_name'];
} else { 
	$ExhibitName = "MyExhibit";
}

if(isset($_POST['zipbutton']) && count(scandir('./uploads/'.session_id().'/imgset/'.$ExhibitName.'/')) >= 1) {
	zipify('./uploads/'.session_id().'/imgset/'.$ExhibitName.'/', $ExhibitName);
} else {
	die('Error: not enough arguments');
}

function zipify($Dir, $ExhibitName) {
	chdir($Dir);
		
	$filename = '../../tmp/'.$ExhibitName.'.zip';

	echo 'real filename: '.realpath($filename);

	if(size_dir('.') > disk_free_space('.'))
		die('Error: not enough free space to zip');
			
	ignore_user_abort(true);
	
	$command='zip -r '.escapeshellarg($filename).' .';
	exec($command);
			
	if(connection_aborted()) {
		unlink($filename);
		die();
	}
	else {
		header('Content-Description: File Transfer');
	   header('Content-Type: application/zip');
	   header('Content-Disposition: attachment; filename='.urlencode($ExhibitName).'.zip');
	   header('Content-Transfer-Encoding: binary');
	   header('Expires: 0');
	   header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	   header('Pragma: public');
	   header('Content-Length: ' . filesize($filename));
	   ob_clean();
	   flush();
	   readfile($filename);
		unlink($filename);
	   exit;
	}
}

function getSize($file) {
	chmod($file, 0755);
   	$size = filesize($file);
  	if ($size < 0)
    	$size = trim(`stat -c%s $file`);
   	return $size;
}

function size_dir($path) {
	chmod($path, 0755);
	if (!is_dir($path))
   		return getSize($path);
	if(FALSE === ($handle = opendir($path)))
		return -1;
	$size=0;
	foreach (scandir($path) as $file){
   		if ($file=='.' or $file=='..')
       		continue;
		$dirsize=size_dir($path.'/'.$file);
		if($dirsize == -1)
			$dirsize = 0;
		$size+=$dirsize;
	}
	return $size;
}

function dirsize($path)
{
  $old_path = getcwd();
  if(!is_dir($old_path."/".$path)) return -1;
  $size = trim(shell_exec(escapeshellcmd("cd \"".$old_path."/".$path."\"; du -sb; cd \"".$old_path."\";")), "\x00..\x2F\x3A..\xFF");

  return $size;
}

?>
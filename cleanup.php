<?

define('SECONDS_BEFORE_DELETE', 1209600);
define('CLEANUP_DEBUG_MODE', false);

// check to make sure we're running from CLI
if(isset($_SERVER['HTTP_HOST'])) {
   die();
}

$Dir = './uploads/';
$DirHandle  = opendir($Dir);
while (false !== ($Filename = readdir($DirHandle))) {
    $Files[] = $Filename;
}

foreach($Files as $File) {
   $File = $Dir.$File;
   
   // skip non-directories and special folder aliases
   if($File === $Dir.'.' || $File === $Dir.'..' || !is_dir($File))
      continue;
            
   $ModTime = filemtime($File);
   
   if(time() - $ModTime > SECONDS_BEFORE_DELETE) {
		if(CLEANUP_DEBUG_MODE) {
      	echo $File.' would be deleted
';
		} else {
			recursiveDelete($File);
		}
   }
}

/**
* Delete a file or recursively delete a directory
*
* from comment at http://php.net/manual/en/function.unlink.php
*
* @param string $str Path to file or directory
*/
function recursiveDelete($str) {
   if(is_file($str)) {
      return @unlink($str);
   }
   elseif(is_dir($str)) {
      $scan = glob(rtrim($str,'/').'/*');
      foreach($scan as $index=>$path) {
         recursiveDelete($path);
      }
      return @rmdir($str);
   }
}

?>
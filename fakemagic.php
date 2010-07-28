<?

class Imagick {
	protected $sessionPath;
	protected $tempPath;
	protected $imagePath;
	
	function __construct() {
		$this->sessionPath = './uploads/'.session_id().'/';
	}
	
	public function readImage($FilePath) {
		if($FilePath != $this->tempPath) {
			$this->imagePath = $FilePath;
			$Info = pathinfo($FilePath);
		   $this->tempPath = $this->sessionPath.'tmp/'.$Info['basename'];
			copy($this->imagePath, $this->tempPath) or die('Error copying file in read');
		}
	}
	
	public function writeImage($FilePath) {
		if($FilePath !== $this>tempPath) {
			copy($this->tempPath, $FilePath) or die('Error copying file in write.');
		}
	}

	
	public function getImageHeight() {
		$location='/usr/bin/identify';
      $convert=$location . ' ' . escapeshellarg($this->tempPath);
      exec ($convert, $output= array());
		$outputLine = $output[0];
		$pieces = explode(" ", $outputLine);
		$dimensions = $pieces[2];
		$pieces = explode("x", $dimensions);
		return (int)$pieces[1];
	}
	
	public function cropImage($Width, $Height, $X1, $Y1) {
		$location='/usr/bin/mogrify';
		$geometry = $Width.'x'.$Height.'+'.$X1.'+'.$Y1;
      $convert=$location . ' -crop ' . $geometry . ' ' . escapeshellarg($this->tempPath);
      exec ($convert);
		return true;
	}
	
	
	public function resizeImageWithQuadraticFilter($NewWidth, $NewHeight) {
		$location='/usr/bin/mogrify';
		$geometry = $Width.'x'.$Height;
      $convert=$location . ' -adaptive-resize ' . $geometry . ' -filter Quadratic ' . escapeshellarg($this->tempPath);
      exec ($convert);
		return true;
	}
	
	public function resizeImage($NewWidth, $NewHeight, $Dummy1, $Dummy2) {
		$this->resizeImage($NewWidth, $NewHeight);
	}
}

?>
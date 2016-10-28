<?php
class ProfileImage
{
	const IMAGE_TYPE_PROFILE = 1;
	const IMAGE_TYPE_PORTFOLIO = 2;
	
	public function __construct($postedFile = null)
    {
        if(null != $postedFile)
		{
			$this->ImagePath = $postedFile->WebFilePath;
			$this->ThumbnailPath = $postedFile->WebThumbnailPath;
			$this->ImageSize = $postedFile->Size;
			$this->ProfileID = $postedFile->ProfileID;
			$this->ImageType = ProfileImage::IMAGE_TYPE_PORTFOLIO;
			$this->ImageName = $postedFile->FileName;
		}
    }

    public $ID = 0;
    public $ProfileID = -1;
    
	public $ImagePath = "";
	public $ThumbnailPath = "";
	public $ImageType = -1;
	public $ImageSize = -1;
    public $Order = 0;
	public $ImageName = "";
	
	public $PhysicalImagePath = "";
	public $PhysicalThumbnailPath = "";
	
	public static function GetFileName($path)
	{
		$arr = explode("/", $path);
		return $arr[count($arr) - 1];
	}
}
?>

<?php

/**
 * class FileInfo
 *
 * Description for class FileInfo
 *
 * @author:
*/
class PostedFile 
{
	public function __construct()
	{
		
	}
	
	public $FileName = null;
	public $PhysicalStagingPath = null;
	public $PhysicalFilePath = null;
	public $PhysicalThumbnailPath = null;
	public $FileExtension = null;
	public $WebFilePath = null;
	public $WebThumbnailPath = null;
	public $TempPath = null;
	public $FileType = null;
	public $Size = null;
	public $HasError = false;
	public $ErrorMessage = null;
	public $ProfileID = 0;
	
	private $BaseDirectory = "";
	private $StagingDirectory = "";
	private $FullsizeDirectory = "";
	private $ThumbnailDirectory = "";
	
	private $WebBaseDirectory = "";
	private $WebFullsizeDirectory = "";
	private $WebThumbnailDirectory = "";
	
	public function Save($profileID)
	{
		$this->ProfileID = $profileID;
		$this->BaseDirectory = Server::MapPath(Configuration::$ImageDirectory, true).Configuration::$LocalDirectoryHack.$this->ProfileID;
		$this->StagingDirectory = $this->BaseDirectory.Configuration::$LocalDirectoryHack."Staging";
		$this->FullsizeDirectory = $this->BaseDirectory.Configuration::$LocalDirectoryHack."Full";
		$this->ThumbnailDirectory = $this->BaseDirectory.Configuration::$LocalDirectoryHack."Thumb";
		
		$this->WebBaseDirectory = Configuration::$ImageDirectory."/".$this->ProfileID;
		$this->WebFullsizeDirectory = $this->WebBaseDirectory."/Full";
		$this->WebThumbnailDirectory = $this->WebBaseDirectory."/Thumb";
		
		$offset = 1;	
		while(is_file($this->FullsizeDirectory.Configuration::$LocalDirectoryHack.$this->FileName)) 
		{
			$this->FileName = $this->GetTemplatedName($this->FileName, $offset);
			$offset++;
		}
		$this->PhysicalStagingPath = $this->StagingDirectory.Configuration::$LocalDirectoryHack.$this->FileName;
		$this->PhysicalFilePath = $this->FullsizeDirectory.Configuration::$LocalDirectoryHack.$this->FileName;
		$this->PhysicalThumbnailPath = $this->ThumbnailDirectory.Configuration::$LocalDirectoryHack.$this->FileName;
		
		$this->WebFilePath = $this->WebFullsizeDirectory."/".$this->FileName;
		$this->WebThumbnailPath = $this->WebThumbnailDirectory."/".$this->FileName;
		
		clearstatcache();
		
		$this->EnsureDirectories();
		
		if($this->TempPath && is_uploaded_file($this->TempPath))
		{
			if(Configuration::$PostedFile_UseCopy)
			{
				copy($this->TempPath, $this->PhysicalFilePath);
				copy($this->TempPath, $this->PhysicalThumbnailPath);
			}
			else
			{
				if(!move_uploaded_file($this->TempPath, $this->PhysicalStagingPath))
				{
					chmod($this->StagingDirectory, PostedFile::DIRECTORY_PERMISSIONS);
					chmod($this->ThumbnailDirectory, PostedFile::DIRECTORY_PERMISSIONS);
					chmod($this->FullsizeDirectory, PostedFile::DIRECTORY_PERMISSIONS);
					if(!move_uploaded_file($this->TempPath, $this->PhysicalStagingPath))
						echo "Setting permissions did not help -- Cannot move file from temp to Images directory\r\n";
					else
					{
						copy($this->PhysicalStagingPath, $this->PhysicalFilePath);
						copy($this->PhysicalStagingPath, $this->PhysicalThumbnailPath);
					}
				}
				else
				{
					copy($this->PhysicalStagingPath, $this->PhysicalFilePath);
					copy($this->PhysicalStagingPath, $this->PhysicalThumbnailPath);
				}
			}
		}
		$size = filesize($this->PhysicalFilePath);
		if ($size === $this->Size) 
		{
			$this->ScaleImage($this->PhysicalThumbnailPath, $this->FileExtension, Configuration::$MaxThumbImageWidth, Configuration::$MaxThumbImageHeight);
			$this->ScaleImage($this->PhysicalFilePath, $this->FileExtension, Configuration::$MaxImageWidth, Configuration::$MaxImageHeight);
		}
	}
	
	private function EnsureDirectories()
	{
		if(!is_dir($this->BaseDirectory))
		{
			$this->CreateDirectory($this->BaseDirectory);
		}
		if(!is_dir($this->StagingDirectory))
		{
			$this->CreateDirectory($this->StagingDirectory);
		}
		if(!is_dir($this->FullsizeDirectory))
		{
			$this->CreateDirectory($this->FullsizeDirectory);
		}
		if(!is_dir($this->ThumbnailDirectory))
		{
			$this->CreateDirectory($this->ThumbnailDirectory);
		}
	}
	
	private function CreateDirectory($path)
	{
		mkdir($path, PostedFile::DIRECTORY_PERMISSIONS, true);
		chmod($path, PostedFile::DIRECTORY_PERMISSIONS);
	}
	
	private function GetTemplatedName($name, $offset)
	{
		if(!preg_match(PostedFile::RE_FILENAMERENAMETEMPLATE, $name))
		{
			return str_replace($this->FileExtension, " (".$offset.")".$this->FileExtension, $name);
		}
		return preg_replace(PostedFile::RE_FILENAMERENAMETEMPLATE, "(".$offset.")", $name);
	}	
	
	public static function ScaleImage($filepath, $fileExtenstion, $maxX, $maxY)
	{
		list($iW, $iH) = getimagesize($filepath);
		
		if (!$iW || !$iH) 
			return false;
		
		$scale = min($maxX / $iW, $maxY / $iH);
		if ($scale >= 1) 
		{
			return true;
		}
		
		// Target W,H
		$tW = $iW * $scale;
		$tH = $iH * $scale;
		
		$targetImage = @imagecreatetruecolor($tW, $tH);
		$sourceImage = null;
		$writeMethod = null;
		switch ($fileExtenstion) 
		{
			case '.jpg':
			case '.jpeg':
				$sourceImage = @imagecreatefromjpeg($filepath);
				$writeMethod = 'imagejpeg';
				$quality = Configuration::$ImageQuality;
				break;
			case '.gif':
				@imagecolortransparent($targetImage, @imagecolorallocate($targetImage, 0, 0, 0));
				$sourceImage = @imagecreatefromgif($filepath);
				$writeMethod = 'imagegif';
				$quality = null;
				break;
			case '.png':
				@imagecolortransparent($targetImage, @imagecolorallocate($targetImage, 0, 0, 0));
				@imagealphablending($targetImage, false);
				@imagesavealpha($targetImage, true);
				$sourceImage = @imagecreatefrompng($filepath);
				$writeMethod = 'imagepng';
				$quality = Configuration::$ImageQuality / 10;
				break;
			default:
				return true;
		}
		
		$success = $sourceImage && @imagecopyresampled($targetImage, $sourceImage, 0, 0, 0, 0, $tW, $tH, $iW, $iH) && $writeMethod($targetImage, $filepath, $quality);
		
		@imagedestroy($sourceImage);
		@imagedestroy($targetImage);
		
		return $success;
	}
	
	public function ToStdFile()
	{
		$f = new stdClass();
		$f->name = $this->FileName;
		$f->size = $this->Size;
		$f->url = $this->FileName;
		//$f->thumbnail_url = $File->PhysicalThumbnailPath;
		//$f->fileid = $File->ID;
		//$f->sortorder = $File->Order;
		$f->delete_url = '?Action=Delete&FileID='.$this->FileName;
		$f->delete_type = 'DELETE';
		return $f;
	}
	
	public static function GetPostedFiles()
	{
		$postedFiles = array();
		foreach($_FILES as $type=>$file)
		{			
			if(!is_array($type))
			{
				array_push($postedFiles, PostedFile::ParseFile($file, 0));
				return $postedFiles;
			}
			foreach(array_keys($type) as $key)
			{
				array_push($postedFiles, PostedFile::ParseFile($file, $key));
			}
		}
		return $postedFiles;
	}
	
	private static function ParseFile($file, $key)
	{
		$X = new PostedFile();
		$X->FileName = $file['name'][$key];
		$X->TempPath = $file['tmp_name'][$key];
		$X->FileType = $file['type'][$key];
		$X->Size = $file['size'][$key];
		$e = $file['error'][$key];
		$X->HasError = $e > 0;
		$X->FileExtension = ".".strtolower(substr(strrchr($X->FileName, '.'), 1));
		if($X->HasError)
		{
			/*
			UPLOAD_ERR_OK: 0
			UPLOAD_ERR_INI_SIZE: 1
			UPLOAD_ERR_FORM_SIZE: 2
			UPLOAD_ERR_NO_TMP_DIR: 6
			UPLOAD_ERR_CANT_WRITE: 7
			UPLOAD_ERR_EXTENSION: 8
			UPLOAD_ERR_PARTIAL: 3
			*/
			switch($e)
			{
				case 1:
					$X->ErrorMessage = "I'm not sure what this error is exactly but it has something to do with size (INI)";
					break;	
				case 2:
					$X->ErrorMessage = "I'm not sure what this error is exactly but it has something to do with size (FORM)";
					break;
				case 3:
					$X->ErrorMessage = "Partial file upload. Not all contents made it from your computer to the server.";
					break;
				case 6:
					$X->ErrorMessage = "No temporary directory exists to host the file during upload.";
					break;
				case 7:
					$X->ErrorMessage = "File permissions were denied when writing the file to the servers temp directory";
					break;
				case 8:
					$X->ErrorMessage = "Error uploading file due to an extension";
					break;
			}
		}
		return $X;
	}
	
	const RE_FILENAMERENAMETEMPLATE = "/\([0-9]+\)/";
	const DIRECTORY_PERMISSIONS = 0777;
	
}

?>

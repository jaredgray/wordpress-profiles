<?php
/*
 * jQuery File Upload Plugin PHP Class 5.11.2
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */
require_once(dirname(dirname(dirname(__FILE__)))."/Core/Application.php");
require_once(dirname(dirname(dirname(__FILE__)))."/Core/Server.php");
require_once(dirname(dirname(dirname(__FILE__)))."/Core/HttpContext.php");
require_once(dirname(dirname(dirname(__FILE__)))."/Data/UnitOfWork.php");
require_once(dirname(dirname(dirname(__FILE__)))."/Data/ProfileImageDataMapper.php");
require_once(dirname(dirname(dirname(__FILE__)))."/Model/ProfileImage.php");

class UploadHandler
{
	protected $options;
	
	private $Context = null;
	
	private $ImageMapper = null;
	
	private function GetMapper()
	{
		$Images = null;
		$uow = UnitOfWork::Instance();		
		return $uow->GetFinder("IProfileImageFinder");
	}

	function __construct($options=null) 
	{
		Application::Initialize();
		$this->Context = HttpContext::Current();
		$this->ImageMapper = $this->GetMapper();
		$this->options = array(
			'script_url' => $this->GetFullUrl().'/Index.php',
			'upload_dir' => dirname($_SERVER['SCRIPT_FILENAME']).'/files/',
			'upload_url' => '',
			'param_name' => 'files',
			'delete_type' => 'DELETE',
			'max_file_size' => null,
			'min_file_size' => 1,
			'accept_file_types' => '/.+$/i',
			'max_number_of_files' => null,
			'max_width' => null,
			'max_height' => null,
			'min_width' => 1,
			'min_height' => 1
				);
		if ($options) {
			$this->options = array_replace_recursive($this->options, $options);
		}
	}
	
	public function ProcessRequest()
	{
		switch ($this->Context->Request->Method) 
		{
			case 'OPTIONS':
				break;
			case 'HEAD':
			case 'GET':
				$this->Get();
				break;
			case 'POST':
				if ($this->Context->Request->QueryString->GetValue("Action") == "Delete")
					$this->Delete();
				else
					$this->Post();
				break;
			case 'DELETE':
				$this->Delete();
				break;
			default:
				header('HTTP/1.1 405 Method Not Allowed');
		}
	}

	protected function GetFullUrl() {
		$https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
		return
		($https ? 'https://' : 'http://').
			(!empty($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'].'@' : '').
			(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ($_SERVER['SERVER_NAME'].
					($https && $_SERVER['SERVER_PORT'] === 443 ||
						$_SERVER['SERVER_PORT'] === 80 ? '' : ':'.$_SERVER['SERVER_PORT']))).
			substr($_SERVER['SCRIPT_NAME'],0, strrpos($_SERVER['SCRIPT_NAME'], '/'));
	}
	
	private function MapFileObjects($Files)
	{
		$Images = array();
		if(!$Files)
			return $Images;
		foreach($Files as $file)
		{
			array_push($Images, $this->MapFileObject($file));
		}
		return $Images;
	}

	protected function ScaleImage($PostedFile) {
		$file_path = $this->options['upload_dir'].$file_name;
		$new_file_path = $options['upload_dir'].$file_name;
		list($img_width, $img_height) = @getimagesize($file_path);
		if (!$img_width || !$img_height) {
			return false;
		}
		$scale = min(
			$options['max_width'] / $img_width,
			$options['max_height'] / $img_height
			);
		if ($scale >= 1) {
			if ($file_path !== $new_file_path) {
				return copy($file_path, $new_file_path);
			}
			return true;
		}
		$new_width = $img_width * $scale;
		$new_height = $img_height * $scale;
		$new_img = @imagecreatetruecolor($new_width, $new_height);
		switch (strtolower(substr(strrchr($file_name, '.'), 1))) {
			case 'jpg':
			case 'jpeg':
				$src_img = @imagecreatefromjpeg($file_path);
				$write_image = 'imagejpeg';
				$image_quality = isset($options['jpeg_quality']) ?
					$options['jpeg_quality'] : 75;
				break;
			case 'gif':
				@imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
				$src_img = @imagecreatefromgif($file_path);
				$write_image = 'imagegif';
				$image_quality = null;
				break;
			case 'png':
				@imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
				@imagealphablending($new_img, false);
				@imagesavealpha($new_img, true);
				$src_img = @imagecreatefrompng($file_path);
				$write_image = 'imagepng';
				$image_quality = isset($options['png_quality']) ?
					$options['png_quality'] : 9;
				break;
			default:
				$src_img = null;
		}
		$success = $src_img && @imagecopyresampled(
			$new_img,
			$src_img,
			0, 0, 0, 0,
			$new_width,
			$new_height,
			$img_width,
			$img_height
			) && $write_image($new_img, $new_file_path, $image_quality);
		// Free up memory (imagedestroy does not delete files):
		@imagedestroy($src_img);
		@imagedestroy($new_img);
		return $success;
	}
	
	private function MapFileObject($File, $mapfilepath = false)
	{
		$f = new stdClass();
		$f->name = $File->ImageName;
		$f->size = $File->ImageSize;
		$f->url = $mapfilepath? Server::MapPath($File->ImagePath) : $File->ImagePath;
		$f->thumbnail_url = $mapfilepath? Server::MapPath($File->ThumbnailPath) : $File->ThumbnailPath;
		$f->fileid = $File->ID;
		$f->sortorder = $File->Order;
		$f->delete_url = $this->options['script_url'].'?Action=Delete&FileID='.$File->ID;
		$f->delete_type = $this->options['delete_type'];
		return $f;
	}
	
	public function Get() 
	{
		if(null != $this->ImageMapper)
		{
			$Images = null;
			$Images = $this->ImageMapper->GetProfileImagesByProfileID($this->Context->Request->QueryString->GetValue("ProfileID"), $this->Context->Request->QueryString->GetValue("Type"));
			$info = $this->MapFileObjects($Images);
			header('Content-type: application/json');
			echo json_encode($info);
			return;
		}
		echo "IProfileImageFinder failed to load";
	}

	public function Post() 
	{
		$images = array();
		foreach($this->Context->Request->PostedFiles as $f)
		{
			$f->Save($this->Context->Request->QueryString->GetValue("ProfileID"));
			// persist to db
			array_push($images, $this->MapFileObject($this->InsertFile($f), true));
		}
		echo json_encode($images);
	}

	public function Delete() 
	{
		$victimid = $this->Context->Request->QueryString->GetValue("FileID");		
		$mapper = $this->GetMapper();
		$victim = $mapper->GetProfileImageByID($victimid);
		$mapper->DeleteProfileImageByID($victimid);
		if (is_file($victim->PhysicalImagePath)) 
		{
			unlink($victim->PhysicalImagePath);
		}
		if (is_file($victim->PhysicalThumbnailPath)) 
		{
			unlink($victim->PhysicalThumbnailPath);
		}		
		header('Content-type: application/json');
		echo json_encode(true);		
	}
	
	public function InsertFile($postedFile)
	{
		$mapper = $this->GetMapper();
		$ProfileImage = new ProfileImage($postedFile);
		$imagetype = $this->Context->Request->QueryString->GetValue("FileType");
		if($imagetype)
			$ProfileImage->ImageType = $imagetype;
		$mapper->Save($ProfileImage);
		return $ProfileImage;
	}	
}

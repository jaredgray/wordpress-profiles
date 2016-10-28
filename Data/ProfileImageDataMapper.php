<?php
require_once(dirname(__FILE__)."/DataMapper.php");
require_once(dirname(dirname(__FILE__))."/Core/Server.php");


class ProfileImageDataMapper extends DataMapper
{
	public function __construct($DataSource)
    {
		parent::__construct($DataSource);
	}
	
	public function GetProfileImagesByProfileID($profileid, $type = null)
	{
		$query = ProfileImageDataMapper::PROFILE_QUERY;
		$query .= " WHERE ProfileID = ".$profileid;
		
		if(null != $type)
			$query .= " AND ImageType = ".$type;
		
		return $this->ProcessQuery($query);
	} 
    
	public function GetProfileImageByID($profileimageid = -1)
    {
		$query = ProfileImageDataMapper::PROFILE_QUERY;
		$query .= " WHERE ProfileImageID = ".$profileimageid;
		$items = $this->ProcessQuery($query);
		return $items[0];
	}	
	
	public function Save($model)
	{
		if(0 == $model->ID)
		{
			$newid = $this->InsertProfileImage($model);
			$model->ID = $newid;
		}
		else
			$this->UpdateProfileImage($model);
	}
	
	public function DeleteProfileImage($model)
	{
		$this->DeleteProfileImageByID($model->ID);
	}
	
	public function DeleteProfileImageByID($id)
	{
		$query = "DELETE FROM ProfileImage WHERE ProfileImageID = ".$id;
		parent::ExecuteQuery($query);
	}
	
	private function UpdateProfileImage($model)
	{
		$query = "UPDATE ProfileImage
		SET `ProfileID` = ".$model->ProfileID."
			, `ImagePath` = ".$this->QuoteValue($model->ImagePath)."
			, `ThumbnailPath` = ".$this->QuoteValue($model->ThumbnailPath)."
			, `ImageType` = ".$model->ImageType."
			, `ImageSize` = ".$model->ImageSize."
			, `Order` = ".$model->Order."
		WHERE ProfileImageID = ".$model->ID;
		parent::ExecuteQuery($query);
	}
	
	private function InsertProfileImage($model)
	{
		$query = "INSERT INTO ProfileImage
		(
			`ProfileID`
			, `ImagePath`
			, `ThumbnailPath`
			, `ImageType`
			, `ImageSize`
			, `Order`
		)
		VALUES(".$model->ProfileID."
			, ".$this->QuoteValue($model->ImagePath)."
			, ".$this->QuoteValue($model->ThumbnailPath)."
			, ".$model->ImageType."
			, ".$model->ImageSize."
			, ".$model->Order.")";
		return parent::ExecuteQuery($query, true);
	}
	
	private function QuoteValue($value)
	{
		if(null == $value)
			return "NULL";
		return "'".$value."'";	
	}
	
	private function ProcessQuery($query)
	{		
		$arr = parent::ExecuteQuery($query);
		
		if(null != $arr)
		{
			$datamap = new ProfileImageDataBinder($arr);
			return $datamap->ToList();	
		}
	}
	
	const PROFILE_QUERY = "SELECT * FROM ProfileImage";
}

class ProfileImageDataBinder
{
	public function __construct($sourceData)
	{
		require_once(dirname(dirname(__FILE__))."/Model/ProfileImage.php");
		$this->SourceData = $sourceData;
	}
	private $SourceData = null;
	
	public function ToList()
	{
		$itms = array();
		foreach($this->SourceData as $row)
		{
			$model = new ProfileImage();
			$model->ID = $row["ProfileImageID"];
			$model->ProfileID = $row["ProfileID"];
			$model->ImageType = $row["ImageType"];
			$model->ImagePath = Server::MapPath($row["ImagePath"]);
			$model->ThumbnailPath = Server::MapPath($row["ThumbnailPath"]);
			$model->PhysicalImagePath = Server::MapPath($row["ImagePath"], true);
			$model->PhysicalThumbnailPath = Server::MapPath($row["ThumbnailPath"], true);
			$model->ImageName = ProfileImage::GetFileName($model->ImagePath);
			$model->ImageSize = $row["ImageSize"];
			$model->Order = $row["Order"];
			array_push($itms, $model);
		}
		return $itms;
	}
}
?>

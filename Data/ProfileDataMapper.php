<?php
require_once(dirname(__FILE__)."/DataMapper.php");

/**
 * Hi, I'm ProfileDataMapper
 * 
 * I was born 09/11/2012. I'm told I was created to retrieve data from a database. My dad DataMapper helps me a little. I made a new 
 * friend ProfileDataBinder that does something with the data I get until it gets sent to whoever asked me for it :\ 
 *
 */
class ProfileDataMapper extends DataMapper
{
	public function __construct($DataSource)
    {
		parent::__construct($DataSource);
    }
    
    public function GetProfiles($id = null, $populateImages = true, $isActive = true)
    {
		$query = ProfileDataMapper::PROFILE_QUERY;
	
	$active = ($isActive) ? "1" : "0";
	
        if(null != $id)
            $query = $query." WHERE ProfileID = ".$id;
	else
            $query = $query." WHERE IsActive = ".$active ;
		
		$arr = parent::ExecuteQuery($query);
		
		if(null != $arr)
		{
			$datamap = new ProfileDataBinder($arr);
			return $datamap->ToList($populateImages);	
		}
		if(count($arr) <= 0)
		{
			// install current user...	
		}
	}
	
	public function GetProfileByID($id)
	{
		$profiles = $this->GetProfiles($id);
		return $profiles[0];
	}
	
	public function GetProfileByUserID($id, $populateImages = true)
	{
		$query = ProfileDataMapper::PROFILE_QUERY;
		
		if(null != $id)
			$query = $query." WHERE UserID = ".$id;
		
		$arr = parent::ExecuteQuery($query);
		
		if(null != $arr)
		{
			$datamap = new ProfileDataBinder($arr);
			$users = $datamap->ToList($populateImages);
			if(count($users) > 0)
				return $users[0];
		}
		return null;
	}
	
	public function Save($model)
	{
		if(0 == $model->ID)
			$this->InsertProfile($model);
		else
			$this->UpdateProfile($model);
	}
	
	public function DeleteProfile($model)
	{
		$query = "DELETE FROM Profile WHERE ProfileID = ".$model->ID;
		parent::ExecuteQuery($query);
	}
	
	private function UpdateProfile($model)
	{
		$query = "UPDATE Profile
		SET `Biography` = ".$this->QuoteValue($model->Biography)."
			, `Blurb` = ".$this->QuoteValue($model->Blurb)."
			, `DisplayName` = ".$this->QuoteValue($model->DisplayName)."
			, `DisplayOrder` = ".$model->DisplayOrder."
			, `IsActive` = ".$model->IsActive."
			, `UserID` = ".$model->UserID."
		WHERE ProfileID = ".$model->ID;
		parent::ExecuteQuery($query);
	}
	
	private function InsertProfile($model)
	{
		$query = "INSERT INTO Profile
		(
			`Biography`,
			`Blurb`,
			`DisplayName`,
			`DisplayOrder`,
			`IsActive`,
			`UserID`
		)
		VALUES(".$this->QuoteValue($model->Biography)."
			, ".$this->QuoteValue($model->Blurb)."
			, ".$this->QuoteValue($model->DisplayName)."
			, ".$model->DisplayOrder."
			, ".$model->IsActive."
			, ".$model->UserID.")";
		parent::ExecuteQuery($query);
	}
	
	private function QuoteValue($value)
	{
		if(null == $value)
			return "NULL";
		return "'".$value."'";	
	}
	
	const PROFILE_QUERY = "SELECT * FROM Profile";
}

/**
 * Hi, I'm ProfileDataBinder
 * 
 * I was born 09/11/2012. I'm told I was created to map data from arrays that were generated form the ProfileImage table to
 * ProfileImage poco objects. I'm still learning what all this means, so I might make a mistake once and a while :/ 
 *
 */
class ProfileDataBinder
{
	public function __construct($sourceData)
	{
		require_once(dirname(__FILE__)."/ProfileImageDataMapper.php");
		require_once(dirname(dirname(__FILE__))."/Model/Profile.php");
		require_once(dirname(dirname(__FILE__))."/Model/ProfileImage.php");
		$this->SourceData = $sourceData;
	}
	private $SourceData = null;
	
	public function ToList($populateImages = true)
	{
		$itms = array();
		foreach($this->SourceData as $row)
		{
			$profile = new Profile();
			$profile->ID = $row["ProfileID"];
			$profile->DisplayName = $row["DisplayName"];
			$profile->IsActive = (true == $row["IsActive"]);
			$profile->DisplayOrder = $row["DisplayOrder"];
			$profile->Blurb = $row["Blurb"];
			$profile->Biography = $row["Biography"];
			$profile->UserID = $row["UserID"];
			if($populateImages)
			{
				$this->PopulateImages($profile);
			}
			array_push($itms, $profile);
		}
		return $itms;
	}
	
	private function PopulateImages($profile)
	{
		$pdm = UnitOfWork::Instance()->GetFinder("IProfileImageFinder");
		$images = $pdm->GetProfileImagesByProfileID($profile->ID);
		
		$profile->ProfileImages = array();
		$profile->PortfolioImages = array();
		if(null == $images)
			return;
		foreach($images as $image)
		{
			if(ProfileImage::IMAGE_TYPE_PROFILE == $image->ImageType)
			{				
				array_push($profile->ProfileImages, $image);
			}
			else if(ProfileImage::IMAGE_TYPE_PORTFOLIO == $image->ImageType)
			{
				array_push($profile->PortfolioImages, $image);
			}	
		}
		if(count($profile->ProfileImages) > 0)
		{			
			$profile->ProfileImage = Server::MapPath($profile->ProfileImages[0]->ImagePath);
			$profile->ProfileThumbnail = Server::MapPath($profile->ProfileImages[0]->ThumbnailPath);	
		}
	}
}
?>

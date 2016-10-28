<?php

class SR
{
	private static function Init()
	{
		SR::$Properties = array();
		SR::$Properties["Image"] = "Image";
		SR::$Properties["DisplayName"] = "Display Name";
		SR::$Properties["Blurb"] = "Blurb";
		SR::$Properties["PortfolioImages"] = "Portfolio Images";
		SR::$Properties["AddFiles"] = "Add files";
		SR::$Properties["StartUpload"] = "Start upload";
		SR::$Properties["CancelUpload"] = "Cancel upload";
		SR::$Properties["Delete"] = "Delete";
		SR::$Properties["ProfileImage_Title"] = "Profile Image";
		SR::$Properties["ProfileImage_Description"] = "Upload your profile image here. Note: you are allowed to upload more than one image to this section but by default only one will be used";
		SR::$Properties["PortfolioImage_Title"] = "Portfolio Images";
		SR::$Properties["PortfolioImage_Description"] = "Upload your portfolio images here.";
		SR::$Properties["Portfolio_Intro"] = "<span class=\"Title\">Profiles</span> - This screen displays the portfolios available for display on your site. To have other users appear in this section, go to the native \"Users\" section of the admin and add users as Administrators or Contributers.";
		SR::$Properties["PortfolioEdit_Intro"] = "<span class=\"Title\">Edit Profile</span> - Use this screen to edit attributes of this profile. Add Profile Image that will be displayed on the site, change content and add/remove portfolio images.";
		SR::$isInit = true;
	}
	private static $isInit = false;
	
	public static function GetString($key)
	{
		if(!SR::$isInit)
			SR::Init();
		if(!SR::$Properties[$key])
		{
			echo " class SR does not contain the key: ".$key." ";
			return "";
		}
		return SR::$Properties[$key];
	}	
	
	public static $Properties = null;
}

?>

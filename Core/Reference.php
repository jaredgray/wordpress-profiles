<?php
class Reference
{
	static public function LoadAll($RootDirectory)
    {
        $CurrentDirectory = dirname(__FILE__);
		require_once($CurrentDirectory."/Application.php");
		require_once($CurrentDirectory."/Server.php");
        require_once($CurrentDirectory."/ILoadable.php");
		require_once($RootDirectory."/Controls/Page.php");
		require_once($CurrentDirectory."/User.php");
		require_once($RootDirectory."/Adaptors/Factory.php");
		require_once($RootDirectory."/Admin/YourProfile.php");
		require_once($RootDirectory."/Data/UnitOfWork.php");
		require_once($RootDirectory."/Model/Profile.php");
		require_once($RootDirectory."/Model/ProfileImage.php");
    }
}
?>

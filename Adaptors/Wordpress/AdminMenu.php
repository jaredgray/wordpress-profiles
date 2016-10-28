<?php
class AdminMenu implements ILoadable
{
    private $RequiredRole = "";
    private $UserIsAdmin = false;
    private $UserIsMember = false;
    private $CurrentUser = null;
  
    private $RootDirectory = "";
    private $RelativeUrl = "";
    private $ProfileManagerUrl = "";
    private $ProfileUrl = "";

    public function __construct($RootDir, $Rel, $CurrentUser)
    {
        add_action('init', array($this,'Initialize'));
        $this->CurrentUser = $CurrentUser;
        $this->RootDirectory = $RootDir;
        $this->RelativeUrl = $Rel;
        $this->ProfileManagerUrl = 'ProfileManager';
        $this->ProfileUrl = 'YourProfile';
    }
    
    public function Initialize()
    {    
    }
    
    public function Load()
    {
        if($this->CurrentUser->IsAdmin)
        {
            $this->LoadAdminMenu();
        }
        else if($this->CurrentUser->IsMember)
        {
            $this->LoadMemberMenu();
        }
    }
    
    private function LoadAdminMenu()
    {
		require_once($this->RootDirectory."/Controls/Page.php");	
        include_once($this->RootDirectory."/Admin/ProfileManager.php");
		include_once($this->RootDirectory."/Admin/EditProfile.php");	
		add_menu_page('Profile Management', 'Salon Profiles', 'edit_users', $this->ProfileManagerUrl, array('ProfileManager', 'Create'), Server::MapPath("~/Images/Application/plugin_16x16.png"), 71); 		
		add_submenu_page($this->ProfileManagerUrl, 'My Salon Profile Page', 'My Profile', 'edit_users', $this->ProfileUrl, array('YourProfile', 'Create'));   
		
		add_submenu_page( $this->ProfileManagerUrl, null, null, 'edit_users', "EditProfile", array('EditProfile', 'Create') );
    }
    
    private function LoadMemberMenu()
    {
		include_once($this->RootDirectory."/Admin/EditProfile.php");
		add_menu_page('My Salon Profile', 'Salon Profile', 'level_1', $this->ProfileManagerUrl, array('YourProfile', 'Create'));
    }
}
?>

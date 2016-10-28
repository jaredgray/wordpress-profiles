<?php

require_once("AdminMenu.php");

/**
 * class WordPressAdaptor
 *
 * Description for class WordPressAdaptor
 *
 * @author:
*/
class WordPressAdaptor 
{
	public $CurrentUser = null;
	public $Menu = null;
	public $Platform = "wordpress";
	public $Version = null;
	public $RootDirectory;
	public $Contributers = null;
	public $Administrators = null;

	private $ProfileFinder;
	
	function __construct($version, $rootdir)
	{
		register_activation_hook(__FILE__, 'ProfileInstall');
		
		$uow = UnitOfWork::Instance();		
		$this->ProfileFinder = $uow->GetFinder("IProfileFinder");
		
		$this->RootDirectory = $rootdir;		
		$this->Version = $version;
		$this->CurrentUser = new User();
		add_action('init', array($this,'Initialize'));  
		$this->Menu = new AdminMenu($this->RootDirectory, "../", $this->CurrentUser);
		add_action('admin_menu', array($this->Menu,'Load'));
		add_shortcode( 'profiles', array($this,'LoadFrontEndProfileDisplay') );
	}
	
	public function Initialize()
	{
		$User = wp_get_current_user();
		$this->PopulateUser($this->CurrentUser, $User);
		Application::$CurrentProfile = $this->ProfileFinder->GetProfileByUserID($this->CurrentUser->UserID);
		
		$this->LoadUsers();
		
	}
	
	public function LoadUsers()
	{
		$natives = get_users("role=administrator");
		$this->Administrators = array();
		foreach($natives as $nu)
		{
			$model = $this->ProfileFinder->GetProfileByUserID($nu->ID);
			$user = $this->LoadUser(get_user_by("id", $nu->ID));
			array_push($this->Administrators, $user);
			//if(null == $model)
			//{
			//	$model = new Profile();
			//	$model->UserID = $user->UserID;
			//	$model->Biography = "";
			//	$model->Blurb = "";
			//	$model->DisplayName = $user->FirstName;
			//	$this->ProfileFinder->Save($model);
			//}
		}
		$natives = get_users("role=contributor");
		$this->Contributers = array();
		foreach($natives as $nu)
		{
			$model = $this->ProfileFinder->GetProfileByUserID($nu->ID);
			$user = $this->LoadUser(get_user_by("id", $nu->ID));
			array_push($this->Contributers, $user);
			//if(null == $model)
			//{
			//	$model = new Profile();
			//	$model->UserID = $user->UserID;
			//	$model->Biography = "";
			//	$model->Blurb = "";
			//	$model->DisplayName = $user->FirstName;
			//	$this->ProfileFinder->Save($model);
			//}
		}
	}
	
	private function LoadUser($NativeUser)
	{
		$User = new User();
		$this->PopulateUser($User, $NativeUser);
		return $User;
	}
	
	private function PopulateUser($User, $NativeUser)
	{
		$User->UserID = $NativeUser->ID;
		$User->IsAdmin = in_array('administrator', $NativeUser->roles);
		$User->IsMember = in_array('contributor', $NativeUser->roles);
		$User->FirstName = $NativeUser->first_name;
		$User->LastName = $NativeUser->last_name;
		$User->Capabilities = $NativeUser->allcaps;
	}	
	
	public function LoadFrontEndProfileDisplay()
	{
		require_once($this->RootDirectory."/SiteContent/ProfileDisplay.php");
		return ProfileDisplay::Create(true);
	}
	

	/**
	 * WordPressAdaptor constructor
	 *
	 * @param 
	 */
	function WordPressAdaptor()
	{

	}
	
	public static function ProfileInstall()
	{
		$sqlPath = dirname(dirname(__FILE__))."/Data/".Configuration::$DBVersion;
		$profileTable = $sqlPath."/CreateT Profile.sql";
		$profileImageTable = $sqlPath."/CreateT ProfileImage.sql";
		echo 'Loading SQL';
		$profileSQL = file_get_contents($profileTable);
		$profileImageSQL = file_get_contents($profileImageTable);
		
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		echo 'Adding Sql Statements';
		dbDelta($profileSQL);
		dbDelta($profileImageSQL);
		
		add_option("ProfileDBVersion", Configuration::$DBVersion);
		
	}
}

?>

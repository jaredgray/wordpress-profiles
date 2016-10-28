<?php

class EditProfile extends Page
{
	public function __construct()
	{
		require_once(Application::$RootDirectory."/Data/SR.php");
		require_once(Application::$RootDirectory."/Data/UnitOfWork.php");
		require_once(Application::$RootDirectory."/Data/ProfileImageDataMapper.php");
		require_once(Application::$RootDirectory."/UserControls/ProfileEditor.php");
		require_once(Application::$RootDirectory."/Controls/FileUpload.php");
		parent::__construct();
		$this->ProfileID = $this->Context->Request->QueryString->GetValue("ID");
		$this->LoadProfile();
		$this->RegisterResource( 'ProfileAdmin.css', "stylesheet", Server::MapPath('~/Admin/Theme/ProfileAdmin.css'), null, "before");
	}
	
	public static function Create()
	{
		$p = new EditProfile();	
		$p->Title = "Profile Editor";
		$p->Initialize();
	}
	
	public $ProfileID = null;
	public $Profile = null;
	
	protected function CreateControls()
	{
		if(null == $this->Profile)
		{
			echo " Null Profile (EditProfile.php->CreateControls) ";
			return;
		}
		$editor = new ProfileEditor($this->Profile);
		$editor->LoadChildControls();
		$this->AddControl($editor);
		
		$template = dirname(__FILE__)."/Templates/FileUpload.part.xml";
		$fu = new FileUpload($template);
		$fu->DisplayName = SR::GetString("ProfileImage_Title");
		$fu->Description = SR::GetString("ProfileImage_Description");
		$fu->FileType = 1;
		$fu->HttpHandler = Server::MapPath("~/FileUpload/Core/Index.php");
		$fu->ProfileID = $this->ProfileID;
		$fu->Name = "Upload1";
		$this->AddControl($fu);
		
		$fu = new FileUpload($template);
		$fu->DisplayName = SR::GetString("PortfolioImage_Title");
		$fu->Description = SR::GetString("PortfolioImage_Description");
		$fu->FileType = 2;
		$fu->HttpHandler = Server::MapPath("~/FileUpload/Core/Index.php");
		$fu->ProfileID = $this->ProfileID;
		$fu->Name = "Upload2";
		$this->AddControl($fu);
	}
	
	public function Render()
	{
		parent::Render();
	}
	
	private function LoadProfile()
	{
		if(null == $this->ProfileID)
		{
			echo " Null ProfileID (EditProfile.php->LoadProfile) ";
			return;
		}
		$uow = UnitOfWork::Instance();		
		$mapper = $uow->GetFinder("IProfileFinder");
		if(null == $mapper)
			echo "Mapper is null";
		else
		{
			$this->Profile = $mapper->GetProfileByID($this->ProfileID);
		}
	}
	
}
?>

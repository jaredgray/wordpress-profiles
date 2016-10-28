<?php

class ProfileManager extends Page
{
    public function __construct()
    {
		require_once(dirname(dirname(__FILE__))."/Data/UnitOfWork.php");
		require_once(dirname(dirname(__FILE__))."/Controls/GridControl.php");
		parent::__construct();
		$this->ProfileGridTempateUrl = dirname(__FILE__)."/Templates/ProfileGrid.part.xml";
		$this->RegisterResource( 'ProfileAdmin.css', "stylesheet", Server::MapPath('~/Admin/Theme/ProfileAdmin.css'), null, "before");
    }
	
	public static function Create()
	{
		$pm = new ProfileManager();
		$pm->Title = "Profile Manager";
		$pm->Initialize();	
	}
	
	private $ProfileGridTempateUrl = "";
	private $Profiles = null;
    
	protected function CreateControls()
    {
		$this->LoadProfiles();
		if(null != $this->Profiles)
		{
			$gc = new GridControl($this->ProfileGridTempateUrl, $this->Profiles);
			parent::AddControl($gc);
			//array_push($this->Controls, $gc);
		}
		else
		{
			echo " Profiles did not load ";	
		}
		
    }
	
	private function LoadProfiles()
	{
		$uow = UnitOfWork::Instance();		
		$mapper = $uow->GetFinder("IProfileFinder");
		if(null == $mapper)
			echo "Mapper is null";
		else
		{
			$this->Profiles = $mapper->GetProfiles(null);
		}
	}
	
}
?>

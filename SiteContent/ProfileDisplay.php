<?php
require_once(dirname(dirname(__FILE__))."/Controls/Page.php");

/**
 * class ProfileDisplay
 *
 * Description for class ProfileDisplay
 *
 * @author:
*/
class ProfileDisplay extends Page
{
	public function __construct()
	{
		parent::__construct();
		require_once(dirname(dirname(__FILE__))."/Data/UnitOfWork.php");
		require_once(dirname(dirname(__FILE__))."/Controls/GridControl.php");
		$this->ProfileID = HttpContext::Current()->Request->QueryString->GetValue("ID");
		if(null != $this->ProfileID)// && isint(profileid)
		{
			$this->ProfileGridTempateUrl = dirname(__FILE__)."/Templates/ProfileGridDetail.part.xml";
		}
		else
			$this->ProfileGridTempateUrl = dirname(__FILE__)."/Templates/ProfileGrid.part.xml";
	}

	/**
	 * ProfileDisplay constructor
	 *
	 * @param 
	 */
	function ProfileDisplay() 
	{

	}
	
	public function Create($bufferoutput = false)
	{
		if($bufferoutput)
			ob_start();
		$pm = new ProfileDisplay();
		$pm->Title = "";
		$pm->Initialize();
		if($bufferoutput)
			return ob_get_clean();
	}
	
	private $ProfileGridTempateUrl = "";
	private $Profiles = null;
	
	private $ProfileID = null;
	
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
		if(null != $this->ProfileID && null != $this->Profiles[0]->PortfolioImages && (count($this->Profiles[0]->PortfolioImages) > 1))
		{
			$gc = new GridControl(dirname(__FILE__)."/Templates/ProfileGridDetail.ImageViewerTitle.xml", array());
			parent::AddControl($gc);
			
			require_once(dirname(dirname(__FILE__))."/Controls/ImageViewer.php");
			$iv = new ImageViewer();
			$iv->Width = 960;
			$iv->Height = 450;
			$iv->CssClass = "ImageGallery GalleryShell";
			$iv->Name = "ProfileViewer";
			$iv->ID = "ProfileViewer";
			$iv->IImageDataSource = $this->Profiles[0]->PortfolioImages;
			parent::AddControl($iv);
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
			if(null == $this->ProfileID)
				$this->Profiles = $mapper->GetProfiles(null);
			else
			{
				$this->Profiles = array();
				$p = $mapper->GetProfileByID($this->ProfileID);
				array_push($this->Profiles, $p);
			}
		}
	}
	
	protected function BeginRender()
	{
		parent::BeginRender();
	}
}

?>

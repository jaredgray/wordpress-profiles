<?php

require_once("Control.php");
require_once("HiddenField.php");
require_once("Button.php");
require_once(dirname(dirname(__FILE__))."/Core/HttpContext.php");
require_once(dirname(dirname(__FILE__))."/Core/StateBag.php");

abstract class Page extends Control
{
	public function __construct()
	{
		parent::__construct();
		Page::$Current = $this;
		$this->ID = "ctrl_fch_pluginpage";
		$this->Name = "fch_pluginpage";
		$this->Context = HttpContext::Current();
		$this->RegisteredScriptsBeforeRender = new StateBag();
		$this->RegisteredScriptsAfterRender = new StateBag();
		$this->RegisteredCssBeforeRender = new StateBag();
		$this->RegisteredCssAfterRender = new StateBag();
	}
	
	public $Context = null;
	
	public $Title = "";
	
	public $IsPostback = false;
	
	public static $Current;
	
	private static $RegisteredObjects = array();
	
	private $RegisteredScriptsBeforeRender;
	private $RegisteredScriptsAfterRender;
	
	private $RegisteredCssBeforeRender;
	private $RegisteredCssAfterRender;
	
	public function Initialize()
	{
		$this->ViewState = new HiddenField();
		$this->ViewState->ID = "ctrl___ViewState";
		$this->ViewState->Name = "__ViewState";
		parent::AddControl($this->ViewState);
		$this->CreateControls();
		$this->LoadControls();
		$this->LoadViewState();
		$this->Page_Load();
		$this->DataBind();
		$this->Render();
	}
	
	private $ViewState;
	private $btn_TestClick;
	
	public function btn_TestClick_Click($sender, $eventArgs)
	{
		echo " BUTTEN TESTCLICK EVENT CALLED!!!!!!  ";
	}
	
	private function LoadControls()
	{
		$this->Load();
	}
	
	private function LoadViewState()
	{
		$bag = null;
		if(null != $this->ViewState->Value && $this->ViewState->Value != "")
			$bag = new StateBag($this->ViewState->Value);
		else
			$bag = new StateBag("IsPostback=0");
		$postbackCount = $bag->GetValue("IsPostback");
		$postbackCount++;
		$bag->SetValue("IsPostback", $postbackCount);
		if(null != $bag->GetValue("Click"))
		{			
			$id = $bag->GetValue("Click");
			$ctrl = parent::FindControl($id);
			
			$bag->Remove("Click");
			if(null != $ctrl)
			{
				$ctrl->OnClick($this);
			}
		}
		$this->ViewState->Value = $bag->ToString();
	}
	
	protected function Page_Load()
	{
		if($this->IsPostback)
			echo " page was posted back ";
	}
	
	public function DataBind()
	{
		foreach($this->Controls as $Control)
		{
			$Control->DataBind();	
		}	
	}	
	
	public function Render()
	{
		$this->BeginRender();
		
		foreach($this->Controls as $Control)
		{
			$Control->Render();	
		}
		
		$this->EndRender();
	}
	
	protected function BeginRender()
	{
		$url = $this->Context->RequestUri();
		//$this->Context->Request->QueryString->ToString()
		$this->RegisteredScriptsBeforeRender->CommitDependencies();
		$this->RegisteredCssBeforeRender->CommitDependencies();
		$this->RegisteredScriptsAfterRender->CommitDependencies();
		$this->RegisteredCssAfterRender->CommitDependencies();
		print $this->RegisteredCssBeforeRender->ToString(false);
		print $this->RegisteredScriptsBeforeRender->ToString(false);
		
		print "<div class=\"wrap\">\r\n<form id=\"fch_profileplugin\" method=\"post\" action=\"".$url."\">";
	}
	
	protected function EndRender()
	{
		print "</form>\r\n</div>";
		print $this->RegisteredCssAfterRender->ToString(false);
		print $this->RegisteredScriptsAfterRender->ToString(false);
	}
	
	protected function RenderBeginTag()
	{
	}
	protected function RenderClosingBeginTag()
	{
	}
	protected function RenderEndTag()
	{
	}
	
	protected function CreateControls()
	{
		echo "A Page Control must override the CreateControls method";	
	}
	
	public function RegisterResource($key, $resourcetype, $resource, $comment = null, $placement = "after")
	{
		if(array_key_exists($key, Page::$RegisteredObjects))
			return;
		Page::$RegisteredObjects[$key] = true;
		$dependancies = preg_split('/\|/', $key);
		$key = $dependancies[0];
		$dependant = null;
		if(count($dependancies) > 1)
		{
			$dependant = $dependancies[1];	
		}
		
		$resourceItem = "";
		if(null != $comment)
		{
			$resourceItem.="<!--[if ".$comment."]>\r\n";
		}
		if($resourcetype == "script")
			$resourceItem.="<script src=\"".$resource."\"></script>\r\n";
		else if($resourcetype == "inline-script")
			$resourceItem.="<script type=\"text/javascript\">
".$resource."
</script>\r\n";
		else if($resourcetype == "stylesheet")
			$resourceItem.="<link rel=\"stylesheet\" href=\"".$resource."\" />\r\n";

		if(null != $comment)
		{
			$resourceItem.="<![endif]-->\r\n";
		}
		
		if($placement == "after")
		{
			if($resourcetype == "script" || $resourcetype =="inline-script")
				$this->RegisteredScriptsAfterRender->SetValue($key, $resourceItem, $dependant);
			else if($resourcetype == "stylesheet")
				$this->RegisteredCssAfterRender->SetValue($key, $resourceItem, $dependant);
		}
		else if($placement == "before" || $resourcetype =="inline-script")
		{
			if($resourcetype == "script")
				$this->RegisteredScriptsBeforeRender->SetValue($key, $resourceItem, $dependant);
			else if($resourcetype == "stylesheet")
				$this->RegisteredCssBeforeRender->SetValue($key, $resourceItem, $dependant);
		}
	}
    
}

?>

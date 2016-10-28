<?php
require_once("WebControl.php");

class GridControl extends WebControl
{
	public function __construct($templatePath, $dataSource)
	{
		parent::__construct();
		require_once(dirname(dirname(__FILE__))."/Data/SR.php");
		require_once(dirname(__FILE__)."/RepeaterTemplate.php");
		$this->TemplatePath = $templatePath;
		$this->DataSource = $dataSource;
	}
	
	public $TemplatePath = "";
	public $Trace = false;
	
	private $GridTemplateData;
	private $Templates = array();
	
	
	public function Load()
	{
		if($this->Trace)
			echo "Template: ".$this->TemplatePath;
		$handle = fopen($this->TemplatePath, "r");
		if ($handle) 
		{
			while (($buffer = fgets($handle, 4096)) !== false) 
			{
				$buffer = $this->ReadLine($buffer);
				if($this->startGridControl || preg_match(GridControl::RE_BINDERGRIDTEMPLATESTART, $buffer))
				{
					$this->startGridControl = true;
					$this->ReadGridTemplate($handle);
				}
			}
			if (!feof($handle)) 
			{
				echo "Error: unexpected fgets() fail\n";
			}
			fclose($handle);
		}
		else
		{
			if($this->Trace)
				echo " Template did not open ";	
		}
	}
	
	private function ReadGridTemplate($handle)
	{
		$matches = null;
		while (($buffer = fgets($handle, 4096)) !== false) 
		{
			$buffer = $this->ReadLine($buffer);			
			if(preg_match(GridControl::RE_BINDERGRIDTEMPLATEEND, $buffer))
			{
				return true;	
			}
			else if(preg_match(GridControl::RE_BINDERTEMPLATESTART, $buffer))
			{
				$this->ReadTemplate($handle);	
			}
			else if(preg_match(GridControl::RE_REPEATERTEMPLATESTART, $buffer, $matches))
			{ 
				$this->ReadRepeaterTemplate($handle, $matches["id"]);	
			}
		}
		// Throw exception - Missing tag </DataBinder:GridTemplate>
		return false;
	}
	
	private function ReadTemplate($handle)
	{
		$matches = null;
		while (($buffer = fgets($handle, 4096)) !== false) 
		{
			$buffer = $this->ReadLine($buffer);
			if(preg_match(GridControl::RE_BINDERTEMPLATEEND, $buffer))
			{
				return true;	
			}
			// Add line to data buffer	
			// add buffer to the template data
			$this->GridTemplateData.=$buffer."\r\n";
		}
		// throw error:: Missing tag </Template> 
		return false;
	}
	
	private function ReadRepeaterTemplate($handle, $templatename)
	{
		$matches = null;
		$RepeaterTemplate = $this->GetRepeaterTemplate($templatename);
		
		while (($buffer = fgets($handle, 4096)) !== false) 
		{
			$buffer = $this->ReadLine($buffer);
			if(preg_match(GridControl::RE_REPEATERTEMPLATEEND, $buffer))
			{
				return true;	
			}
			$RepeaterTemplate->TemplateData.=$buffer;
		}
		// throw error:: Missing tag </RepeaterTemplate> 
		return false;
	}
	
	private function GetRepeaterTemplate($templatename, $create = true)
	{
		$RepeaterTemplate = null;
		if(array_key_exists($templatename, $this->Templates))
			$RepeaterTemplate = $this->Templates[$templatename];
		else if($create)
		{
			$RepeaterTemplate = new RepeaterTemplate($templatename);
			$this->Templates[$templatename] = $RepeaterTemplate;
		}
		return $RepeaterTemplate;
	}
	
	private function ReadLine($line)
	{
		if(preg_match(GridControl::RE_LOCALIZEDSTRING, $line, $matches))
		{
			$key = $matches["key"];
			$localized = preg_replace(GridControl::RE_LOCALIZEDSTRING, SR::GetString($key), $line);
			return $localized;
		}
		else if(preg_match(GridControl::RE_DATABINDERURLKEY, $line, $matches))
		{
			$value = $matches["key"];
			$value = Server::MapPath($value);
			$localized = preg_replace(GridControl::RE_DATABINDERURLKEY, $value, $line);
			return $localized;
		}
		return $line;
		
	}
	
	public function Render()
	{
		$matches = null;
		foreach(preg_split("/((\r?\n)|(\r\n?))/", $this->GridTemplateData) as $buffer)
		{
			if(preg_match(GridControl::RE_DATAREADERPLACEHOLDER, $buffer, $matches))
			{
				// Get Repeater template and render it with datasource
				$rtp = $this->GetRepeaterTemplate($matches["target"], false);
				if(null == $rtp)
					echo " Error rendering template: Template does not exist ->".$matches["target"];
				else
				{
					foreach($this->DataSource as $model)
					{
						$rtp->RenderTemplate($model);	
					}	
				}
			}
			else
			{
				print $buffer;	
			}
		}
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
	
	const RE_BINDERGRIDTEMPLATESTART = "/<DataBinder:GridTemplate>/";
	const RE_BINDERGRIDTEMPLATEEND = "/<\/DataBinder:GridTemplate>/";
	private $startGridControl = false;
	const RE_BINDERTEMPLATESTART = "/<Template>/";
	const RE_BINDERTEMPLATEEND = "/<\/Template>/";
	private $inDataTemplate = false;
	const RE_DATAREADERPLACEHOLDER = "/<DataBinder:Repeater Target=\"(?P<target>[a-zA-Z0-9]+)\" \/>/";
	
	const RE_REPEATERTEMPLATESTART = "/<RepeaterTemplate ID=\"(?P<id>[a-zA-Z0-9]+)\">/";
	const RE_REPEATERTEMPLATEEND = "/<\/RepeaterTemplate>/";
	const RE_DATABINDERURLKEY = "/(\[DataBinder::RelativeUrl\((?P<key>[~\/a-zA-Z0-9\-_@\.]+)\)\])/";
	
	const RE_LOCALIZEDSTRING = "/(\[String\((?P<key>[a-zA-Z0-9_]+)\)\])/";
}
?>

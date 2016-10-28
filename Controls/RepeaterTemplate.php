<?php

class RepeaterTemplate
{
	public function __construct($name)
	{
		$this->TemplateName = $name;
		$this->TemplateData = "";
	}
	
	public $TemplateName;
	
	public $TemplateData;
	
	/**
	 * Registered - Boolean value that determines if the template is used in a Template. If the Registered value
	 *				is not true, the template is simply ignored during rendering
	 *
	 * @var mixed 
	 *
	 */	
	public $Registered = false;
	
	public function RenderTemplate($model)
	{
		$matches = null;
		$propertyname = "";
		$value = null;
		$mdl = $model;
		foreach(preg_split("/((\r?\n)|(\r\n?))/", $this->TemplateData) as $buffer)
		{
			if(preg_match(RepeaterTemplate::RE_DATABINDERIMAGEKEY, $buffer, $matches))
			{
				$propertyname = $matches["key"];
				$parts = split("->", $propertyname);				
				$value = $mdl->$parts[0];
				for($x = 0; $x < count($parts); $x++)
				{
					$prop = $parts[$x];	
					$type = gettype($mdl->$prop);
					if(gettype($mdl->$prop) == "object")
					{
						$mdl = $mdl->$prop;					
					}
					else
					{
						$value = $mdl->$prop;
					}
				}
				$default = $matches["default"];
				//$value = $model->$propertyname;
				if(null == $value)
				{
					$value = $default;	
				}
				$buffer = preg_replace(RepeaterTemplate::RE_DATABINDERIMAGEKEY, $value, $buffer);
			}
			else if(preg_match(RepeaterTemplate::RE_DATABINDERURLKEY, $buffer, $matches))
			{
				$value = $matches["key"];
				$value = Server::MapPath($value);
				$buffer = preg_replace(RepeaterTemplate::RE_DATABINDERURLKEY, $value, $buffer);
			}
			else if(preg_match(RepeaterTemplate::RE_DATABINDERKEY, $buffer, $matches))
			{
				$propertyname = $matches["key"];
				$value = $model->$propertyname;
				$buffer = preg_replace(RepeaterTemplate::RE_DATABINDERKEY, $value, $buffer);
			}
			print $buffer;
		}
	}		
	
	const RE_DATABINDERIMAGEKEY = "/(\[DataBinder::Image\((?P<key>[a-zA-Z0-9\->]+), '(?P<default>.*)'\)\])/";
	const RE_DATABINDERURLKEY = "/(\[DataBinder::RelativeUrl\('(?P<key>[~\/a-zA-Z0-9\->]+)'\)\])/";
	const RE_DATABINDERKEY = "/(\[DataBinder::(?P<key>[a-zA-Z0-9]+)\])/";
}

?>

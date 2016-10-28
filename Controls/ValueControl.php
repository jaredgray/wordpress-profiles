<?php

require_once("WebControl.php");
/**
 * class ValueControl
 *
 * Description for class ValueControl
 *
 * @author:
*/
class ValueControl extends WebControl
{
	public function __construct()
	{
		parent::__construct();	
	}

	/**
	 * ValueControl constructor
	 *
	 * @param 
	 */
	function ValueControl() 
	{

	}
	
	public $Value = null;
	
	public $Type = "";
	
	protected function RenderAttributes()
	{
		parent::RenderAttributes();
		print " type=\"".$this->Type."\"";
		if(null != $this->Value)
			print " value=\"".$this->Value."\"";
	}
	
	public function Load()
	{
		parent::Load();
		if(null == $this->Value)
		{
			$val = $this->GetValue();
			if(null != $val)
				$this->Value = $val;
			else
				$this->Value = "";
		}
	}
}

?>

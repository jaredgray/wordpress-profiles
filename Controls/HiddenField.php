<?php

require_once("ValueControl.php");

/**
 * class HiddenField
 *
 * Description for class HiddenField
 *
 * @author:
*/
class HiddenField extends ValueControl
{
	public function __construct()
	{
		parent::__construct();	
	}

	/**
	 * HiddenField constructor
	 *
	 * @param 
	 */
	function HiddenField() 
	{

	}
	
	public function Render()
	{
		$id = $this->ID;
		if(null == $id)
			$id = "ctrl_".$this->Name;
		print "<input id=\"".$id."\" type=\"hidden\" name=\"".$this->Name."\" value=\"".$this->Value."\" />";	
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
}

?>

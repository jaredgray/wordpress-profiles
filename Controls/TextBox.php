<?php

require_once("ValueControl.php");
/**
 * class TextBox
 *
 * Description for class TextBox
 *
 * @author:
*/
class TextBox extends ValueControl
{
	public function __construct()
	{
		parent::__construct();
		$this->TagName = "input";
		$this->Type = "text";
	}

	/**
	 * TextBox constructor
	 *
	 * @param 
	 */
	function TextBox() 
	{

	}
	
	protected function RenderClosingBeginTag()
	{
	}
	
	protected function RenderEndTag()
	{
		print " />";	
	}
}

?>

<?php

require_once("WebControl.php");
/**
 * class Label
 *
 * Description for class Label
 *
 * @author:
*/
class Label extends WebControl
{
	public function __construct()
	{
		parent::__construct();	
	}

	/**
	 * Label constructor
	 *
	 * @param 
	 */
	function Label() 
	{

	}
	
	public $Text;
	
	public function Render()
	{
		print "<label ID=\"".$this->ID."\" class=\"".$this->CssClass."\">".$this->Text."</label>";	
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

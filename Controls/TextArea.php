<?php

require_once("WebControl.php");
/**
 * class TextArea
 *
 * Description for class TextArea
 *
 * @author:
*/
class TextArea  extends WebControl
{
	public function __construct()
	{
		parent::__construct();	
	}

	/**
	 * TextArea constructor
	 *
	 * @param 
	 */
	function TextArea() 
	{

	}
	
	public $Text;
	
	public function Render()
	{
		print "<textarea id=\"".$this->ID."\" class=\"".$this->CssClass."\">".$this->Text."</textarea>";	
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

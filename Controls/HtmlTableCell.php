<?php

require_once("WebControl.php");
/**
 * class HtmlTableCell
 *
 * Description for class HtmlTableCell
 *
 * @author:
*/
class HtmlTableCell extends WebControl
{

	public function __construct()
	{
		parent::__construct();
		$this->RequireName = false;
		$this->TagName = "td";
	}

	/**
	 * HtmlTable constructor
	 *
	 * @param 
	 */
	function HtmlTableCell() 
	{

	}
	
	public $Title = null;
	
	protected function RenderBeginTag()
	{
		if(null != $this->Title)
			print "<th>".$this->Title."</th>";
		print "<td";
		parent::RenderAttributes();
	}
}

?>

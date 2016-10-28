<?php

require_once("WebControl.php");
/**
 * class HtmlTableRow
 *
 * Description for class HtmlTableRow
 *
 * @author:
*/
class HtmlTableRow extends WebControl
{

	public function __construct()
	{
		parent::__construct();	
		$this->RequireName = false;
		$this->TagName = "tr";
	}

	/**
	 * HtmlTable constructor
	 *
	 * @param 
	 */
	function HtmlTableRow() 
	{

	}
}

?>

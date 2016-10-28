<?php

require_once("WebControl.php");
/**
 * class HtmlTable
 *
 * Description for class HtmlTable
 *
 * @author:
*/
class HtmlTable extends WebControl
{
	public function __construct()
	{
		parent::__construct();	
		$this->RequireName = false;
		$this->TagName = "table";
	}

	/**
	 * HtmlTable constructor
	 *
	 * @param 
	 */
	function HtmlTable() 
	{

	}
}

?>

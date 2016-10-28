<?php

require_once("Control.php");
require_once("Page.php");

/**
 * class WebControl
 *
 * Description for class WebControl
 *
 * @author:
*/
abstract class WebControl extends Control
{
	public function __construct()
	{
		parent::__construct();	
	}

	/**
	 * WebControl constructor
	 *
	 * @param 
	 */
	function WebControl() 
	{

	}
	
	protected function RenderAttributes()
	{
		parent::RenderAttributes();
		if(null != $this->CssClass)
			print " class=\"".$this->CssClass."\"";	
	}
	
	public $CssClass;
	
	
	protected function RegisterResource($key, $resourcetype, $resource, $comment = null, $placement = "after")
	{
		Page::$Current->RegisterResource($key, $resourcetype, $resource, $comment, $placement);
	}
}

?>

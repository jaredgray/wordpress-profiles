<?php
require_once("HttpPageRequest.php");
class HttpContext
{
	public function __construct()
	{
		$this->Request = new HttpPageRequest();		
	}
	
	public static function Current()
	{
		if(null == HttpContext::$_current)
			HttpContext::$_current = new HttpContext();
		return HttpContext::$_current;
	}
	private static $_current;
	
	public $Request;
	
	function RequestUri() 
	{
		$pageURL = 'http';
		if ($_SERVER["HTTPS"] == "on") 
		{
			$pageURL .= "s";
		}
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") 
		{
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else 
		{
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}
}

?>

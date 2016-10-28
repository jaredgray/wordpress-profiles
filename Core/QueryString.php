<?php

require_once("StateBag.php");
/**
 * class QueryString
 *
 * Description for class QueryString
 *
 * @author:
*/
class QueryString extends StateBag
{
	public function __construct()
	{
		parent::__construct($_SERVER['QUERY_STRING']);	
	}
	/**
	 * QueryString constructor
	 *
	 * @param 
	 */
	function QueryString() 
	{			
	}
}

?>

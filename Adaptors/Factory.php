<?php

/**
 * class Factory
 *
 * Description for class Factory
 *
 * @author:
*/
class Factory 
{
	public $RootDirectory;
	
	public $Adapter;
	
	public static $Current;
	
	public function __construct($rootdirectory)
	{
		Factory::$Current = $this;
		$this->RootDirectory = $rootdirectory;
	}
	
	public function Load()
	{
		global $wp_version;
		
		if(isset($wp_version))
		{
			Application::$RootUrl = WP_PLUGIN_URL."/Profiles";
			require_once(dirname(__FILE__)."/Wordpress/WordPressAdaptor.php");
			$this->Adapter = new WordPressAdaptor($wp_version, $this->RootDirectory);	
		}
		else
		{
			echo "Plugin Site Type not supported";	
		}
	}
	
	/**
	 * Factory constructor
	 *
	 * @param 
	 */
	function Factory() 
	{

	}
}

?>

<?php

/**
 * class Application
 *
 * Description for class Application
 *
 * @author:
*/
class Application  
{
	/**
	* Provides the physical file uri to the "Application"
	*/
	public static $RootDirectory;
	/**
	* Provides the root web url to the "Application"
	*/
	public static $RootUrl;
	
	public static $CurrentProfile;
	
	private static $IsInitialized = false;
	public static function Initialize()
	{
		if(Application::$IsInitialized)
			return;
		Application::$IsInitialized = true;
		require_once(dirname(dirname(__FILE__))."/Configuration.php");
		Application::$RootUrl = Configuration::$RootUrl;
		Application::$RootDirectory = dirname(dirname(__FILE__));
	}
}

?>

<?php

require_once("Application.php");
/**
 * class Server
 *
 * Description for class Server
 *
 * @author:
*/
class Server 
{

	/**
	 * Server constructor
	 *
	 * @param 
	 */
	function Server() 
	{

	}
	
	/**
	 * function MapPath
	 * @param $relativePath either a local or web path beginning with ~
	 * @param $makeLocal converts a path set up for w3 /../ to local path \..\
	 * 
	 * MapPath will result in a path without trailing slashes regarless of whether it is local or not
	 */
	public static function MapPath($relativePath, $makeLocal = false)
	{
		$base = Application::$RootUrl;
		$hack = "/";
		$reverseHack = "\\";
		if($makeLocal)
		{
			$hack = Configuration::$LocalDirectoryHack;
			$reverseHack = Configuration::$LocalDirectoryHack == "\\" ? "/" : "\\";
			$base = Application::$RootDirectory;
		}
		$path = trim($relativePath, $hack);
		$path = str_replace($reverseHack, $hack, $path);
		$path = str_replace("~".$hack, $base.$hack, $path);
		return $path;
	}
	
	public static function CombinePath($basePath, $appendingPath, $makeLocal = false)
	{
		$hack = "/";
		if($makeLocal)
			$hack = Configuration::$LocalDirectoryHack;
		$basePath = Server::MapPath($basePath, $makeLocal);
		$appendingPath = trim($appendingPath, $hack);
		return $basePath.$hack.$appendingPath;
	}
}

?>

<?php

/**
 * class Configuration
 *
 * Description for class Configuration
 *
 * @author:
*/
class Configuration  
{	
	public static $IsConfigurationSet = true;
	public static $Port = 3306;
	public static $Server = "localhost";
	public static $Database = "----";
	public static $DBUser = "------";
	public static $DBPassword = "OlOMhd02QR";
	public static $RootUrl = "http://www.frenchcuthairpdx.com/wp-content/plugins/Profiles";
	public static $ImageDirectory = "~/Images/User";
	public static $MaxImageWidth = 500;
	public static $MaxImageHeight = 400;
	public static $MaxThumbImageWidth = 200;
	public static $MaxThumbImageHeight = 200;
	public static $ImageQuality = 95;
	public static $DBVersion = 101;
	public static $DBInstalledVersion = null;
	public static $LocalDirectoryHack = "/";
	/**
	 * PostedFile_UseCopy is a flag to force the PostedFile class to use copy instead of move_uploaded_file
	 *						- on windows, permissions may cause you to turn this on, but generally this should be off
	 *
	 * @var bool 
	 *
	 */	
	public static $PostedFile_UseCopy = true;
}

?>

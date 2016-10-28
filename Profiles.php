<?php
/*
Plugin Name: Profiles
Plugin URI: http://www.drumssuck.com
Description: Provisions the ability to create profiles for members of an organization to display publicly on a profile section of your site
Version: 1.0
Author: Jared Gray, dev dev
*/
$RootDirectory = dirname(__FILE__);
require_once($RootDirectory."/Core/Reference.php");
Reference::LoadAll($RootDirectory);
Application::Initialize();
Application::$RootDirectory = $RootDirectory;

$factory = new Factory($RootDirectory);
$factory->Load();

?>
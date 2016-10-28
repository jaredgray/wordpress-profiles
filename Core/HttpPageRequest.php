<?php

require_once("QueryString.php");
require_once(dirname(__FILE__)."/IO/PostedFile.php");

class HttpPageRequest
{
	public function __construct()
	{
		$this->Initialize();
	}
	
	public $QueryString = null;
	public $Method = "";
	public $Referer = "";
	public $UserAgent = "";
	public $PostedFiles = null;
	
	private function GetPostedFiles()
	{
		return PostedFile::GetPostedFiles();	
	}	
	
	private function Initialize()
	{
		$this->QueryString = new QueryString();
		$this->Method = array_key_exists("REQUEST_METHOD", $_SERVER) ? $_SERVER["REQUEST_METHOD"] : "GET";
		$this->Referer = array_key_exists("HTTP_REFERER", $_SERVER) ? $_SERVER["HTTP_REFERER"] : "";
		$this->UserAgent = array_key_exists("HTTP_USER_AGENT", $_SERVER) ? $_SERVER["HTTP_USER_AGENT"] : "";
		$this->PostedFiles = $this->GetPostedFiles();
	}
}

?>

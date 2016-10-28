<?php
class MySqlConnection
{
	function __construct($DataSource)
	{
		$this->DataSource = $DataSource;
	}
	
	public $Handle = null;
	
	private $DataSource = null;	
	public $IsOpen = false;
		
	public function Open()
	{
		$this->Handle = new mysqli($this->DataSource->Server, $this->DataSource->User, $this->DataSource->Password, $this->DataSource->Database);
		if ($this->Handle->connect_errno) 
		{
			$this->OnConnectionError($this->Handle->connect_error);
			return false;
		}
		else 
		{
			// Flag Connection as Open       
			$this->IsOpen = true;
			return true;
		}
	}
	
	public function Close()
	{
		if ($this->IsOpen) 
		{ 
			$this->Handle->close();
			//mysql_close($this->Handle); 
		}
		$this->IsOpen = false;
	}
	
    protected function OnConnectionError($error=false)
    {
    	if ($error) 
		{
	    	$this->OnError("Connection Error: " . $error);
		}
    }
	
	protected function OnError($err)
	{
		echo $err;
	}
}
?>

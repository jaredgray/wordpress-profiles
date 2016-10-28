<?php
abstract class DataMapper
{
	function __construct($ds)
	{
		require_once(dirname(__FILE__)."/MySqlConnection.php");
		require_once(dirname(__FILE__)."/MySqlCommand.php");
		$this->DataSource = $ds;
	}
	
	protected function ExecuteQuery($queryString, $inserting = false)
	{
		$this->Connection = new MySqlConnection($this->DataSource);
		$this->Command = new MySqlCommand($queryString, $this->Connection);
		return $this->Command->ExecuteQuery($inserting);
	}
	
	private $Command = null;
	private $Connection = null;	
	protected $DataSource = null;
}

?>

<?php
class MySqlCommand
{
	function __construct($query, $connection)
	{
		$this->Query = $query;
		$this->Connection = $connection;
	}

	public $Query = "";
	public $Connection = null;	
	public $RowCount = 0;
	
	public function ExecuteQuery($inserting = false)
	{
		if(null == $this->Connection)
			echo " Connection cannot be null ";
		$this->Connection->Open();
		if(!$this->Connection->IsOpen)
			echo " Connection did not open correctly ";
		$rslt = $this->ExecuteQueryInternal($inserting);
		$this->Connection->Close();
		return $rslt;
	}
	
	private function ExecuteQueryInternal($inserting = false)
	{
		try
		{
			$results = array();
			$id = $this->Connection->Handle->query($this->Query);
			if(true === $inserting)
			{
				return $this->Connection->Handle->insert_id;
			}
			if(null == $this->Connection->Handle)
				echo " connection handle is null ";
			if ($id && gettype($id) != 'boolean') 
			{
				$this->RowCount = $id->num_rows;
				while($row=$id->fetch_assoc())
				{
					array_push($results, $row);
				}
			}
			else
			{
				return null;
			}
			
			return $results;
		}
		catch(exception $ex)
		{
			echo 'mysql exception: '.$ex;
			return null;
		}	
	}
}
?>

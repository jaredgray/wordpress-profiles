<?php

/**
 * class StateBag
 *
 * Description for class StateBag
 *
 * @author:
*/
class StateBag  
{
	public function __construct($keyvaluestring = null)
	{
		$this->props = array();
		$this->pending = array();
		if(null != $keyvaluestring)
			parse_str($keyvaluestring, $this->props);		
	}

	/**
	 * StateBag constructor
	 *
	 * @param 
	 */
	function StateBag() 
	{

	}
	
	private $props;
	
	private $pending;
	
	public function HasKey($key)
	{
		return array_key_exists($key, $this->props);	
	}
	
	public function GetValue($key)
	{
		if(array_key_exists($key, $this->props))
			return $this->props[$key];
		return null;
	}
	
	public function SetValue($key, $value, $dependentkey = null)
	{
		if(null == $dependentkey)
			$this->props[$key] = $value;
		else
			$this->AddDependency($dependentkey, $key, $value);
	}
	
	public function Remove($key)
	{
		if(array_key_exists($key, $this->props))
			unset($this->props[$key]);
	}
	
	public function ToString($includekey = true)
	{
		$sb = "";
		foreach($this->props as $key => $value)
		{
			if($includekey)
				$sb.=$key."=".$value;
			else
				$sb.=$value;
		}	
		return $sb;
	}
	
	public function AddDependency($dependentkey, $key, $value)
	{
		$Dependency = new Dependency($dependentkey, $key, $value);
		array_push($this->pending, $Dependency);
	}
	
	public function CommitDependencies()
	{
		$this->CommitDependencyList($this->pending);
	}
	
	private function CommitDependencyList($dependents)
	{
		$resolveditems = array();
		
		foreach($dependents as $dependent)
		{
			if($this->HasKey($dependent->Key))
			{
				$this->SetValue($dependent->Key, $dependent->Value);
				array_push($resolveditems, $dependent);	
			}
		}
		
		
		$resolvedcount = count($resolveditems);
		
		if($resolvedcount > 0)
		{
			foreach($resolveditems as $key => $resolved)
			{
				unset($dependents[$key]);	
			}
		}
		else
		{
			foreach($dependents as $dependent)
			{
				$this->SetValue($dependent->Key, $dependent->Value);
			}
			return;
		}
		$this->CommitDependencyList($dependants);
	}
}

class Dependency
{
	public function __construct($foreignid, $id, $object)
	{
		$this->ForeignKey = $foreignid;
		$this->Key = $id;
		$this->Value = $object;	
	}
	
	public $ForeignKey;
	public $Key;
	public $Value;
		
			
}


?>

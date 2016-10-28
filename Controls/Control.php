<?php

/**
 * class Control
 *
 * Description for class Control
 *
 * @author:
*/
class Control  
{
	
	public $Controls;
	public function __construct()
	{
		$this->Controls = array();
	}
	/**
	 * Control constructor
	 *
	 * @param 
	 */
	function Control() 
	{
	}
	
	public $ID;
	
	private static $offset = 0;
	
	public $RequireName = true;
	public $Name;
	
	public $DataSource = null;	
	
	public $TagName = "";
	
	public function EnsureID()
	{
		if(null == $this->ID && null != $this->Name)
		{
			$this->ID = "ctrl".Control::$offset."_".$this->Name;
			Control::$offset++;
		}
	}
	
	public function FindControl($id, $controls = null)
	{
		if(null == $controls)
			$controls = $this->Controls;
		foreach($controls as $ctrl)
		{
			if($ctrl->ID == $id)
				return $ctrl;
			if(null != $ctrl->Controls && count($ctrl->Controls) > 0)
			{
				$c = $this->FindControl($id, $ctrl->Controls);
				if(null != $c)
					return $c;
			}
		}
		return null;
	}
	
	protected function GetValue()
	{
		if(!array_key_exists($this->Name, $_POST))
			return null;
		return $_POST[$this->Name];	
	}
	
	public function AddControl($Ctrl)
	{
		if(null === $this->Controls)
		{
			echo " Control collection cannot be null - check to make sure the call is parent::AddControl and not this->AddControl ";
		}
		if(in_array($Ctrl, $this->Controls))
			echo "Control already exists in Control Collection: ".$Ctrl->Name;
		array_push($this->Controls, $Ctrl);
		
		$Ctrl->CreateControls();
	}
	
	protected function CreateControls()
	{
	}
	
	public function Load()
	{
		if($this->RequireName && null == $this->Name)
			echo "Value Control's Name cannot be Null";
		$this->EnsureID();
		foreach($this->Controls as $ctrl)
		{
			$ctrl->Load();
		}
	}
	
	public function DataBind()
	{
		
	}
	
	public function Render()
	{
		$this->RenderBeginTag();
		$this->RenderClosingBeginTag();
		$this->RenderContentBeforeChildren();
		$this->RenderChildControls();
		$this->RenderContentAfterChildren();
		$this->RenderEndTag();
	}
	
	protected function RenderContentAfterChildren() { }
	protected function RenderContentBeforeChildren() { }
	
	protected function RenderBeginTag()
	{
		print "<".$this->TagName;
		$this->RenderAttributes();
	}
	
	protected function RenderClosingBeginTag()
	{
		print ">";
	}
	
	protected function RenderAttributes()
	{
		if(null != $this->ID)
			print " id=\"".$this->ID."\"";
		if(null != $this->Name)
			print " name=\"".$this->Name."\"";
	}
	
	protected function RenderEndTag()
	{
		print"</".$this->TagName.">";
	}
	
	protected function RenderChildControls()
	{		
		foreach($this->Controls as $Control)
		{
			$Control->Render();	
		}
	}
}

?>

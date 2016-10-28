<?php

require_once("ValueControl.php");
/**
 * class Button
 *
 * Description for class Button
 *
 * @author:
*/
class Button extends ValueControl
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Button constructor
	 *
	 * @param 
	 */
	function Button() 
	{

	}
	
	public function OnClick($sender)
	{		
		if(null != $this->Click)
		{
			// 2nd parameter would be eventargs, but eventargs doesnt exist yet.
			$method = $this->Click[1];
			$this->Click[0]->$method($this, null);
		}
	}
	
	public function Render()
	{
		print "<input onclick=\"document.getElementById('ctrl___ViewState').value+='&Click=".$this->ID."'\" id=\"".$this->ID."\" class=\"".$this->CssClass."\" type=\"submit\" name=\"".$this->Name."\" value=\"".$this->Value."\" />";
	}
	
	
	protected function RenderBeginTag()
	{
	}	
	protected function RenderClosingBeginTag()
	{
	}
	protected function RenderEndTag()
	{
	}
	
	public $Click;
}

?>

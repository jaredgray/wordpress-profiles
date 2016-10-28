<?php

require_once(dirname(dirname(__FILE__))."/Controls/WebControl.php");
require_once(dirname(dirname(__FILE__))."/Controls/Button.php");
require_once(dirname(dirname(__FILE__))."/Controls/HtmlTable.php");
require_once(dirname(dirname(__FILE__))."/Controls/HtmlTableRow.php");
require_once(dirname(dirname(__FILE__))."/Controls/HtmlTableCell.php");
require_once(dirname(dirname(__FILE__))."/Controls/TextBox.php");
require_once(dirname(dirname(__FILE__))."/Controls/Editor/Editor.php");
/**
 * class ProfileEditor
 *
 * Description for class ProfileEditor
 *
 * @author:
*/
class ProfileEditor extends WebControl
{
	public function __construct($model)
	{
		parent::__construct();
		$this->RequireName = false;
		$this->TagName = "div";
		$this->Model = $model;
	}

	/**
	 * ProfileEditor constructor
	 *
	 * @param 
	 */
	function ProfileEditor() 
	{

	}
	
	public $Model = null;
	
	public $tb_DisplayName = null;
	public $tb_Blurb = null;
	public $tb_Biography = null;
	
	public $btn_Submit = null;
	
	public function LoadChildControls()
	{
		$path = Server::MapPath("~/Admin/Templates/EditProfile_Title.xml", true);
		$titlegc = new GridControl($path, array());
		//$titlegc->Trace = true;
		$this->AddControl($titlegc);
		
		
		$table = new HtmlTable();
		$table->CssClass = "form-table";
		$this->AddControl($table);
		
		// Display Name
		$row = new HtmlTableRow();
		$table->AddControl($row);
		$cell = new HtmlTableCell();
		$cell->Title = "Display Name";
		$this->tb_DisplayName = new TextBox();
		$this->tb_DisplayName->Name = "tb_DisplayName";
		$this->tb_DisplayName->CssClass = "regular-text";
		$cell->AddControl($this->tb_DisplayName);
		//regular-text
		$row->AddControl($cell);
		
		// Blurb
		$row = new HtmlTableRow();
		$table->AddControl($row);
		$cell = new HtmlTableCell();
		$cell->Title = "Blurb";
		$this->tb_Blurb = new TextBox();
		$this->tb_Blurb->Name = "tb_Blurb";
		$this->tb_Blurb->CssClass = "regular-text";
		$cell->AddControl($this->tb_Blurb);
		//regular-text
		$row->AddControl($cell);
		
		// Biography
		$row = new HtmlTableRow();
		$table->AddControl($row);
		$cell = new HtmlTableCell();
		$cell->Title = "Biography";
		
		$this->tb_Biography = new Editor();
		$this->tb_Biography->ID = "tb_Biography";
		$this->tb_Biography->Name = "tb_Biography";
		$this->tb_Biography->CssClass = "regular-text";
		$cell->AddControl($this->tb_Biography);
		//regular-text
		$row->AddControl($cell);
		
		// Button
		$row = new HtmlTableRow();
		$table->AddControl($row);
		$cell = new HtmlTableCell();
		$this->btn_Submit = new Button();
		$this->btn_Submit->ID = "btn_Submit";
		$this->btn_Submit->Name = "ProfileButton";
		$this->btn_Submit->CssClass = "button-secondary action";
		$this->btn_Submit->Value = "Apply";
		$this->btn_Submit->Click = array($this, "btn_Submit_Click");
		$cell->AddControl($this->btn_Submit);
		$row->AddControl($cell);
	}
	
	public function btn_Submit_Click()
	{
		//echo "Model Display Name: ".$this->Model->DisplayName;
		//echo " Display Name: ".$this->tb_DisplayName->Value;
		$this->Model->DisplayName = $this->tb_DisplayName->Value;
		$this->Model->Blurb = $this->tb_Blurb->Value;
		$this->Model->Biography = $this->tb_Biography->Text;
		$this->SaveProfile();		
	}
	
	public function SaveProfile()
	{
		$uow = UnitOfWork::Instance();		
		$mapper = $uow->GetFinder("IProfileFinder");
		if(null == $mapper)
			echo "Mapper is null";
		else
		{
			$mapper->Save($this->Model);
		}
	}
	
	
	public function DataBind()
	{
		$this->tb_DisplayName->Value = $this->Model->DisplayName;
		$this->tb_Blurb->Value = $this->Model->Blurb;
		$this->tb_Biography->Text = $this->Model->Biography;
	}
}

?>

<?php
class Editor extends WebControl
{
	public function __construct()
	{
		parent::__construct();
		$this->TagName = "textarea";
	}
	
	public $Text;
	public $Columns = 150;
	public $Rows = 15;
	
	protected function RenderContentAfterChildren() 
	{
		print $this->Text;
	}
	
	protected function RenderContentBeforeChildren() { }
	protected function RenderChildControls() { }
	protected function CreateControls()
	{
		//Register scripts
		$this->RegisterResource( 'jHtmlArea.min|jquery.1.8.1.min', "script", Server::MapPath('~/Controls/Editor/Source/jHtmlArea-0.7.5.min.js'), null, 'after');
		$this->RegisterResource( 'jHtmlArea.ColorPicker.min|jquery.1.8.1.min', "script", Server::MapPath('~/Controls/Editor/Source/jHtmlArea.ColorPickerMenu-0.7.0.min.js'), null, 'after');
		$src = str_replace("{0}", $this->ID, Editor::STARTUP_SCRIPT);
		$src = str_replace("{1}", Server::MapPath("~/Controls/Editor/Source/Resource/jHtmlArea.iframe.css"), $src);
			$this->RegisterResource( $this->ID.'jHtmlArea|jHtmlArea.ColorPicker.min', "inline-script", $src, null, 'after');
		
		//Register styles
		$this->RegisterResource( 'jHtmlArea.css', "stylesheet", Server::MapPath('~/Controls/Editor/Source/Resource/jHtmlArea.css'), null, "after");
		$this->RegisterResource( 'jHtmlArea.ColorPickerMenu.css', "stylesheet", Server::MapPath('~/Controls/Editor/Source/Resource/jHtmlArea.ColorPickerMenu.css'), null, "after");
	}
	
	protected function RenderAttributes()
	{
		parent::RenderAttributes();
		if(null != $this->Columns)
			print " cols=\"".$this->Columns."\"";
		if(null != $this->Rows)
			print " rows=\"".$this->Rows."\"";
	}
		
	public function Load()
	{
		parent::Load();
		if(null == $this->Text)
		{
			$val = $this->GetValue();
			if(null != $val)
				$this->Text = $val;
			else
				$this->Text = "";
		}
	}
	
	const STARTUP_SCRIPT = '        $(function() {
            $("#{0}").htmlarea({
                toolbar: ["html", "|",
                        "forecolor", "|", 
						"bold", "italic", "underline", "increaseFontSize", "decreaseFontSize", "|", 
						"p", "h1", "h2", "h3", "|", 
						"link", "unlink", "|", 
						"image"],
				css: "{1}"
                });
        });';
}
?>
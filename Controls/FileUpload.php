<?php

require_once("ValueControl.php");
require_once("GridControl.php");

/**
 * class FileUpload
 *
 * Description for class FileUpload
 *
 * @author:
*/
class FileUpload extends WebControl
{
	public function __construct($displaytemplate)
	{
		parent::__construct();
		$this->TemplatePath = $displaytemplate;
		$this->RegisterResources();
	}

	/**
	 * FileUpload constructor
	 *
	 * @param 
	 */
	function FileUpload() 
	{
		
	}
	
	public $ProfileID;
	
	public $FileType;
	
	public $HttpHandler;
	
	public $TemplatePath;
	
	public $DisplayName = "";
	
	public $Description = "";
	
	public static $HasTemplatesAdded = false;
	
	public function Render()
	{
		parent::Render();
	}
	
	protected function CreateControls()
	{
		parent::CreateControls();
		$ds = array();
		$ds[0] = $this;
		$templatedcontrol = new GridControl($this->TemplatePath, $ds);
		parent::AddControl($templatedcontrol);
		$this->RegisterStartupScript();
		$this->CreateStaticTemplates();
	}
	
	private function CreateStaticTemplates()
	{
		if(FileUpload::$HasTemplatesAdded)
			return;		
		FileUpload::$HasTemplatesAdded = true;
		$ds = array();
		$ds[0] = $this;
		parent::AddControl(new GridControl(dirname(dirname(__FILE__))."/Admin/Templates/FileUpload_UploadTemplate.xml", $ds));
		parent::AddControl(new GridControl(dirname(dirname(__FILE__))."/Admin/Templates/FileUpload_DownloadTemplate.xml", $ds));		
	}
	
	public function Load()
	{
		parent::Load();
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
	
	private function RegisterStartupScript()
	{
		$scr = FileUpload::STARTUP_SCRIPT;
		$scr = str_replace("[Name]", $this->Name, $scr);
		$scr = str_replace("[FileType]", $this->FileType, $scr);
		$scr = str_replace("[ProfileID]", $this->ProfileID, $scr);
		$scr = str_replace("[HttpHandler]", $this->HttpHandler, $scr);
		$this->RegisterResource('FileUploadStartup.'.$this->Name, 'inline-script', $scr, null, 'after');
		$scr = FileUpload::GLOBAL_STARTUP_SCRIPT;
		$scr = str_replace("[HttpHandler]", $this->HttpHandler, $scr);
		$this->RegisterResource('FileUploadStartup', 'inline-script', $scr, null, 'after');
	}
	
	private function RegisterResources()
	{
		$this->RegisterResource( 'bootstrap.min',"stylesheet", Server::MapPath('~/FileUpload/css/bootstrap.min.css'), null, "after");
		$this->RegisterResource( 'bootstrap-responsive.min', "stylesheet", Server::MapPath('~/FileUpload/css/bootstrap-responsive.min.css'), null, "after");
		$this->RegisterResource( 'bootstrap-ie6.min', "stylesheet", Server::MapPath('~/FileUpload/css/bootstrap-ie6.min.css'), null, "after");
		$this->RegisterResource( 'bootstrap-image-gallery.min', "stylesheet", Server::MapPath('~/FileUpload/css/bootstrap-image-gallery.min.css'), null, "after");
		$this->RegisterResource( 'jquery.FileUpload-ui.css', "stylesheet", Server::MapPath('~/FileUpload/css/jquery.fileupload-ui.css'), null, "after");
				
		$this->RegisterResource( 'html5', "script", Server::MapPath('~/FileUpload/js/html5.js'), 'lt IE 9');
		$this->RegisterResource( 'jquery.1.8.1.min', "script", Server::MapPath('~/FileUpload/js/jquery.1.8.1.min.js'));
		$this->RegisterResource( 'jquery.ui.widget', "script", Server::MapPath('~/FileUpload/js/vendor/jquery.ui.widget.js'));
		$this->RegisterResource( 'tmpl.min', "script", Server::MapPath('~/FileUpload/js/tmpl.min.js'));
		$this->RegisterResource( 'load-image.min', "script", Server::MapPath('~/FileUpload/js/load-image.min.js'));
		$this->RegisterResource( 'canvas-to-blob.min', "script", Server::MapPath('~/FileUpload/js/canvas-to-blob.min.js'));
		$this->RegisterResource( 'bootstrap.min', "script", Server::MapPath('~/FileUpload/js/bootstrap.min.js'));
		$this->RegisterResource( 'bootstrap-image-gallery.min', "script", Server::MapPath('~/FileUpload/js/bootstrap-image-gallery.min.js'));
		$this->RegisterResource( 'jquery.iframe-transport', "script", Server::MapPath('~/FileUpload/js/jquery.iframe-transport.js'));
		$this->RegisterResource( 'jquery.FileUpload', "script", Server::MapPath('~/FileUpload/js/jquery.fileupload.js'));
		$this->RegisterResource( 'jquery.FileUpload-fp', "script", Server::MapPath('~/FileUpload/js/jquery.fileupload-fp.js'));
		$this->RegisterResource( 'jquery.FileUpload-ui', "script", Server::MapPath('~/FileUpload/js/jquery.fileupload-ui.js'));
		$this->RegisterResource( 'jquery.FileUpload.locale', "script", Server::MapPath('~/FileUpload/js/jquery.fileupload.locale.js'));
		//$this->RegisterResource( 'jquery.FileUpload.main', "script", Server::MapPath('~/FileUpload/js/jquery.fileupload.main.js'));
		
		
		
		/* For: The XDomainRequest Transport is included for cross-domain file deletion for IE8+
		$this->RegisterResource( 'jquery.xdr-transport', "stylesheet", Server::MapPath('~/FileUpload/js/cors/jquery.xdr-transport.js'), 'gte IE 8');
		*/		

	}
	
	
	const STARTUP_SCRIPT = '
    $(function ()
    {
        "use strict";
        var fu = $(".[Name]").fileupload();
        $(".[Name]").each(
        function ()
        {
            this.ImageType = [FileType];
            this.ProfileID = [ProfileID];
            var that = this;
			//this.options.url = "[HttpHandler]";
            this.action = "[HttpHandler]?ProfileID="+ this.ProfileID + "&Type=" + this.ImageType.toString();
            $.getJSON(this.action,
            function (result)
            {
				if (result && result.length)
				{
					$(that).fileupload("option", "done").call(that, null, { result: result });
				}
            });
        });
    });';
	
	const GLOBAL_STARTUP_SCRIPT = '
    $.widget("blueimpUIX.fileupload", $.blueimpUI.fileupload,
    {
        options:
        {
            send: function (e, data)
            {
                var type = this.ImageType;
                var fu = $(this).data("fileupload");
				//debugger;
				data.url = "[HttpHandler]";
                fu.ImageType = type;
                fu.ProfileID = this.ProfileID;
                var userinfo = fu.GetUserAndTypeBeforeUpload(data);
                data.url = data.url && fu._addUrlParams(data.url, userinfo);
                $.blueimpUI.fileupload.prototype.options.send.call(this, e, data);
            },
            destroy: function (e, data)
            {
                var fu = $(this).data("fileupload");
                var userinfo = fu.GetUserAndTypeBeforeDelete(data);
                data.url = data.url && fu._addUrlParams(data.url, userinfo);
                $.blueimpUI.fileupload.prototype.options.destroy.call(this, e, data);
            }
        },
        _addUrlParams: function (url, data)
        {
            return url + (/\?/.test(url) ? "&" : "?") + $.param(data);
        },
        GetUserAndTypeBeforeUpload: function (datacontext)
        {
            obj = {};
            obj["FileType"] = this.ImageType;
            obj["ProfileID"] = this.ProfileID;
            var itms = $(datacontext.context).find(".sort");
            if (null != itms && itms.length && itms.length > 0)
                obj["Order"] = itms[0].value;

            return obj;
        },
        GetUserAndTypeBeforeDelete: function (datacontext)
        {
            obj = {};
            obj[name] = "PersonalImage"; // parts[0];
            return obj;
        }
    });
	';
}

?>

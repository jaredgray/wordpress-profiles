<?php

require_once("WebControl.php");

class ImageViewer extends WebControl
{
	public function __construct()
	{
		parent::__construct();	
		$this->TagName = "div";
		//($key, $resourcetype, $resource, $comment = null, $placement = "after")
		parent::RegisterResource("MooTools", "script", Server::MapPath("~/App_ClientCode/MooTools.js"), null, "before");
		parent::RegisterResource("ImageGallery", "script", Server::MapPath("~/App_ClientCode/ImageGallery.js"), null, "before");
	}
	
	public $IImageDataSource;
	
	public $IsAutoMode = true;
	
	public $Width;
	public $Height;
	
	protected function RenderAttributes()
	{
		parent::RenderAttributes();
	}
	
	protected function RenderClosingBeginTag()
	{
		parent::RenderClosingBeginTag();
		print "<div id=\"ctl00_".$this->Name."\">";
	}
	
	protected function RenderEndTag()
	{
		print "</div>";
		parent::RenderEndTag();
	}
	
	protected function RenderContentAfterChildren() 
	{ 
		if(null == $this->IImageDataSource)
			return;
		$sb = "var ctl00_".$this->Name." = new ImageGallery(\"ctl00_".$this->Name."\", [";
		$delimiter = "";
		print "<script type=\"text/javascript\">

";
		foreach($this->IImageDataSource as $element)
		{
			$sb.=$delimiter."[\"".$element->ImagePath."\", null]";
			$delimiter = ", ";
		}
		$sb.="], \"auto\", ".$this->Width.", ".$this->Height.");";
		print $sb;
		print "

</script>";
	}

/*
	<div class="GalleryShell">
	            <div id="ctl00_PageContent_iv_FeaturedGallery" class="gallery home" style="height:440px;width:960px;">

		</div><script type="text/javascript">
	//<![CDATA[
		window.addEvent('domready', function iv_ctl00_PageContent_iv_FeaturedGallery_render(){
		var ctl00_PageContent_iv_FeaturedGallery = new ImageGallery("ctl00_PageContent_iv_FeaturedGallery", [["Files/PhotoGalleries/g_1/327bbf9e-007a-49a9-a600-9256ffd38a78/66aee7d4-460e-4c89-9f4b-9802f50b0ead.jpg",null],["Files/PhotoGalleries/g_1/327bbf9e-007a-49a9-a600-9256ffd38a78/8b61b506-2b22-4e26-abef-3facbe8df2ff.jpg",null],["Files/PhotoGalleries/g_1/327bbf9e-007a-49a9-a600-9256ffd38a78/29d4b2d0-d164-4c2a-ab53-2e49e4bfe0b5.jpg",null],["Files/PhotoGalleries/g_1/327bbf9e-007a-49a9-a600-9256ffd38a78/ccee006b-cca5-4133-bde7-4efdec1ec2df.jpg",null],["Files/PhotoGalleries/g_1/327bbf9e-007a-49a9-a600-9256ffd38a78/978cc53d-1046-468b-8255-c0c20ca5567f.jpg",null],["Files/PhotoGalleries/g_1/327bbf9e-007a-49a9-a600-9256ffd38a78/a40f24c9-932b-40ee-a79b-ba72cb2bdf5d.jpg",null],["Files/PhotoGalleries/g_1/327bbf9e-007a-49a9-a600-9256ffd38a78/9d9db376-12f6-4d09-b5b4-018011ed21dc.jpg",null],["Files/PhotoGalleries/g_1/327bbf9e-007a-49a9-a600-9256ffd38a78/3ca6dde2-9fa2-42cb-9342-bf6355ece550.jpg",null],["Files/PhotoGalleries/g_1/327bbf9e-007a-49a9-a600-9256ffd38a78/cd08694b-3662-4c35-b735-cb3249606045.jpg",null],["Files/PhotoGalleries/g_1/327bbf9e-007a-49a9-a600-9256ffd38a78/853e23b2-87b3-449f-8dc0-1a99071d9419.jpg",null],["Files/PhotoGalleries/g_1/327bbf9e-007a-49a9-a600-9256ffd38a78/31bdab15-14d9-4556-b225-fdf7815888b5.jpg",null],["Files/PhotoGalleries/g_1/327bbf9e-007a-49a9-a600-9256ffd38a78/bbfdc02b-7c99-4821-b519-ecbf635be168.jpg",null]], "auto", 960, 440)
	});
		//]]>
		</script>
	            
	        </div>
*/	
}

?>
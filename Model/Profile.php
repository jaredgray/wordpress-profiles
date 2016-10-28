<?php
class Profile
{    
    public function __construct()
    {
        
    }
    
    public $ID = 0;
    public $DisplayName = "";
	public $IsActive = 1;
	public $DisplayOrder = 0;
	
    public $Blurb = "";
    public $Biography = "";
    public $UserID = -1;
    
    public $ProfileImages = null;
	public $ProfileImage = null;
	public $ProfileThumbnail = null;
	
	public $PortfolioImages = null;
	public $PortfolioImageCount = 0;
    
}
?>

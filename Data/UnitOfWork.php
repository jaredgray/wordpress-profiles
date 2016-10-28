<?php
class UnitOfWork
{
    public function __construct()
	{
        require_once(dirname(__FILE__)."/DataSource.php");
        require_once(dirname(__FILE__)."/DataMapper.php");
		require_once(dirname(__FILE__)."/ProfileDataMapper.php");
		require_once(dirname(__FILE__)."/ProfileImageDataMapper.php");
        $this->LoadDataSource();
		$this->LoadMappers();
    }
    
    public static function Instance()
    {
        if(null == UnitOfWork::$_instance)
            UnitOfWork::$_instance = new UnitOfWork();
        return UnitOfWork::$_instance;
    }
    private static $_instance = null;
    
    public function LoadDataSource()
    {
        $this->DataSource = new DataSource();
		
        $this->DataSource->Port = 3306;
        $this->DataSource->Server = Configuration::$Server;//DB_HOST; //$_SERVER["SERVER_NAME"];
		$this->DataSource->Database = Configuration::$Database; //DB_NAME; //"monica_wp";
		$this->DataSource->User = Configuration::$DBUser; //DB_USER; //"webpresenter";
		$this->DataSource->Password = Configuration::$DBPassword; //DB_PASSWORD; //"@P0rtl4nd";
    }
	
	private function LoadMappers()
	{
		$this->Finders = array();
		$this->Finders["IProfileFinder"] = new ProfileDataMapper($this->DataSource);
		$this->Finders["IProfileImageFinder"] = new ProfileImageDataMapper($this->DataSource);
	}
	
	public function GetFinder($type)
	{
		return $this->Finders[$type];
	}
    
    public $DataSource = null;
	
	private $Finders = null;
}
?>

<?php

/**
 * class FileObject
 *
 * Description for class FileObject
 *
 * @author:
*/
class FileObject  {

	/**
	 * FileObject constructor
	 *
	 * @param 
	 */
	function FileObject() {

	}
	
	public static function GetFileName($path)
	{
		$arr = explode("/", $path);
		return $arr[count($arr) - 1];
	}
}

?>

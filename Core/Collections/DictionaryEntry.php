<?php

namespace System\Collections;

/*
 * Defines a dictionary key/value pair that can be set or retrieved.
 */
class DictionaryEntry {

    private $_key;
    private $_value;

    /*
     * Initializes an instance of the System.Collections.DictionaryEntry type with the specified key and value.
     *
     * @param key: The object defined in each key/value pair.
     * @param value: The definition associated with key.
     *
     * @throws System.ArgumentNullException: key is null
     */
    public function __construct($key, $value) {
		if(is_null($key)) throw new ArgumentNullException("key is null.");
		$this->_key = $key;
		$this->_value = $value;
	}

	/*
	 * Gets or sets the key in the key/value pair.
	 *
	 * @return The key in the key/value pair.
	 */
	public function key($value=null) {
		if(is_null($value)) 
			return $this->_key;
		$this->_key = $value;
	}

	/*
	 * Gets or sets the value in the key/value pair.
	 *
	 * @return The value in the key/value pair.
	 */
	public function value($value=null) {
		if(is_null($value))
			return $this->_value;
		$this->_value = $value;
	}
}
?>

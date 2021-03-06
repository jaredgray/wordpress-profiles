<?php

namespace System\Collections;

require_once(dirname(__FILE__)."/IEnumerator.php");

/*
 * Enumerates the elements of a nongeneric dictionary.
 */
interface IDictionaryEnumerator extends IEnumerator {

    /*
     * Gets both the key and the value of the current dictionary entry.
     *
     * @return A System.Collections.DictionaryEntry containing both the key and the value of the current dictionary entry.
     *
     * @throws System.InvalidOperationException: The System.Collections.IDictionaryEnumerator is positioned before the first entry
     *         of the dictionary or after the last entry.
     */
	function entry();

	/*
	 * Gets the key of the current dictionary entry.
	 *
	 * @return The key of the current element of the enumeration.
	 *
	 * @throws System.InvalidOperationException: The System.Collections.IDictionaryEnumerator is positioned before the first entry
	 *         of the dictionary or after the last entry.
	 */
	function key();


	/*
	 * Gets the value of the current dictionary entry.
	 *
	 * @return The value of the current element of the enumeration.
	 *
	 * @throws System.InvalidOperationException: The System.Collections.IDictionaryEnumerator is positioned before the first entry
	 *         of the dictionary or after the last entry.
	 */
	function value();
}
?>

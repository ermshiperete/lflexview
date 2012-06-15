<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package    ARK
 * @subpackage Data
 * @author     Cambell Prince <cambell@arketec.com>
 * @link       http://www.arketec.com
 */

/**
 * The global data pool
 * The data pool is a flat pool of IDataSpaces that can be retrieved by key.
 * @see IDataSpace
 */
class DataPool {

	/**
	 * Add a DataSpace to the DataPool
	 *
	 * @param string $name
	 * @param IDataSpace $space
	 */
	public static function setSpace($name, $space) {
		$GLOBALS['_dataPool'][$name] = $space;
	}

	/**
	 * Get the DataSpace $name from the DataPool
	 *
	 * @param string $name
	 * @return IDataSpace
	 */
	public static function getSpace($name) {
		$ret = NULL;
		if (isset($GLOBALS['_dataPool'][$name])) {
			$ret = $GLOBALS['_dataPool'][$name];
		}
		return $ret;
	}

	/**
	 * Deletes the DataSpace $name from the DataPool
	 *
	 * @param string $name
	 */
	public static function delete($name) {
		unset($GLOBALS['_dataPool'][$name]);
	}

}

?>

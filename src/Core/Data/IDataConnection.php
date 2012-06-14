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
 * @package		ARK
 * @subpackage	Data
 * @interface
 * @access public
 * @todo doco for this.
 */
interface IDataConnection {

	function connect($dsn);

	function disconnect();

	/**
	 * Create a data mapper that writes the model to the store
	 * on this connection.
	 * @param string $type
	 * @return IDataStore
	 */
	function createMapper($type);

}

?>
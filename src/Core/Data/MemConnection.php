<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package    SayGoForms
 * @subpackage Data
 * @author     Cambell Prince <cambell@arketec.com>
 * @link       http://www.arketec.com
 */

/**
 */
require_once (SGF_CORE.'Data/IDataConnection.php');

/**
 *
 */
class MemConnection implements IDataConnection {

	/**
	 * @param string
	 */
	function __construct($dsn) {
	}

	function __destroy() {
	}

	/**
	 * @see IDataConnection::connect()
	 */
	function connect($dsn) {
	}

	/**
	 * @see IDataConnection::disconnect()
	 */
	function disconnect() {
	}

	/**
	 * @see IDataConnection::createMapper()
	 */
	function createMapper($type) {
		return NULL;
	}

}

?>

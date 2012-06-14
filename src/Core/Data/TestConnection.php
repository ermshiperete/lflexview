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
 */
require_once (SGF_CORE.'Data/IDataConnection.php');
require_once (SGF_CORE.'Data/TestListMapper.php');
require_once (SGF_CORE.'Data/TestValueMapper.php');

/**
 *
 */
class TestConnection implements IDataConnection {

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
	public function connect($dsn) {
	}

	/**
	 * @see IDataConnection::disconnect()
	 */
	public function disconnect() {
	}

	/**
	 * @see IDataConnection::createMapper()
	 */
	function createMapper($type) {
		switch ($type) {
			case DataKit::ListMapper:
				return new TestListMapper();
			case DataKit::ValueMapper:
				return new TestValueMapper();
		}
		throw new Exception("Unsupported DataMapper '$type' in '".__CLASS__."'");
	}

}

?>

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
class MySqlConnection implements IDataConnection {

	private $_handle;

	/**
	 * @param string
	 */
	function __construct($dsn) {
		$this->connect($dsn);
	}

	function __destroy() {
		$this->disconnect();
	}

	/**
	 * @see IDataConnection::connect()
	 */
	public function connect($dsn) {
		$info = MySqlConnection::parseDSN($dsn);
		if ($info) {
			$handle = mysqli_connect($info['host'], $info['user'], $info['pass'], $info['database']);
			if (!$handle) {
				$niceDSN = str_replace($info['pass'], '******', $dsn);
				Error::err(__FILE__, __LINE__, "Could not connect to '$niceDSN'");
			} else {
				$this->_handle = $handle;
			}
		}
	}

	/**
	 * @see IDataConnection::disconnect()
	 */
	public function disconnect() {
		if ($this->_handle) {
			mysqli_close($this->_handle);
		}
	}

	/**
	 * @see IDataConnection::createMapper()
	 */
	function createMapper($type) {
		switch ($type) {
			case DataKit::ListMapper:
				require_once (SGF_CORE.'Data/MySqlListMapper.php');
				return new MySqlListMapper($this->_handle);
			case DataKit::ValueMapper:
				require_once (SGF_CORE.'Data/MySqlValueMapper.php');
				return new MySqlValueMapper($this->_handle);
			case DataKit::SessionMapper:
				require_once (SGF_CORE.'Data/MySqlSessionMapper.php');
				return new MySqlSessionMapper($this->_handle);
			case DataKit::BuildMapper:
				require_once (SGF_CORE.'Data/MySqlDataStoreBuilder.php');
				return new MySqlDataStoreBuilder($this->_handle);
		}
		return NULL;
	}


	public static function parseDSN($dsn) {
		$retval = FALSE;
		$matches = array();
		$result = preg_match(
			'/\b((?#protocol)mysql):\/\/((?#user)[^:@\/]+):((?#pass)[^:@\/]+)@((?#host)[^:@\/]+)\/((?#database)[^:@\/]+)/',
		$dsn, $matches
		);
		if ($result == 1) {
			$retval = array();
			$retval['protocol'] = $matches[1];
			$retval['user'] = $matches[2];
			$retval['pass'] = $matches[3];
			$retval['host'] = $matches[4];
			$retval['database'] = $matches[5];
		}
		return $retval;
	}

}

?>

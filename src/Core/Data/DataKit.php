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
 * The DataKit is a factory and cache for store connections.
 * As a factory it creates new connections to stores.
 * As a cache it stores these (against a key) for later use.
 * @package ARK
 * @subpackage Data
 */
class DataKit {

	// Common mappers
	const ListMapper    = 'list';
	const ValueMapper   = 'value';
	const SessionMapper = 'session';
	const BuildMapper   = 'build';

	/**
	 * Return a connection to the store indicated by $cacheKey (it defaults
	 * to 'active'). If no such connection exists a new one is created.
	 * @param string $dsn
	 * @param string $scid [optional]
	 * @return IDataConnection
	 */
	static function connect($dsn, $scid = 'default') {
		// Check cache and create if required.
		if (!isset($GLOBALS['_sk'][$scid])) {
			$connection = self::createConnection($dsn);
			$GLOBALS['_sk'][$scid] = $connection;
		}
		return $GLOBALS['_sk'][$scid];
	}

	/**
	 * Disconnect from the given Store.
	 * @param string $scid [optional]
	 * @return
	 */
	static function disconnect($scid = 'default') {
		unset($GLOBALS['_sk'][$scid]);
	}

	/**
	 * Return the cached IDataConnection
	 * @param string $scid [optional]
	 * @return IDataConnection
	 */
	static function get($scid = 'default') {
		if (!isset($GLOBALS['_sk'][$scid])) {
			throw new Exception("'$scid' not yet connected");
		}
		return $GLOBALS['_sk'][$scid];
	}

	/**
	 * Creates the connection according to the scheme given in $dsn.
	 * @param string $dsn
	 * @return IDataConnection
	 */
	private static function createConnection($uri) {
		$s = split(':', $uri, 2);
		$scheme = $s[0];
		$f = '';
		$c = NULL;
		switch ($scheme) {
			case 'mysql':
				$f = 'Data/MySqlConnection.php';
				$c = 'MySqlConnection';
				break;
			case 'mem':
				$f = 'Data/MemConnection.php';
				$c = 'MemConnection';
				break;
			case 'test':
				$f = 'Data/TestConnection.php';
				$c = 'TestConnection';
				break;

		}
		if (!$c) {
			throw new Exception("Unsupported connection scheme '$scheme'");
		}
		if (!class_exists($c, FALSE)) {
			require_once (SGF_CORE.$f);
		}
		return new $c($uri);
	}

	// TODO refactor to use class for DataProviderFromModel
	static function dataFromModel($path, $className, $scid = 'default') {
		if (!class_exists($className, FALSE)) {
			$filePath = SITE_ROOT."$path/Model/$className".".php";
			require_once ($filePath);
			if (!class_exists($className, FALSE)) {
				throw new Exception("Class '$className' is not defined in '$filePath'");
			}
		}
		return new $className($scid);
	}

	// TODO refactor to use class for DataProviderSchemaFromModel
	static function schemaFromModel($path, $className) {
		if (!class_exists($className, FALSE)) {
			$filePath = SITE_ROOT."$path/Model/$className".".php";
			require_once ($filePath);
			if (!class_exists($className, FALSE)) {
				throw new Exception("Class '$className' is not defined in '$filePath'");
			}
		}
		return new $className();
	}

	static function createMapper($type, $scid = 'default') {
		$connection = self::get($scid);
		return $connection->createMapper($type);
	}
	
	static function providerFromExistingData($existingData) {
		require_once(SGF_CORE.'Data/DataProviderFromData.php');
		return new DataProviderFromData($existingData);
	}
	
	static function providerFromTraversal() {
		require_once(SGF_CORE.'Data/DataProviderFromTraversal.php');
		return new DataProviderFromTraversal();
	}

}

?>

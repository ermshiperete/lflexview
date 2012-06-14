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
require_once (SGF_CORE.'Data/MySqlValueMapper.php');
require_once (SGF_CORE.'Data/schema.php');

/**
 *
 */
class MySqlSessionMapper extends MySqlValueMapper {

	/**
	 * @param mysqli
	 */
	function __construct($handle) {
		parent::__construct($handle);
	}

	/**
	 * @param IDataSpace $data
	 * @return boolean
	 */
	public static function writeData($data) {
		$stateSpace = $data->getSpace('state');
		if ($stateSpace) {
			$d = serialize($stateSpace);
			$data->set('data', $d);
		}
	}

	public static function readData($data) {
		$d = $data->get('data');
		if ($d) {
			$stateSpace = unserialize($d);
		}
		if (!$stateSpace) {
			$stateSpace = new SimpleTreeSpace();
		}
		$data->setSpace('state', $stateSpace);
	}

	/**
	 * Calls writeData to serialize the stateSpace the continues with a normal update.
	 * @see writeData
	 * @see MySqlValueMapper::update()
	 */
	function update($data) {
		self::writeData($data);
		return parent::update($data);
	}

	/**
	 * @see IDataStore::read()
	 */
	function read($data, $schema, $id) {
		$result = parent::read($data, $schema, $id);
		if ($result) {
			self::readData($data);
		}
		return $result;
	}

	/**
	 * Calls writeData to serialize the stateSpace the continues with a normal update.
	 * @see writeData
	 * @see MySqlValueMapper::write()
	 */
	function write($data) {
		self::writeData($data);
		return parent::write($data);
	}

}

?>

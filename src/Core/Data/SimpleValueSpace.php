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
require_once (SGF_CORE.'Data/SimpleListSpace.php');
require_once (SGF_CORE.'Data/DataSpaceBase.php');
require_once (SGF_CORE.'Data/IDataSpace.php');

/**
 * Implements a KeyValue IDataSpace, with no support for Meta data.
 */
class SimpleValueSpace extends DataSpaceBase {

	/**
	 * @var array
	 */
	private $_keyValues;

	/**
	 *
	 */
	function __construct($dataMapper = NULL, $array = NULL) {
		parent::__construct($dataMapper);
		if ($array) {
			$this->import($array);
		} else {
			$this->_keyValues = array();
		}
	}

	/**
	 * Returns an ArrayIterator for the key / values in this DataSpace.
	 * @see IteratorAggregate::getIterator
	 * @return Iterator
	 */
	function getIterator() {
		return new ArrayIterator($this->_keyValues);
	}

	/**
	 * @see IDataSpace::count()
	 */
	function count() {
		return count($this->_keyValues);
	}

	/**
	 * @see IDataSpace::erase()
	 */
	function erase($key) {
		unset($this->_keyValues[$key]);
	}

	/**
	 * @see IDataSpace::eraseAll()
	 */
	function eraseAll() {
		$this->_keyValues = array();
	}

	/**
	 * @see IDataSpace::get()
	 */
	function get($key) {
		return isset($this->_keyValues[$key]) ? $this->_keyValues[$key] : '';
	}

	/**
	 * @see IDataSpace::hasKey()
	 */
	function hasKey($key) {
		return isset($this->_keyValues[$key]);
	}

	/**
	 * @see DataSpaceBase::hasSpace()
	 */
	function hasSpace($name) {
		return ($name == DataSpaceBase::Values) || parent::hasSpace($name);
	}

	/**
	 * @see DataSpaceBase::getSpace()
	 */
	function getSpace($name) {
		if ($name == DataSpaceBase::Values) {
			return $this;
		}
		return parent::getSpace($name);
	}

	/**
	 * @see IDataSpace::set()
	 */
	function set($key, $value) {
		$this->_keyValues[$key] = $value;
	}

	/**
	 * Sets the internal key value array.
	 * Intended to be used by classes that know what they are doing.
	 * @param array $array
	 * @see MySqlValueMapper
	 */
	function import($array) {
		assert(is_array($array));
		$this->_keyValues = $array;
	}

}
?>

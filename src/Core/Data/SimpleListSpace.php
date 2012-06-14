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
require_once (SGF_CORE.'Data/DataSpaceBase.php');

/**
 *
 */
class SimpleListSpace extends DataSpaceBase {

	/**
	 * @var array
	 */
	private $_keySpaces;

	/**
	 * @param IDataStore
	 * @param array
	 */
	function __construct($dataMapper = NULL, $array = array()) {
		parent::__construct($dataMapper);
		$this->_keySpaces = $array;
	}

	/**
	 * Returns an ArrayIterator for the key / values in this DataSpace.
	 * @see IteratorAggregate::getIterator
	 * @return Iterator
	 */
	function getIterator() {
		return new ArrayIterator($this->_keySpaces);
	}

	/**
	 * @see DataSpaceBase::getSpace()
	 */
	function getSpace($name) {
		if (isset($this->_keySpaces[$name])) {
			return $this->_keySpaces[$name];
		} else if ($name == DataSpaceBase::Spaces) {
			return $this;
		}
		return parent::getSpace($name);
	}

	/**
	 * @see DataSpaceBase::hasSpace()
	 */
	function hasSpace($name) {
		return $name == DataSpaceBase::Spaces || isset($this->_keySpaces[$name]) || parent::hasSpace($name);
	}

	/**
	 * @see DataSpaceBase::setSpace()
	 */
	function setSpace($name, $space) {
		if (DataSpaceBase::isDataSpaceBaseName($name)) {
			parent::setSpace($name, $space);
		} else {
			$this->_keySpaces[$name] = $space;
		}
	}

	/**
	 * @see IDataSpace::count()
	 */
	function count() {
		return count($this->_keySpaces);
	}

	/**
	 * @see IDataSpace::erase()
	 */
	function erase($key) {
		unset($this->_keySpaces[$key]);
	}

	/**
	 * @see IDataSpace::eraseAll()
	 */
	function eraseAll() {
		$this->_keySpaces = array();
	}

}

?>

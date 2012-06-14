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
require_once (SGF_CORE.'Data/SimpleValueSpace.php');

/**
 * Imlements an KeyValue IDataSpace without Meta data.
 */
class SimpleTreeSpace extends SimpleValueSpace {

	private $_spaces;

	/**
	 *
	 */
	function __construct($valueMapper = NULL, $array = array()) {
		parent::__construct($valueMapper, $array);
		$this->_spaces = new SimpleListSpace();
	}

	/**
	 * @see SimpleValueSpace::getSpace()
	 */
	function getSpace($name) {
		$result = parent::getSpace($name);
		if (!$result) {
			$result = $this->_spaces->getSpace($name);
		}
		return $result;
	}

	/**
	 * @see SimpleValueSpace::hasSpace()
	 */
	function hasSpace($name) {
		if ($name == DataSpaceBase::Spaces) {
			return TRUE;
		}
		$result = parent::hasSpace($name);
		if (!$result) {
			$result = $this->_spaces->hasSpace($name);
		}
		return $result;
	}

	/**
	 * @see DataSpaceBase::setSpace()
	 */
	function setSpace($name, $space) {
		if (parent::hasSpace($name)) {
			parent::setSpace($name, $space);
		} else {
			$this->_spaces->setSpace($name, $space);
		}
	}

}

?>

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
require_once (SGF_CORE.'Data/SimpleTreeSpace.php');

/**
 * Imlements an KeyValue IDataSpace with Meta data.
 */
class TreeSpace extends SimpleTreeSpace {

	/**
	 *
	 */
	function __construct($valueMapper = NULL, $array = array()) {
		parent::__construct($valueMapper, $array);
		$this->_metaSpaces = array();
		// TODO this can lazy create in DataSpaceBase
		$this->_metaSpaces[DataSpaceBase::Meta] = new SimpleValueSpace();
	}

}

?>

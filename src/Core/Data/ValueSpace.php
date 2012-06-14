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
require_once (SGF_CORE.'Data/SimpleValueSpace.php');

/**
 * Imlements an KeyValue IDataSpace with Meta data.
 */
class ValueSpace extends SimpleValueSpace {

	/**
	 *
	 */
	function __construct($dataMapper, $array = array()) {
		parent::__construct($dataMapper, $array);
		$this->_metaSpaces = array();
		// TODO this can lazy create in DataSpaceBase
		$this->_metaSpaces[DataSpaceBase::Meta] = new SimpleValueSpace();
	}

}
?>

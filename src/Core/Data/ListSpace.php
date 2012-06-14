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
 * Adds Meta data to the SimpleKeySpaceSpace
 */
class ListSpace extends SimpleListSpace {

	/**
	 *
	 */
	function __construct($dataMapper, $array = array()) {
		parent::__construct($dataMapper, $array);
		$this->_metaSpaces = array();
		$this->_metaSpaces[DataSpaceBase::Meta] = new SimpleValueSpace();
	}

}

?>

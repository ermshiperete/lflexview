<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package    ARK
 * @subpackage Parts
 * @author     Cambell Prince <cambell@arketec.com>
 * @link       http://www.arketec.com
 */

/**
 */
require_once (SGF_CORE.'data/schema.php');

/**
 *
 */
class FileContentSchema extends Schema {

	/**
	 *
	 */
	function __construct() {
		parent::__construct();
		$this->setField('title', DT_Text, 'Title', SC_Valid_Required, SC_Sort_None);
		$this->setField('created_by', DT_Text, 'Created By', SC_Valid_NotRequired, SC_Sort_None);
		$this->setField('modified_by', DT_Text, 'Modified By', SC_Valid_NotRequired, SC_Sort_None);
		$this->setField('created', DT_DateTime, 'Created', SC_Valid_NotRequired, SC_Sort_None);
		$this->setField('modified', DT_DateTime, 'Modified', SC_Valid_NotRequired, SC_Sort_None);
	}

}

?>

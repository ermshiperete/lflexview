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
require_once (SGF_CORE.'Data/schema.php');

/**
 * SessionSchema
 */
class SessionSchema extends Schema {

	function __construct() {
		parent::__construct();
		$this->set(SC_Meta_Primary, 'sk');

		$this->setField('sk', DT_Text, 'Session Key', SC_Valid_Required);
		$this->setField('uc', DT_ID, 'User ID', SC_Valid_Required);
		$this->setField('priv', DT_Number, 'Privilege', SC_Valid_NotRequired);
		$this->setField('ip', DT_Text, 'IP', SC_Valid_NotRequired);
		$this->setField('browser', DT_Text, 'Browser', SC_Valid_NotRequired);
		$this->setField('story', DT_Text, 'Story', SC_Valid_NotRequired);
		$this->setField('dtc', DT_DateTime, 'Date Created', SC_Valid_Required);
		$this->setField('dtm', DT_DateTime, 'Date Modified', SC_Valid_Required);
		$this->setField('data', DT_Text, 'data', SC_Valid_NotRequired);
	}
}
?>
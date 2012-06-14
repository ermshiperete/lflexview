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
require_once (SGF_CORE.'Data/SimpleValueSpace.php');
require_once (SGF_CORE.'Data/SimpleListSpace.php');

/**
 * Data type constants.
 * @see Schema::setField
 */
define('DT_Unknown', '?');
define('DT_PID', 'pid');
define('DT_ID', 'id');
define('DT_Hidden', 'hidden');
define('DT_Number', 'number');
define('DT_Currency', 'currency');
define('DT_Date', 'date');
define('DT_Time', 'time');
define('DT_DateTime', 'datetime');
define('DT_Text', 'text');
define('DT_Email', 'email');
define('DT_URL', 'url');
define('DT_Phone', 'phone');
define('DT_Password', 'password');
define('DT_Action', 'action');
define('DT_Memo', 'memo');

/**
 * Required constants
 * @see Schema::setField
 */
define('SC_Valid_NotRequired', 0);
define('SC_Valid_Required', 1);

/**
 * Sorted constants
 * @see Schema::setField
 */
define('SC_Sort_None', 0);
define('SC_Sort_Asc', 1);
define('SC_Sort_Desc', 2);

/**
 * Schema Meta Keys
 */
define('SC_Meta_ID', 'id');
define('SC_Meta_Source', 'src');
define('SC_Meta_Query', 'query');
define('SC_Meta_Primary', 'primary');

/**
 * Schema Field Meta Keys
 */
define('FLD_Type', 'type');
define('FLD_Name', 'name'); // or id
define('FLD_Label', 'label');
define('FLD_Required', 'reqd');
define('FLD_Sort', 'sort'); // TODO: deprecate this. can express sort differently, it refs > 1 field
define('FLD_Format', 'format');

/**
 * Schema represents the prescription for a DataSpace
 * @package		ARK
 * @subpackage	Data
 * @see http://arketec.com
 * @see DataSpace
 * @access public
 */
class Schema extends SimpleListSpace {
	// TODO Maybe extending SimpleTreeSpace would be better

	private $_keyValues;

	function __construct() {
		parent::__construct();
		$this->_keyValues = array();
		$this->_metaSpaces = array();
		$this->_metaSpaces[DataSpaceBase::Meta] = new SimpleValueSpace();
		$this->setMeta(SC_Meta_ID, '0');
		$this->setMeta(SC_Meta_Source, '');
		$this->setMeta(SC_Meta_Primary, '');
	}

	/**
	 * @see DataSpaceBase::get()
	 */
	function get($key) {
		return $this->_keyValues[$key];
	}

	/**
	 * @see DataSpaceBase::set()
	 */
	function set($key, $value) {
		$this->_keyValues[$key] = $value;
	}


	/**
	 * @see DataSpaceBase::setValueInSpace()
	 */
	function setValueInSpace($name, $key, $value) {
		if (!$this->hasSpace($name)) {
			$this->setSpace($name, new SimpleValueSpace());
		}
		parent::setValueInSpace($name, $key, $value);
	}

	/**
	 * Sets the initial values for the field $name
	 * @param string
	 * @param string
	 * @param string
	 * @param integer SCH_IsRequired or SC_Valid_NotRequired
	 * @param integer SCH_NotSorted, SC_Sort_Asc, or SC_Sort_Desc
	 * @todo make this a separate createSchema sort of function, more general case fit with sgfobj better.
	 */
	function setField($name, $type, $label, $isRequired, $sort = NULL, $fmt = null) {
		// TODO change $fmt to a renderer and remove $sort
		$a = array(
		FLD_Name => $name,
		FLD_Type => $type,
		FLD_Label => $label,
		FLD_Required => $isRequired,
		FLD_Sort => $sort,
		FLD_Format=>$fmt
		);
		$fieldSpace = new SimpleValueSpace(NULL, $a);
		$this->setSpace($name, $fieldSpace);
	}

}

?>

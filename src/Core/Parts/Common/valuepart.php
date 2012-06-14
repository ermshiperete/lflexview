<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package		ARK
 * @subpackage	Parts
 * @version    $Id: valuepart.php,v 1.1.1.1 2006/04/13 01:11:02 cambell Exp $
 * @author		  Cambell Prince <cambell@arketec.com>
 * @link				http://www.arketec.com
 */

/**
 */
require_once(SGF_CORE . 'Controller/part.php');
require_once(SGF_CORE . 'Data/arrayiterator.php');

/**
 * Value is a Part that pushes key value pairs into the View
 * @package		ARK
 * @subpackage	Parts
 */
class ValuePart extends Part {
	/**
	 * @var array
	 */
	var $values_;

	/**
	 * Constructor
	 * @param string
	 * @param View
	 * @param array
	 */
	function ValuePart($name, $view, $position, $values) {
		$this->Part($name, $view, $position);
		$this->values_ = $values;
	}

	/**
	 * Handles the onRender event.
	 * Renders the key value pairs from the array
	 * @param Event
	 * @param Traversal
	 * @return boolean Returns true always
	 */
	function onRender($e, $t) {
		$v = $t->viewGet();
		$it = new ArrayIterator($this->values_);
		for ($it->rewind(); $it->isValid(); $it->next()) {
			$v->pushText($it->key(), $it->current());
		}
		parent::onRender($e, $t);

		return true;
	}

}

?>

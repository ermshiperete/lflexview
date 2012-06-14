<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package		ARK
 * @subpackage	Parts
 * @version    $Id: filter.php,v 1.1.1.1 2006/04/13 01:11:01 cambell Exp $
 * @author		  Cambell Prince <cambell@arketec.com>
 * @link				http://www.arketec.com
 */

/**
 */
require_once(SGF_CORE . 'Controller/part.php');
require_once(SGF_CORE . 'Data/accessor.php');

/**
 * @package		ARK
 * @subpackage	Parts
 */
class Filter extends Deck {
	var $where_;

	/**
	 * Constructor
	 * @param string
	 * @param View
	 * @param array Keys
	 */
	function Filter($name) {
		$this->Deck($name);
		$this->where_ = null;
	}

	/**
	 * Sets the sql where
	 * @param string
	 * @todo May need to post an event to cause us to update our $where in the state with traversal context
	 */
	function setWhere($where) {
		$this->where_ = $where;
	}

	/**
	 * handle
	 * Sets the 'where' as a data string in the traversal.
	 * - key = 'filter'
	 * - value = where
	 * @param Event
	 * @param Traversal
	 */
	function handle($e, $t) {
		$where = $t->stateGet('where');
		if ($where != $this->where_) {
			if ($this->where_) {
				$where = $this->where_;
				$t->stateSet('where', $where);
			}
		}
		if ($where) {
			$t->dataSet('filter', $where);
		}
		// render children (we may have a form as a child for example which is the ui for the filter)
		parent::handle($e, $t);
	}

}

?>

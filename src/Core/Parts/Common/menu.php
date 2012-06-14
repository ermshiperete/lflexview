<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package		ARK
 * @subpackage	Parts
 * @version    $Id: menu.php,v 1.1.1.1 2006/04/13 01:11:02 cambell Exp $
 * @author		  Cambell Prince <cambell@arketec.com>
 * @link				http://www.arketec.com
 */

/**
 */
require_once(SGF_CORE . 'Controller/part.php');

/**
 * @package		ARK
 * @subpackage	Parts
 * Menu sets {ISOPEN} and only draws its children if ISOPEN is true.
 */
class Menu extends ActionPart {
	/**
	 * @var string
	 */
	var $action_;

	/**
	 * Constructor
	 * @param string
	 * @param string
	 * @param string
	 * @param ActionPath
	 * @param string
	 * @param string
	 * @param string
	 */
	function MenuItem($name, $view, $position, $actionPath, $label, $action, $image = null) {
		$this->ActionPart($name, $view, $position, $actionPath, $label, $image);
		$this->action_ = $action;
	}

	/**
	 * Handles the onRender event
	 * Calls parent::onRender and pushes the following variables into the view
	 *   - {ISOPEN} true of the name matches the current traversal
	 * @param Event
	 * @param Traversal
	 */
	function onRender($e, $t) {
		parent::onRender($e, $t);
		$v = $t->viewGet();
		$nextActionName = $t->getNextActionName();
		if ($this->action_ == $nextActionName) {
			$v->pushText('ISOPEN', '1');
		} else {
			$v->pushText('ISOPEN', '0');
		}
	}

}

?>

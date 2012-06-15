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
require_once(SGF_CORE . 'Controller/Control.php');

/**
 * A ButtonControl
 * @package		ARK
 * @subpackage	Parts
 */
class ButtonControl extends Control {
	/**
	 * Constructor
	 */
	function __construct($name, $viewProvider, $position, $form, $id, $label) {
		parent::__construct($name, $viewProvider, $position, $form, $id, $label);
	}

	/**
	 * Handles the onRender event
	 * @param Event
	 * @param Traversal
	 */
	function onRender($e, $t) {
		$v = $t->viewGet();
		$v->pushText('Name', $this->getName());
		$v->pushText('Title', $this->getLabel());
		return true;
	}

}

?>

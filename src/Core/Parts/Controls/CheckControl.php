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
require_once(SGF_CORE . 'Controller/control.php');

/**
 * A CheckControl
 * This pushes TITLE, REQD, and BODY
 * @package		ARK
 * @subpackage	Parts
 */
class CheckControl extends Control {

	/**
	 * Constructor
	 * @param string
	 * @param View
	 * @param Form
	 * @param integer
	 * @param string
	 * @param integer
	 */
	function __construct($name, $viewProvider, $position, &$form, $id, $label) {
		parent::__construct($name, $viewProvider, $position, $form, $id, $label);
	}

	/**
	 * Handles the onRender event
	 * Pushes Label Reqd into the view. Also pushes the HTML input tag as BODY into the view.
	 * @param Event
	 * @param Traversal
	 */
	function onRender($e, $t) {
		$v = $t->viewGet();
		$v->pushText('Label', $this->getLabel());
		$v->pushText('Reqd', $this->isRequired());
		$value = $this->getValue($t);
		$checked = $value === 1 ? 'checked' : '';
		$body = '<input type="checkbox" name="' . $this->getName() . '" value="1" ' . $checked . '" />';
		$v->pushText('Body', $body);
		return true;
	}

}

?>
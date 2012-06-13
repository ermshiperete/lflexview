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
 * A TextControl
 * This pushes TITLE, REQD, and BODY
 * @package		ARK
 * @subpackage	Parts
 */
class TextControl extends Control {

	var $_size;

	/**
	 * Constructor
	 * @param string
	 * @param View
	 * @param Form
	 * @param integer
	 * @param string
	 * @param integer
	 */
	function __construct($name, $viewProvider, $position, &$form, $id, $label, $size) {
		parent::__construct($name, $viewProvider, $position, $form, $id, $label);
		$this->_size = $size;
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
		$body = '<input type="text" name="' . $this->getName() . '" size="' . $this->_size . '" value="' . $value . '" />';
		$v->pushText('Body', $body);
		return true;
	}

}

/**
 * A TextArea
 * @package		ARK
 * @subpackage	Parts
 */
class TextArea extends Control {

	var $_rows;
	var $_cols;

	/**
	 * Constructor
	 * @param string
	 * @param View
	 * @param Form
	 * @param integer
	 * @param string
	 * @param integer
	 * @param integer
	 */
	function __construct($name, $viewProvider, $position, &$form, $id, $label, $rows, $cols) {
		parent::__construct($name, $viewProvider, $position, $form, $id, $label);
		$this->_rows = $rows;
		$this->_cols = $cols;
	}

	/**
	 * Handles the onRender event
	 * Pushes TITLE REQD into the view. Also pushes the HTML input tag as BODY into the view.
	 * @param Event
	 * @param Traversal
	 */
	function onRender($e, $t) {
		$v = $t->viewGet();
		$v->pushText('Label', $this->getLabel());
		$v->pushText('Reqd', $this->isRequired());
		$value = $this->getValue($t);
		$body = '<textarea name="' . $this->getName() . '" rows="' . $this->_rows . '" cols="' . $this->_cols . '">' . $value . '</textarea>';
		$v->pushText('Body', $body);
		return true;
	}

}

/**
 * A PasswordControl
 * @package		ARK
 * @subpackage	Parts
 */
class PasswordControl extends Control {

	var $_size;

	/**
	 * Constructor
	 * @param string
	 * @param View
	 * @param Form
	 * @param integer
	 * @param string
	 * @param integer
	 */
	function __construct($name, $viewProvider, $position, &$form, $id, $label, $size) {
		parent::__construct($name, $viewProvider, $position, $form, $id, $label);
		$this->_size = $size;
	}

	/**
	 * Handles the onRender event
	 * Pushes TITLE REQD into the view. Also pushes the HTML input tag as BODY into the view.
	 * @param Event
	 * @param Traversal
	 */
	function onRender($e, $t) {
		$v = $t->viewGet();
		$v->pushText('Title', $this->getLabel());
		$v->pushText('Reqd', $this->isRequired());
		$value = $this->getValue($t);
		$body = '<input type="password" name="' . $this->_name . '" size="' . $this->_size . '" value="' . $value . '" />';
		$v->pushText('Body', $body);
		return true;
	}

}

?>

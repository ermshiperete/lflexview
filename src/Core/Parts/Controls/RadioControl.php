<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package    SayGoForms
 * @subpackage Parts
 * @author     Cambell Prince <cambell@arketec.com>
 * @link       http://www.arketec.com
 */

/**
 */
require_once (SGF_CORE.'Controller/control.php');
require_once (SGF_CORE.'Data/IDataProvider.php');

/**
 * A Radio Button Control
 * @package		ARK
 * @subpackage	Parts
 */
class RadioControl extends Control {

	/**
	 * @var IDataProvider
	 */
	private $_selectProvider;

	/**
	 * Constructor
	 * @param string
	 * @param View
	 * @param Form
	 * @param integer
	 * @param string
	 * @param DataSpace
	 */
	function __construct($name, $viewProvider, $position, $form, $id, $label, $selectProvider) {
		parent::__construct($name, $viewProvider, $position, $form, $id, $label);
		$this->_selectProvider = $selectProvider;
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
		$select = $this->_selectProvider->provideDataAndRead($t);
		assert($select);
		$selected = $this->getValue($t);
		$body = '';
		foreach ($select as $key => $value) {
			if ($selected == $key) {
				$checked = 'checked';
			} else {
				$checked = '';
			}
			$body .= "<label><input type=\"radio\" name=\"$this->_name\" value=\"$key\" $checked />$value</label><br/>";
			//debug('t', $body);
		}
		$v->pushText('Body', $body);
		return true;
	}

}


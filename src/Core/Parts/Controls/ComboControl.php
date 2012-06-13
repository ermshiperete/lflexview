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
require_once (SGF_CORE.'data/IDataProvider.php');

/**
 * A ComboBox Control
 * @package		ARK
 * @subpackage	Parts
 */
class ComboControl extends Control {

	/**
	 * @var IDataProvider
	 */
	private $_selectProvider;

	const SizeToSelect = 1;
	const SizeSet      = 2;

	/**
	 * @var integer
	 */
	public $SizeMode;

	/**
	 * @var integer
	 */
	public $Size;

	/**
	 * Constructor
	 * @param string
	 * @param IViewProvider
	 * @param string
	 * @param Form
	 * @param integer
	 * @param string
	 * @param IDataSpaceProvider
	 */
	function __construct($name, $viewProvider, $position, $form, $id, $label, $selectProvider) {
		parent::__construct($name, $viewProvider, $position, $form, $id, $label);
		$this->_selectProvider = $selectProvider;
		$this->SizeMode = self::SizeToSelect;
		$this->Size = 1;
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
		if ($this->SizeMode == self::SizeToSelect) {
			$this->Size = $select->count();
		}
		$body = '<select name="'.$this->_name.'" size="'.$this->Size.'">';
		$selected = $this->getValue($t);
		foreach ($select as $key => $value) {
			$body .= '<option value="'.$key.'"';
			if ($key == $selected) {
				$body .= ' selected="true"';
			}
			$body .= '>'.$value.'</option>';
		}
		$body .= '</select>';
		$v->pushText('Body', $body);
		return true;
	}

}


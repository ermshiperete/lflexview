<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package		ARK
 * @subpackage	Parts
 * @version    $Id: form.php,v 1.1.1.1 2006/04/13 01:11:02 cambell Exp $
 * @author		  Cambell Prince <cambell@arketec.com>
 * @link				http://www.arketec.com
 */

/**
 */
require_once(SGF_CORE . 'Parts/Std/Form.php');

/**
 * JQForm is an jQuery implementations of a web Form.
 * It is based on the standard php Form part in parts/Std/Form.php
 * @see Form
 * @package		ARK
 * @subpackage	Parts
 */
class JQForm extends Form {
	/**
	 * Constructor
	 * @param string
	 * @param string
	 * @param string
	 * @param DataSpace
	 */
	function JQForm($name, $view, $position, $ioSpace = null) {
		$this->Form($name, $view, $position, $ioSpace);
	}


	/**
	 * Handles the onAction event.
	 * The Form is authoritative for all child Controls. Any onAction handlers for Controls will not be
	 * called.
	 * @param Event
	 * @param Traversal
	 * @return boolean Returns true if the event is handled.
	 */
	function onAction($e, $t) {
		$ret = false;
		$a = $t->getCommand();
		$op = $a->get(0);
		$primary = $a->get(1);
		// get args
		switch ($op) {
			case 'write':
				//!!! changed to kick off onData event				$this->import($_POST);
				$dataEvent = new Event(EVT_Data, '');
				$this->onData($dataEvent, $t);
				// Check for the default button
				if ($this->dataSpace_->hasKey('default')) {
					$this->dataSpace_->set($this->defaultControl_, 'default');
				}
				// Check for cancel button
				if ($this->dataSpace_->hasKey('cancel')) {
					$this->dataSpace_->eraseAll();
					$this->runCancel($e, $t);
					// Check for save button
				} else if ($this->dataSpace_->hasKey('save')) {
					// check with validator
					if ($this->validator_) {
						if ($this->validator_->checkValid($this->dataSpace_)) {
							$this->write($primary);
							$this->runSave($e, $t);
						} else {
							$this->actError($e, $t);
						}
					} else {
						// No validator so write and save anyway
						$this->write($primary);
						$this->runSave($e, $t);
					}
				}
				break;
		}
		return $ret;
	}

	/**
	 * Handles the onRender event.
	 * Renders the following template variable
	 * - TITLE A title for the form
	 * - ACTION The action url to submit to
	 * - DEFAULT A default submit button with style display:none
	 * - FOCUS A javascript
	 * - NAME The name of the form (referenced in the focus script)
	 * @param Event
	 * @param Traversal
	 * @return boolean Returns true always
	 */
	function onRender($e, $t) {
		$op = '';
		$primary = '';
		$a0 = $t->getCommand();
		if ($a0) {
			$op = $a0->get(0);
			$primary = $a0->get(1);
		}
		if ($op != 'write') {
			$this->read($primary);
		}
		$v = $this->_view;
		$a1 = new Action($this->_name . ':write:' . $primary);
		$v->pushText('TITLE', $this->title_);

		$v->pushText('NAME', $this->_name);

		$url = $t->urlFromCommand($a1);
		$v->pushText('ACTION', $url);

		$def = '<input type="submit" name="default" value="" style="display: none;" />';
		$v->pushText('DEFAULT', $def);

		if ($this->focus_) {
			$focus = "<script>document.$this->_name.$this->focus_.focus();</script>";
			$v->pushText('FOCUS', $focus);
		}

		parent::onRender($e, $t);

		return true;
	}

}

?>

<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package		ARK
 * @subpackage	Parts
 * @version    $Id: validatorcontrol.php,v 1.1.1.1 2006/04/13 01:11:02 cambell Exp $
 * @author		  Cambell Prince <cambell@arketec.com>
 * @link				http://www.arketec.com
 */

/**
 */
require_once(SGF_CORE . 'Data/arrayiterator.php');
require_once(SGF_CORE . 'Controller/control.php');

/**
 * ValidatorErrorControl
 * @package		ARK
 * @subpackage	Parts
 */
class ValidatorErrorControl extends Control {
	/**
	 * Constructor
	 */
	function __construct($name, $viewProvider, $position, &$form) {
		parent::__construct($name, $viewProvider, $position, $form, null, null);
	}

	/**
	 * Handles the onRender event
	 * @param Event
	 * @param Traversal
	 */
	function onRender($e, $t) {
		$v = $t->viewGet();
		$validator = $this->form_->getValidator();
		if ($validator) {
			if (!$validator->isValid()) {
				$reasons = array();
				$errors = $validator->getErrors();
				$it1 = new ArrayIterator($errors);
				// TODO fix this up for foreach with native iterators
				for ($it1->rewind(); $it1->isValid(); $it1->next()) {
					$controlErrors = $it1->current();
					$it2 = new ArrayIterator($controlErrors);
					for ($it2->rewind(); $it2->isValid(); $it2->next()) {
						$s = $it2->current();
						$reasons[] = $s;
					}
				}
				$v->pushText('BODY', $reasons, 'validatoritem');
				$v->renderToParent($t, $this->_position);
			}
		}
		return true;
	}

	/**
	 * Do nothing as we have rendered the View in onRender
	 * @param Traversal
	 */
	function onRenderLeave(&$t) {
	}

}

?>

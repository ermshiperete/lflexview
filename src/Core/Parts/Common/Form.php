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
require_once (SGF_CORE.'Controller/ActionPath.php');
require_once (SGF_CORE.'Controller/Command.php');
require_once (SGF_CORE.'Controller/Part.php');
require_once (SGF_CORE.'Data/IDataSpace.php');

/**
 * Form is an implementations of a web Form.
 * @todo 2 ways of doing forms
 * 1) push all vars first for display by a custom template
 * 2) push each control and parse template via a generic control template for each control.
 * @package		ARK
 * @subpackage	Parts
 */
class Form extends Part {
	/**
	 * @var Validator
	 */
	private $_validator;

	/**
	 * @var IDataProvider
	 */
	private $_dataProvider;

	/**
	 * The local copy of form data.
	 * @var IDataSpace
	 */
	protected $_dataSpace;

	/**
	 * @var Task
	 */
	private $_saveTask;

	/**
	 * @var Task
	 */
	private $_cancelTask;

	/**
	 * @var string
	 */
	private $_title;

	/**
	 * @var string
	 */
	private $_defaultControl;

	/**
	 * @var string
	 */
	private $_focus;

	/**
	 * Constructor
	 * @param string
	 * @param View
	 * @param DataSpace
	 */
	function __construct($name, $viewProvider, $position, $dataProvider) {
		parent::__construct($name, $viewProvider, $position);
		$this->_dataProvider = $dataProvider;
		$this->_title = $name; // TODO why it the title = name?
		$this->_defaultControl = 'save';
		$this->_dataSpace = NULL;;
		$this->_focus = NULL;
		$this->_validator = NULL;
	}

	/**
	 * Sets the forms title.
	 * @param string
	 */
	function setTitle($title) {
		$this->_title = $title;
	}

	/**
	 * Sets the name of the default submit control
	 * @param string
	 * @see onRender
	 */
	function setDefault($name) {
		$this->_defaultControl = $name;
	}

	/**
	 * Sets the focus to the control $name
	 * @param string
	 * @see onRender
	 */
	function setFocus($name) {
		$this->_focus = $name;
	}

	/**
	 * Sets the forms onSaveActionPath
	 * This actionpath is posted as an EVT_Action event after a successful save
	 * @param ActionPath
	 */
	function setSaveTask($action) {
		$this->_saveTask = $action;
	}

	/**
	 * Sets the forms onCancelActionPath
	 * This actionpath is posted as an EVT_Action event when the form is cancelled
	 * @param ActionPath
	 */
	function setCancelTask($action) {
		$this->_cancelTask = $action;
	}

	/**
	 * Sets the Validator
	 * @param Validator
	 */
	function setValidator($validator) {
		$this->_validator = $validator;
	}

	/**
	 * Gets the Validator
	 * @return Validator May return null
	 */
	function getValidator() {
		return $this->_validator;
	}

	/**
	 * Executes the cancel action
	 * Called from onAction during write if the cancel button is pressed
	 * @param Event
	 * @param Traversal
	 * @see onAction
	 */
	function runCancel($e, $t) {
		if ($this->_cancelTask) {
			//            $this->_cancelTask->setParameter($this->_ioSpace); // TODO review Form::runCancel
			$this->_cancelTask->run();
		}
	}

	/**
	 * Executes the save action
	 * Called from onAction during write if the save button is pressed and the form is valid
	 * @param Event
	 * @param Traversal
	 * @see onAction
	 */
	function runSave($e, $t) {
		if ($this->_saveTask) {
			//            $this->_saveTask->setParameter($this->_ioSpace); //TODO review Form::runSave
			$this->_saveTask->run();
		}
	}

	/**
	 * Executes the error action
	 * Called from onAction during write if the form is not valid
	 * @param Event
	 * @param Traversal
	 * @see onAction
	 */
	function actError($e, $t) {
		// Render this Form, which will show the error condition
		$eq = EventQueue::singleton();
		$eq->addEvent( new Event(EVT_Render, $e->getActionPath()));
	}

	/**
	 * Traverses the child controls with a EVT_Data.
	 * @param Traversal $t
	 * @return void
	 */
	private function processDataFromControls($t) {
		assert($this->_dataSpace == null);
		$t->dataEnter();
		$this->_dataSpace = $this->_dataProvider->provideData($t);
		assert(is_a($this->_dataSpace, 'IDataSpace'));
		$t->dataSet($this->_dataSpace);
		$dataEvent = new Event(EVT_Data, '');
		$this->onData($dataEvent, $t);
		$t->dataLeave();
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
		$ret = FALSE;
		$command = $t->getCommand();
		$primary = count($command->args) > 0 ? $command->args[0] : NULL;
		// get args
		switch ($command->name) {
			case 'write':
				$this->processDataFromControls($t);
				// Check for the default button
				if ($this->_dataSpace->hasKey('default')) {
					$this->_dataSpace->set($this->_defaultControl, 'default');
				}
				// Check for cancel button
				if ($this->_dataSpace->hasKey('cancel')) {
					$this->_dataSpace->eraseAll();
					$this->runCancel($e, $t);
					// Check for save button
				} else if ($this->_dataSpace->hasKey('save')) {
					// check with validator
					if ($this->_validator) {
						if ($this->_validator->checkValid($this->_dataSpace)) {
							$this->_dataSpace->write($primary);
							$this->runSave($e, $t);
						} else {
							$this->actError($e, $t);
						}
					} else {
						// No validator so write and save anyway
						$this->_dataSpace->write($primary);
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
	 * - Title A title for the form
	 * - Action The action url to submit to
	 * - Default A default submit button with style display:none
	 * - Focus A javascript
	 * - Name The name of the form (referenced in the focus script)
	 * @param Event
	 * @param Traversal
	 * @return boolean Returns true always // @todo we have a return???
	 */
	function onRender($e, $t) {
		if (!$this->_dataSpace) {
			$this->_dataSpace = $this->_dataProvider->provideData($t);
		}
		/* TODO Move this form dataspace render reading code into say a UrlDataProvider
		 $op = '';
		 $primary = '';
		 $a0 = $t->getCommand();
		 if ($a0) {
		 $op = $a0->get(0);
		 $primary = $a0->get(1);
		 }
		 */
		$t->dataSet($this->_dataSpace);

		$v = $this->_view;
		$command = new Command('write'/* TODO .$primary*/);

		$v->pushText('Title', $this->_title);
		$v->pushText('Name', $this->_name);

		//        $url = '';
		$url = $t->urlFromCommand($command);
		$v->pushText('Action', $url);

		$def = '<input type="submit" name="default" value="" style="display: none;" />';
		$v->pushText('Default', $def);

		if ($this->_focus) {
			$focus = "<script>document.$this->_name.$this->_focus.focus();</script>";
			$v->pushText('Focus', $focus);
		}

		parent::onRender($e, $t);

		return true;
	}

	/**
	 * @see Part::onRenderEnter()
	 */
	function onRenderEnter($t) {
		parent::onRenderEnter($t);
		$t->dataEnter();
	}

	/**
	 * @see Part::onRenderLeave()
	 */
	function onRenderLeave($t) {
		$t->dataLeave();
		parent::onRenderLeave($t);
	}

}

?>

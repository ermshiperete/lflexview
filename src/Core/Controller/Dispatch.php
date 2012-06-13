<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package    ARK
 * @subpackage Controller
 * @author     Cambell Prince <cambell@arketec.com>
 * @link       http://www.arketec.com
 */

/**
 * An interface of the dispatch pattern.  For a given event, Dispatchers finds a suitable Dispatchable to
 * handle the event.
 * @package		ARK
 * @subpackage	Controller
 */
class Dispatchable {
	protected $_name;

	/**
	 * Constructs this Dispatchable and sets the name to $name.
	 * @param string The name of this dispatchable
	 * @see Traversal::enter
	 */
	function __construct($name) {
		$this->_name = $name;
	}

	/**
	 * Returns the name of this Dispatchable
	 * @return string
	 */
	function getName() {
		return $this->_name;
	}

	/**
	 * canHandle would commonly be implemented in terms of the dispatchable's action name being equal
	 * to the traversals current ActionPath name.  This default implementation simply returns False.
	 * @param Event
	 * @param Traversal
	 * @return boolean False always in this default implementation
	 */
	function canHandle($e, $t) {
		return False;
	}

	/**
	 * Handles the event by calling the on... event handlers.
	 * @param Event
	 * @param Traversal
	 * @return boolean Returns True if the event is handled.
	 */
	function handle($e, $t) {
		$ret = False;
		switch ($e->getType()) {
			case EVT_Action:
				$ret = $this->onAction($e, $t);
				break;

			case EVT_Data:
				$ret = $this->onData($e, $t);
				break;

			case EVT_Render:
				$this->onRenderEnter($t);
				$ret = $this->onRender($e, $t);
				$this->onRenderLeave($t);
				break;

			case EVT_AjaxAction:
				$ret = $this->onAjaxAction($e, $t);
				break;

			case EVT_AjaxRender:
				$ret = $this->onAjaxRender($e, $t);
				break;

		}
		return $ret;
	}

	/**
	 * The default handler for action events.
	 * @param Event
	 * @param Traversal
	 * @return boolean Returns False always in this default implementation
	 */
	function onAction($e, $t) {
		return False;
	}

	/**
	 * The default handler for ajax events.
	 * @param Event
	 * @param Traversal
	 * @return boolean Returns False always in this default implementation
	 */
	function onAjaxAction($e, $t) {
		return False;
	}

	/**
	 * The default handler for ajax events.
	 * @param Event
	 * @param Traversal
	 * @return boolean Returns False always in this default implementation
	 */
	function onAjaxRender($e, $t) {
		return False;
	}

	/**
	 * The default handler for render events.
	 * @param Event
	 * @param Traversal
	 * @return boolean Returns False always in this default implementation
	 */
	function onRender($e, $t) {
		return False;
	}

	/**
	 * The default handler for data events.
	 * @param Event
	 * @param Traversal
	 * @return boolean Returns False always in this default implementation
	 */
	function onData($e, $t) {
		return False;
	}

	/**
	 * The default handler for onEnter.
	 * This is called by handle prior to onRender.
	 * @param Traversal
	 */
	function onRenderEnter($t) {
	}

	/**
	 * The default handler for onLeave.
	 * This is called by handle after onRender.
	 * @param Traversal
	 */
	function onRenderLeave($t) {
	}

}
;

/**
 * A decorated Dispatchable
 * All Dispatchable functions are delegated,
 * @package		ARK
 * @subpackage	Controller
 */
class DispatchableDecorator extends Dispatchable {
	/**
	 * The decorated Dispatchable
	 * @var Dispatchable
	 */
	var $_d;

	/**
	 * Constructs this Dispatchable and sets the name to $name.
	 * @param string The name of this dispatchable
	 * @see Traversal::enter
	 */
	function __construct($name, $dispatchable) {
		parent::__construct($name);
		$this->_d = $dispatchable;
	}

	/**
	 * Returns the name of the decorated Dispatchable
	 * Calls the parent implementation NOT the delegated implementation. They should be the same.
	 * @return string
	 */
	function getName() {
		return parent::getName();
	}

	/**
	 * canHandle returns True if this dispatchable can handle the event $e.
	 * This returns True if the decorated dispatchable can handle the event $e.
	 * @param Event
	 * @param Traversal
	 * @return boolean False always in this default implementation
	 */
	function canHandle($e, $t) {
		return $this->_d->canHandle($e, $t);
	}

	/**
	 * Handles the event by calling the decorated handle method.
	 * @param Event
	 * @param Traversal
	 * @return boolean Returns True if the event is handled.
	 */
	function handle($e, $t) {
		return $this->_d->handle($e, $t);
	}

	/**
	 * Calls the decorated onAction
	 * @param Event
	 * @param Traversal
	 * @return boolean
	 */
	function onAction($e, $t) {
		return $this->_d->onAction($e, $t);
	}

	/**
	 * Calls the decorated onAjax
	 * @param Event
	 * @param Traversal
	 * @return boolean
	 */
	function onAjax($e, $t) {
		return $this->_d->onAjax($e, $t);
	}

	/**
	 * Calls the decorated onRender
	 * @param Event
	 * @param Traversal
	 * @return boolean
	 */
	function onRender($e, $t) {
		return $this->_d->onRender($e, $t);
	}

	/**
	 * Calls the decorated onData
	 * @param Event
	 * @param Traversal
	 * @return boolean
	 */
	function onData($e, $t) {
		return $this->_d->onData($e, $t);
	}

	/**
	 * Calls the decorated onRenderEnter.
	 * This is called by handle prior to onRender.
	 * @param Traversal
	 */
	function onRenderEnter($t) {
		$this->_d->onRenderEnter($t);
	}

	/**
	 * Calls the decorated onRenderLeave.
	 * This is called by handle after onRender.
	 * @param Traversal
	 */
	function onRenderLeave($t) {
		$this->_d->onRenderLeave($t);
	}

}
;

/**
 * An interface of the dispatch pattern.  For a given event, Dispatchers finds a suitable Dispatchable to
 * handle the event.
 * @package		ARK
 * @subpackage	Controller
 */
class Dispatcher extends Dispatchable {
	var $_dispatchables;

	function __construct($name) {
		parent::__construct($name);
		$this->_dispatchables = array();
	}

	/**
	 * Handles the event by calling canHandle on all dispatchables in this Dispatcher.
	 * For each Dispatchable where canHandle returns True the Traversal $t is entered and the Dispatchables
	 * onAction method is called.  Note that only the first action that canHandle the event is executed.
	 * @param Event
	 * @param Traversal
	 * @return boolean Returns True if the event is handled.
	 */
	function onAction($e, $t) {
		$retval = False;
		$c = count($this->_dispatchables);
		for ($i = 0; $i < $c; $i++) {
			$d = $this->_dispatchables[$i];
			if ($d->canHandle($e, $t)) {
				$t->enter($d);
				$retval |= $d->handle($e, $t);
				$t->leave($d);
				break; // Only the first dispatchable that can handle this is called.
			}
		}
		return $retval;
	}

	/**
	 * Handles the event by calling canHandle on all dispatchables in this Dispatcher.
	 * For each Dispatchable where canHandle returns True the Traversal $t is entered and the Dispatchables
	 * onAjax method is called.  Note that only the first action that canHandle the event is executed.
	 * @param Event
	 * @param Traversal
	 * @return boolean Returns True if the event is handled.
	 */
	function onAjax($e, $t) {
		$retval = False;
		$c = count($this->_dispatchables);
		for ($i = 0; $i < $c; $i++) {
			$d = $this->_dispatchables[$i];
			if ($d->canHandle($e, $t)) {
				$t->enter($d);
				$retval |= $d->handle($e, $t);
				$t->leave($d);
				break; // Only the first dispatchable that can handle this is called.
			}
		}
		return $retval;
	}

	/**
	 * Handles the event by calling canHandle on all dispatchables in this Dispatcher.
	 * For each Dispatchable where canHandle returns True the Traversal $t is entered and the Dispatchables
	 * onData method is called.
	 * @param Event
	 * @param Traversal
	 * @return boolean Returns True if all dispatchables also return True.
	 */
	function onData($e, $t) {
		$retval = False;
		$c = count($this->_dispatchables);
		for ($i = 0; $i < $c; $i++) {
			$d = $this->_dispatchables[$i];
			if ($d->canHandle($e, $t)) {
				$t->enter($d);
				$retval |= $d->handle($e, $t);
				$t->leave($d);
			}
		}
		return $retval;
	}

	/**
	 * Handles the event by calling canHandle on all dispatchables in this Dispatcher.
	 * For each Dispatchable where canHandle returns True the Traversal $t is entered and the Dispatchables
	 * onRender method is called.
	 * @param Event
	 * @param Traversal
	 * @return boolean Returns True if the event is handled.
	 */
	function onRender($e, $t) {
		$retval = False;
		$c = count($this->_dispatchables);
		for ($i = 0; $i < $c; $i++) {
			$d = $this->_dispatchables[$i];
			if ($d->canHandle($e, $t)) {
				$t->enter($d);
				$retval |= $d->handle($e, $t);
				$t->leave($d);
			}
		}
		return $retval;
	}

	/**
	 * Adds the given Dispatchable $dispatchable to this dispatcher.
	 * @param Dispatchable
	 */
	function addChild($dispatchable) {
		assert($dispatchable);
		$this->_dispatchables[] = $dispatchable;
	}

}
;

?>

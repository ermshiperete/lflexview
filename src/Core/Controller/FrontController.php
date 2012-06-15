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
 */
require_once (SGF_CORE.'Controller/Dispatch.php');
require_once (SGF_CORE.'Controller/Event.php');
require_once (SGF_CORE.'Controller/Eventqueue.php');
require_once (SGF_CORE.'Controller/Traversal.php');

/**
 * FrontController
 * @package		ARK
 * @subpackage	Controller
 */
abstract class FrontController extends Dispatcher {
	/**
	 * The default action
	 * @access protected
	 */
	var $_defaultAction;

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct('/');
		$this->_defaultAction = '';
	}

	/**
	 * Sets the default action.
	 * If no action is passed on the URL, this is the action that is performed.
	 * @param ActionPath
	 */
	function setDefaultAction($actionPath) {
		$this->_defaultAction = $actionPath;
	}

	/**
	 * @return IDataSpace
	 */
	abstract protected function getStateSpace();

	/**
	 * Run the FrontController
	 * Initialises the Traversal with the Session data.
	 * Pushes the first event into the EventQueue from act ($_GET['act']) passed on the URL.
	 * Loops, handling each Event in the EventQueue until none remain. The Session is then saved.
	 * @see Traversal
	 * @see Event
	 * @see EventQueue
	 */
	function run() {
		$eq = EventQueue::singleton();
		$t = new Traversal();
		$stateSpace = $this->getStateSpace();
		$t->stateSetRoot($stateSpace);
		$actionPath = ControllerKit::urlMapper()->readPartPath();
		if ($actionPath == NULL) {
			$actionPath = $this->_defaultAction;
		}
		// create the first event and put it in the queue
		$first = new Event(EVT_Action, $actionPath);
		$eq->addEvent($first);
		// while events exist in the queue handle them
		while ($eq->hasEvent()) {
			$e = $eq->getEvent();
			$t->begin($e->getActionPath());
			$this->handle($e, $t);
			$eq->doneEvent();
			// If there are no more events, and there has been no page rendered post a render event
			// TODO: This is bogus. Not all events result in a page render. e.g. AJAX request.
			if (!$eq->hasEvent() && !$eq->hasRender()) {
				$event = new Event(EVT_Render, $e->getActionPath());
				$eq->addEvent($event);
			}
		}
	}

}

?>

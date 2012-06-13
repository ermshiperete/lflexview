<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package		ARK
 * @subpackage	Controller
 * @version    $Id: eventqueue.php,v 1.1.1.1 2006/04/13 01:11:01 cambell Exp $
 * @author		  Cambell Prince <cambell@arketec.com>
 * @link				http://www.arketec.com
 * @see
 */

/**
 */
require_once(SGF_CORE . 'Controller/actionpath.php');

/**
 * EventQueue provides the FrontController with services for managing multiple events.
 * @package		ARK
 * @subpackage	Controller
 * @see FrontController
 */
class EventQueue {
	/**
	 * The Event array
	 * @var array
	 */
	var $events_;

	/**
	 * Index into the event array
	 * @var integer
	 */
	var $index_;

	/**
	 * true if the queue has had a EVT_Render event posted
	 * @var boolean
	 */
	var $hasRender_;

	/**
	 * @return EventQueue
	 */
	public static function singleton() {
		if (!isset($GLOBALS['_eventqueue']['singleton'])) {
			$GLOBALS['_eventqueue']['singleton'] = new EventQueue();
		}
		return $GLOBALS['_eventqueue']['singleton'];
	}

	/**
	 * Constructor. Do not create a new EventQueue.  Use the EventQueue::singleton() function.
	 * @access private
	 * @see singleton
	 */
	function EventQueue() {
		$this->events_ = array();
		$this->index_ = 0;
		$this->hasRender_ = false;
	}

	/**
	 * Adds the event $e to the queue.
	 * @param Event
	 */
	function addEvent($e) {
		// debug('adding event', $e);
		assert(is_a($e, 'Event'));
		if ($e->getType() == EVT_Render) {
			$this->hasRender_ = true;
			$ap = $e->getActionPath();
			assert(is_a($ap, 'ActionPath'));
			$path = $ap->toPath();
			//TODO Not sure why this is here really. Looks like logging to me.
			//			$s = Session::singleton();
			//			$s->set('render', $path);
		}
		$this->events_[] = $e;
	}

	/**
	 * getEvent
	 * Returns a reference to the current event.
	 * @return Event
	 */
	function &getEvent() {
		return $this->events_[$this->index_];
	}

	/**
	 * doneEvent
	 * Moves to internal event index to the next event.
	 */
	function doneEvent() {
		$this->index_++;
	}

	/**
	 * hasEvent
	 * Returns true if there are any events remaining in the queue.
	 * @return boolean
	 */
	function hasEvent() {
		return $this->index_ < count($this->events_);
	}

	/**
	 * hasRender
	 * Returns true if there is at least one render event has been posted to the queue.
	 * @return boolean
	 */
	function hasRender() {
		return $this->hasRender_;
	}



};

?>

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
require_once (SGF_CORE.'Controller/Deck.php');
require_once (SGF_CORE.'Data/Session.php');
require_once (SGF_CORE.'Controller/Event.php');
require_once (SGF_CORE.'Controller/EventQueue.php');
require_once (SGF_CORE.'Controller/Traversal.php');

/**
 * Authorised
 * @package		ARK
 * @subpackage	Controller
 */
class Authorised extends Deck {
	var $privilege_;

	var $actAuthorise_;
	var $actRegister_;

	/**
	 * Constructor
	 */
	function Authorised($name, $privilege) {
		$this->Deck($name, DECK_FollowAction);
		$this->privilege_ = $privilege;
	}

	/**
	 * Sets the authorise action
	 * This action is posted to the event queue if the session is not sufficiently privileged but a
	 * user ID (UID) exists in the session indicating that a user ID has been associated with this
	 * sometime in the past.
	 * @param ActionPath
	 * @see onAction
	 */
	function setAuthoriseAction($actionPath) {
		$this->actAuthorise_ = $actionPath;
	}

	/**
	 * Sets the register action
	 * This action is posted to the event queue if the session is not sufficiently privileged and no
	 * user ID is associated with the session.
	 * @param ActionPath
	 * @see onAction
	 */
	function setRegisterAction($actionPath) {
		$this->actRegister_ = $actionPath;
	}

	/**
	 * Handles the onAction event.
	 * If the user is sufficiently privileged then handle the event as normal, otherwise
	 * post an event for login (if there is a uid set), or register if uid is not set.
	 * @param Event
	 * @param Traversal
	 * @return boolean Returns true if the event is handled.
	 */
	function onAction($e, $t) {
		$ret = false;
		$eq = &EventQueue::singleton();
		$s = &Session::singleton();
		if ($s->getPrivilege() >= $this->privilege_) {
			// If we are sufficiently privileged
			$tgt = $s->get('tgt');
			if ($tgt) {
				// If we have a previously set target go there
				$eq->addEvent( new Event(EVT_Action, new ActionPath($tgt)));
				$s->erase('tgt');
				$ret = true;
			} else {
				// Assume the event knows what it is doing and do the parent processing
				$ret = parent::onAction($e, $t);
			}
		} else {
			// We are insufficiently privileged so save the target in the session
			$ap = $e->getActionPath();
			$tgt = $ap->toPath();
			$s->set('tgt', $tgt);
			if ($s->getUID() != 0) {
				// If we have a past UID then try to authorise (login)
				$eq->addEvent( new Event(EVT_Action, $this->actAuthorise_));
				$ret = true;
			} else {
				// If we don't then try to register
				$eq->addEvent( new Event(EVT_Action, $this->actRegister_));
				$ret = true;
			}
		}
		return $ret;
	}

}

/**
 * Authorised
 * @package		ARK
 * @subpackage	Controller
 */
class IsAuthorised extends Deck {
	var $privilege_;

	var $actAuthorised_;
	var $actNotAuthorised_;

	/**
	 * Constructor
	 */
	function IsAuthorised($name, $privilege) {
		$this->Deck($name, DECK_FollowAction);
		$this->privilege_ = $privilege;
	}

	/**
	 * Sets the authorised action
	 * This action is posted to the event queue if the user is sufficiently privileged.
	 * @param ActionPath
	 * @see onAction
	 */
	function setAuthorisedAction($actionPath) {
		$this->actAuthorised_ = $actionPath;
	}

	/**
	 * Sets the unauthorised action
	 * This action is posted to the event queue if the user is not sufficiently privileged.
	 * @param ActionPath
	 * @see onAction
	 */
	function setNotAuthorisedAction($actionPath) {
		$this->actNotAuthorised_ = $actionPath;
	}

	/**
	 * Handles the onAction event.
	 * Either the authorised or not authorised action is posted to the event queue depending on the current
	 * session privilege.
	 * @param Event
	 * @param Traversal
	 * @return boolean Returns true if the event is handled.
	 */
	function onAction($e, $t) {
		$eq = &EventQueue::singleton();
		$s = &Session::singleton();
		if ($s->getPrivilege() >= $this->privilege_) {
			$eq->addEvent( new Event(EVT_Action, $this->actAuthorised_));
		} else {
			$eq->addEvent( new Event(EVT_Action, $this->actNotAuthorised_));
		}
		return true;
	}

}

?>

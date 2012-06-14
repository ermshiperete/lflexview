<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package		SayGoForms
 * @subpackage	Controller
 * @author		  Cambell Prince <cambell@arketec.com>
 * @link				http://www.arketec.com
 */

/**
 */
require_once (SGF_CORE.'Controller/Dispatch.php');


/**
 * Constants used in followMode
 * @see Deck::Deck
 */
define('DECK_AlwaysRender', 0);
define('DECK_FollowAction', 1);

/**
 * A Deck is a transparent assemby
 * The deck is renderable but does not have a view.  A deck can be set to render always, or render only if
 * the name of the deck matches the next action in the action path.  This is set in the constructor.
 * @package		ARK
 * @subpackage	Controller
 */
class Deck extends Dispatcher {

	/**
	 * Alters the behaviour of canHandle e.g. should follow action path for EVT_Render
	 * @var enum One of the DECK_ constants
	 * @access public
	 */
	var $_followMode;

	/**
	 * If set the traversal context is saved during onAction with this name.
	 * @var string
	 * @access public
	 * @todo The whole context thing should be a function in Traversal, related to the URLWriter implementation.
	 */
	var $_context;

	/**
	 * Constructor
	 * @param string
	 * @param boolean
	 * @param string
	 */
	function __construct($name, $followMode = DECK_AlwaysRender , $context = null) {
		parent::__construct($name);
		$this->_followMode = $followMode;
		$this->_context = $context;
	}

	/**
	 * Sets the name of the context
	 * @param string
	 */
	function setContext($context) {
		$this->_context = $context;
	}

	/**
	 * Gets the name of the context
	 * @return string
	 */
	function getContext() {
		return $this->_context;
	}

	/**
	 * Sets the followMode
	 * @param enum One of the DECK_ constants
	 */
	function setFollowMode($followMode) {
		$this->_followMode = $followMode;
	}

	/**
	 * Gets the followMode
	 * @return enum One of the DECK_ constants
	 */
	function getFollowMode() {
		return $this->_followMode;
	}

	/**
	 * canHandle
	 * Can always be rendered, action only if name is equal to the next action name in the traversal.
	 * @param Event
	 * @param Traversal
	 * @return bool
	 */
	function canHandle($e, $t) {
		$type = $e->getType();
		switch ($type) {
			case EVT_Action:
				$ret = ($this->_name == $t->actionGetNextName());
				break;

			case EVT_Render:
				if ($this->_followMode == DECK_FollowAction) {
					$ret = ($this->_name == $t->getNextActionName());
				} else {
					$ret = true;
				}
				break;

			default:
				$ret = false;
		}
		return $ret;
	}

	/**
	 * onAction
	 * Stores a context in the Traversal named $this->_context
	 * @param Event
	 * @param Traversal
	 */
	function onAction($e, $t) {
		if ($this->_context) {
			$t->saveContext($this->_context);
		}
		parent::onAction($e, $t);
	}

	/**
	 * onRender
	 * Stores a context in the Traversal named $this->_context
	 * @param Event
	 * @param Traversal
	 */
	function onRender($e, $t) {
		if ($this->_context) {
			$t->saveContext($this->_context);
		}
		parent::onRender($e, $t);
	}

}

?>

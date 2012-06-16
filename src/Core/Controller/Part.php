<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package		ARK
 * @subpackage	Controller
 * @version    $Id: part.php,v 1.1.1.1 2006/04/13 01:11:01 cambell Exp $
 * @author		  Cambell Prince <cambell@arketec.com>
 * @link				http://www.arketec.com
 */

/**
 */
require_once (SGF_CORE.'Controller/Deck.php');
require_once (SGF_CORE.'View/IViewProvider.php');

/**
 * Part is a Deck that is renderable by a View, and may have many sub-parts.
 * Parts have a View that they render into. Derived classes over-ride onRender to push
 * their data into their View.
 * A Part has a position which is the place it renders within the current View.
 * @package		ARK
 * @subpackage	Controller
 */
class Part extends Deck {
	/**
	 * @var IViewProvider
	 */
	private $_viewProvider;

	/**
	 * @var string
	 */
	protected $_position;

	/**
	 * @var IView
	 */
	protected $_view;

	/**
	 * Constructor
	 * @param string
	 * @param string
	 * @param string
	 * @param boolean
	 * @param string
	 */
	function __construct($name, $viewProvider, $position = 'Body', $hasFollowAction = DECK_AlwaysRender , $context = null) {
		parent::__construct($name, $hasFollowAction, $context);
		$this->_view = null;
		$this->_viewProvider = $viewProvider;
		$this->_position = $position;
		// TODO Remove this short term sanity type check on $viewProvider
		if (!is_a($viewProvider, 'IViewProvider')) {
			throw new Exception("viewProvider must be an IViewProvider");
		}
	}

	/**
	 * Set the position.
	 * The position is the place in the parent where this part is rendered
	 * @param string
	 */
	function setPosition($position) {
		$this->_position = $position;
	}

	/**
	 * Get the position
	 * @return string
	 */
	function getPosition() {
		return $this->_position;
	}

	/**
	 * Sets the View in the Traversal.
	 * @param Traversal
	 */
	function onRenderEnter($t) {
		if (!$this->_view) {
			if ($this->_viewProvider) {
				$this->_view = $this->_viewProvider->createView();
			}
			if ($this->_view) {
				$this->_view->onRenderEnter();
				$t->viewSet($this->_view);
			}
		}
		if (!$this->_view) {
			$this->_view = $t->viewGet();
		}
		// If we haven't got a view by now we've got problems.
	}

	function renderProperties($t) {
		$this->_view->pushText('_Name', $this->_name);
		$this->_view->pushText('_Position', $this->_position);
		// todo: any other properties?
	}

	function renderArguments($t) {
		$command = $t->getCommand();
		if ($command) {
			$c = count($command->args);
			for ($i = 0; $i < $c; $i++) {
				$this->_view->pushText("_Arg$i", $command->args[$i]);
			}
		}
	}

	/**
	 *
	 * @param Event $t
	 * @param Traversal $t
	 */
	protected function renderChildren($e, $t) {
		foreach ($this->_dispatchables as $d) {
			if ($d->canHandle($e, $t)) {
				$t->enter($d);
				$d->handle($e, $t);
				$view = $t->viewGet();
				assert($view != null);
				$render = $view->renderToString();
				$this->_view->addText($t->viewGetPosition(), $render);
				//				echo "--- Part --- Render " . $d->getName() . " from " . $view->_fileName . " into " . $this->getName() . " at " . $t->viewGetPosition() . " into view " . $this->_view->_fileName . "<br/>";

				$t->leave($d);
			}
		}
	}

	/**
	 * Handles the onRender event.
	 * Renders the arguments of the current action as {_ARGn}
	 * @param Event
	 * @param Traversal
	 * @return boolean
	 */
	function onRender($e, $t) {
		$this->renderProperties($t);
		$this->renderArguments($t);
		$this->renderChildren($e, $t);
	}

	/**
	 * Renders the template into the target variable in the parent template with append
	 * @param Traversal
	 */
	function onRenderLeave($t) {
		$t->viewSet($this->_view);
		$t->viewSetPosition($this->_position);
	}

}

?>

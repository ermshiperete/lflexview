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
require_once (SGF_CORE.'Controller/actionpath.php');
require_once (SGF_CORE.'Controller/urlwriter.php');
require_once (SGF_CORE.'data/IDataSpace.php');
require_once (SGF_CORE.'data/dotpath.php');

/**
 * @package		ARK
 * @subpackage	Controller
 */
class TraversalEntry {
	var $name;
	var $action;
	var $dispatchable;

	function TraversalEntry() {
		$this->name = null;
		$this->action = null;
		$this->dispatchable = null;
	}

}
;

/**
 * @package		ARK
 * @subpackage	Controller
 * The Traversal is an implementation of the Visitor pattern that 'visits' each part during a traversal
 * caused by an Event. The Traversal contains current state relating to the current state of the traversal
 * independantly of the Part being traversed.
 *
 * In practice this means that Parts can easily exist in many places in the Part hierarchy and can be
 * coded with no knowledge of where their state comes from, each Part obtains it's current state from
 * the Traversal.
 *
 * The Traversal is a Singleton and should be accessed with $t = Traversal::singleton();
 * The Traversal is initialised by calling the init(...) method.
 */
class Traversal {
	/**
	 * The target path
	 * @var ActionPath
	 */
	private $_targetPath;

	/**
	 * The current path
	 * @var ActionPath
	 */
	private $_path;

	/**
	 * @see ArraySpace
	 */
	private $_state;

	/**
	 * @see IDataSpace
	 * @var array[IDataSpace]
	 */
	private $_data;

	/**
	 * @var integer
	 */
	private $_dataLevel;

	/**
	 * @var URLWriter
	 */
	private $_urlWriter;

	/**
	 * @var View
	 */
	private $_view;

	/**
	 * @var string
	 */
	private $_position;

	/**
	 * @var Page
	 */
	private $_page;

	/**
	 * An array representing the traversal stack. Every enter causes a new entry to be pushed on the stack.
	 * Every leave pops the stack.
	 * @see enter
	 * @see leave
	 */
	private $_traversal;

	/**
	 * $_level is always set to the last valid entry of the $_traversal stack. i.e. count($_traversal) - 1
	 * @see $_traversal
	 */
	private $_level;

	/**
	 * The current TraversalEntry
	 * @see TraversalEntry
	 * @see enter
	 * @see leave
	 */
	private $_current;

	/**
	 * The Traversal constructor should not be called.  Use the singleton accessor.
	 * @access private
	 * @see singleton
	 */
	function __construct() {
		$this->_data = array( 0 => NULL );
		$this->_dataLevel = 0;
		$this->_view = null;
		$this->_position = '';
		$this->_page = null;
		$this->_state = null;
		$this->_currentState = null;
		$this->_context = array();
		$this->_urlWriter = new URLWriter($_SERVER['PHP_SELF']);
	}

	/**
	 * Traversal initialisation.
	 * The traversal must be initialised with a DataSpace representing persistant state. e.g. A session
	 * Note that there are two initialisations:
	 * 1) init Called one time only at the start of the script run
	 * 2) begin Called at the start of each traversal
	 * @see Session, DBSession
	 * @param DataSpace
	 * @param URLWriter
	 */
	function stateSetRoot(&$state) {
		// One time initialisation
		$this->_state = &$state;
		$this->_currentState = null;
	}

	/**
	 * Set the URLWriter for the traversal
	 * @param URLWriter
	 */
	function setURLWriter($urlWriter) {
		$this->_urlWriter = $urlWriter;
	}

	/**
	 * Begin the traversal over the given $target
	 * @param ActionPath
	 */
	function begin($target) {
		$this->_path = new ActionPath('');
		if ($target != null) {
			$this->_targetPath = $target;
		} else {
			$this->_targetPath = new ActionPath('');
		}

		$this->_traversal = array();
		$this->_context = array();

		$te = new TraversalEntry;
		$te->name = '/';
		$te->action = null;
		$this->_traversal[] = &$te;

		$this->_current = &$te;
		$this->_currentState = null;

		$this->_level = 0;
	}

	/**
	 * Returns the current target path.
	 * @return ActionPath
	 */
	function getTargetPath() {
		return $this->_targetPath;
	}

	/**
	 * Gets the current path representing the current state of the traversal.
	 * @return ActionPath
	 */
	function getPath() {
		return $this->_path;
	}

	/**
	 * Returns the current action
	 * @return Action The current action
	 */
	function getAction() {
		return $this->_current->action;
	}

	/**
	 * Save the current path with the given name
	 * @param string
	 * @see ActionPath
	 * @see buildURLByPath
	 */
	function saveContext($name) {
		$this->_context[$name] = $this->_path;
	}

	/**
	 * Build an action path by merging the current path and the given action.
	 * Copies the current path, and pops the last entry then pushes the given action. The resulting
	 * @param Action
	 * @return string Suitable for use as the next URL
	 */
	function buildURLByAction($action) {
		$ap = $this->_path;
		$ap->pop();
		$ap->push($action);
		return $this->_urlWriter->write($ap->toPath());
	}

	/**
	 * Build an action path by merging the base and the given action path.
	 * This function calls buildActionPath to get the action path to merge with.
	 * @param ActionPath
	 * @return string Suitable for use as the next URL
	 * @see buildActionPath
	 */
	function buildURLByPath($actionPath) {
		$ap = $this->buildActionPath($actionPath);
		if ($ap) {
			$action = $ap->toPath();
		} else {
			$action = '';
		}
		return $this->_urlWriter->write($action);
	}

	/**
	 * Build an action path by merging the traversal and the given action path.
	 * The resulting ActionPath is build according to the context of the $actionPath
	 * @param ActionPath
	 * @return ActionPath May be null if context not found
	 */
	function buildActionPath($actionPath) {
		$context = $actionPath->getContext();
		$ret = null;
		switch ($context) {
			case '/':
				$ret = $actionPath;
				break;
			case 'current':
				$ret = clone $this->_path;
				$ret->pop();
				$ret->append($actionPath);
				break;
			default:
				if (isset($this->_context[$context])) {
					$ret = $this->_context[$context];
					$ret->append($actionPath);
				} else {
					Error::log(__FILE__, __LINE__, 'No context', $context);
				}
				break;
		}
		return $ret;
	}

	/**
	 * @deprecated
	 * @see actionGetNextName
	 */
	function getNextActionName() {
		return $this->actionGetNextName();
	}

	/**
	 * @return string The next action name
	 */
	function actionGetNextName() {
		$ret = null;
		$action = $this->_targetPath->get($this->_level);
		if ($action) {
			$ret = $action->getName();
		}
		return $ret;
	}

	/**
	 * @todo document this
	 * Pushes a new TraversalEntry on the stack and sets info about the current dispatchable being traversed.
	 * By default sets the view to be the same as that of the parent.
	 * @param Dispatchable
	 */
	function enter(&$dispatchable) {
		$name = $dispatchable->getName();
		//debug('enter', $name);
		$te = new TraversalEntry;
		$te->name = $name;
		$te->dispatchable = &$dispatchable;

		// Current State
		$this->_currentState = null;

		// Current action and path
		$te->action = null;
		$action = &$this->_targetPath->get($this->_level);
		if ($action) {
			if ($action->getName() == $name) {
				$this->_path->push($action);
				$te->action = &$action;
			} else {
				$this->_path->push( new Action($name));
			}
		} else {
			$this->_path->push( new Action($name));
		}

		$this->_traversal[] = &$te;
		$this->_level++;
		$this->_current = &$te;
	}

	function leave(&$dispatchable) {
		$this->_currentState = null;

		$this->_path->pop();

		if ($this->_level > 0) {
			// Down the level
			$this->_level--;
			$this->_current = &$this->_traversal[$this->_level];
			// Pop off the traversal
			array_pop($this->_traversal);
		}
	}

	/**
	 * Set the current view
	 * @param IView
	 */
	function viewSet($view) {
		$this->_view = $view;
	}

	/**
	 * Returns the current view
	 * @return IView
	 */
	function viewGet() {
		return $this->_view;
	}

	/**
	 * Set the position of this dispatchable (part) in the current view
	 * @param string $position
	 */
	function viewSetPosition($position) {
		$this->_position = $position;
	}

	/**
	 * Returns the position of this dispatchable (part) in the current view
	 * @return string
	 */
	function viewGetPosition() {
		return $this->_position;
	}

	/**
	 * Set the Page in use for this traversal
	 * @param Page $page
	 */
	function pageSet($page) {
		$this->_page = $page;
	}

	/**
	 * Returns the page towards the root of this traversal
	 * Pages set themselves in the traversal, for others to use as required.
	 * @return Page
	 */
	function pageGet() {
		return $this->_page;
	}

	/**
	 * Returns the parent dispatchable
	 * @return Dispatchable
	 */
	function getParent() {
		$ret = null;
		if ($this->_level > 0) {
			$ret = $this->_traversal[$this->_level - 1]->dispatchable;
		}
		return $ret;
	}

	/**
	 * Set data in the persistent data store.
	 * @param string
	 * @param string
	 */
	function stateSet($name, $value) {
		// Ensure that the state exists
		if ($this->_currentState == null) {
			$this->_currentState = &dot_getSpace($this->_path->toPathName(), $this->_state);
		}
		if ($this->_currentState) {
			$this->_currentState->set($name, $value);
		}
	}

	/**
	 * Get data from the persistent data store
	 * @param string
	 */
	function stateGet($key) {
		$ret = null;
		if ($this->_currentState) {
			$ret = $this->_currentState->get($key);
		}
		return $ret;
	}

	/**
	 * Returns a reference to the current state IDataSpace
	 * Returns the DataSpace representing the current state at this level of the traversal, or null if not
	 * found.
	 * @return DataSpace
	 */
	function stateGetOrCreateSpace() {
		$ret = NULL;
		// Ensure that the state exists
		if ($this->_currentState == null) {
			// TODO Need this to be dot_getOrCreateSpace
			// TODO Need a dot_getSpace in enter
			$this->_currentState = dot_getSpace($this->_path->toPathName(), $this->_state);
		}
		$ret = $this->_currentState;
		return $ret;
	}

	/**
	 * Returns a reference to the current state IDataSpace.
	 * This can return NULL if the current part has never set any state data.
	 * @return IDataSpace Can return NULL.
	 */
	function stateGetCurrentSpace() {
		return $this->_currentState;
	}

	/**
	 * Add a new data context to the data stack.
	 * If $copy is true then the existing data space is copied and appended, otherwise a new ArraySpace is
	 * appended.
	 * @param boolean
	 */
	function dataEnter($copy = false) {
		if ($copy) {
			$this->_data[] = $this->_data[$this->_dataLevel];
		} else {
			$this->_data[] = NULL;
		}
		$this->_dataLevel++;
	}

	/**
	 * Remove the last data space from the stack of data.
	 */
	function dataLeave() {
		if ($this->_dataLevel > 0) {
			array_pop($this->_data);
			$this->_dataLevel--;
		}
	}

	/**
	 * Set data in the non-persistent data store.
	 * @param IDataSpace
	 */
	function dataSet($data) {
		$this->_data[$this->_dataLevel] = $data;
	}

	/**
	 * Get data from the non-persistent data store.
	 * @return IDataSpace
	 */
	function dataGet() {
		return $this->_data[$this->_dataLevel];
	}

}

?>

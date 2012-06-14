<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package		ARK
 * @subpackage	Controller
 * @version    $Id: actionpath.php,v 1.1.1.1 2006/04/13 01:11:01 cambell Exp $
 * @author		  Cambell Prince <cambell@arketec.com>
 * @link				http://www.arketec.com
 * @see
 */



/**
 * @package		ARK
 * @subpackage	Controller
 */
class ActionPath { // TODO Better name is TraversalPath
	/**
	 * @var array
	 */
	var $_paths = array();

	/**
	 * @var string
	 */
	var $_context;

	/**
	 * Constructor
	 * @param string
	 * @param string '/', 'current', or other context
	 * @access public
	 */
	function __construct($paths = array(), $context = '/') {
		$this->_paths = $paths;
		$this->_context = $context;
	}

	function getPaths() {
		return $this->_paths;
	}
	
	/**
	 * Sets the context to $context
	 * The context describes how to apply this action path.
	 */
	function setContext($context) {
		$this->_context = $context;
	}

	/**
	 * Gets the context
	 * @return string
	 * @see Traversal::buildURLBy
	 */
	function getContext() {
		return $this->_context;
	}

	/**
	 * Converts the given $path to Actions stored in this ActionPath.
	 * @param string The path to store
	 * @return void
	 * @access public
	 */
	function fromPath($path) {
		$this->_paths = array();
		if ($path) {
			$actionPaths = explode('.', $path);
			foreach($actionPaths as $a) {
				$this->_paths[] = new Action($a);
			}
		}
	}

	/**
	 * Returns the string representation of this ActionPath without Action parameters.
	 * @return string
	 * @access public
	 */
	function toPathName() {
		$ret = "";
		$c = count($this->_paths);
		for ($i = 0; $i < $c; $i++) {
			if ($i > 0) {
				$ret .= '.';
			}
			$ret .= $this->_paths[$i]->getName();
		}
		return $ret;
	}

	/**
	 * Returns the string representation of this ActionPath to the given level.
	 * @param integer
	 * @return string
	 * @access public
	 */
	function toPartialPath($index) {
		$ret = "";
		for ($i = 0; $i <= $index; $i++) {
			if ($i > 0) {
				$ret .= '.';
			}
			$ret .= $this->_paths[$i]->toPath();
		}
		return $ret;
	}

	/**
	 * Returns the number of actions in this ActionPath
	 * @return integer The number of actions in this ActionPath
	 * @access public
	 */
	function count() {
		return count($this->_paths);
	}

	/**
	 * Returns the $index'th path.
	 * May return null if not found.
	 * @param integer The 0 based action to return
	 * @return string The path if found or null
	 * @access public
	 */
	function get($index) {
		$ret = null;
		if ($index < count($this->_paths)) {
			$ret = $this->_paths[$index];
		}
		return $ret;
	}

	/**
	 * Sets the action at $index to $action
	 * @param integer
	 * @param Action
	 */
	function set($index, $action) {
		if ($index < count($this->_paths)) {
			$this->_paths[$index] = $action;
		}
	}

	/**
	 * Returns a reference to the first Action in the ActionPath
	 * May return null if there are no actions in the ActionPath.
	 * @return Action
	 */
	function &first() {
		$ret = null;
		$c = count($this->_paths);
		if ($c > 0) {
			$ret = $this->_paths[0];
		}
		return $ret;
	}

	/**
	 * Returns a reference to the last Action in the ActionPath
	 * May return null if there are no actions in the ActionPath.
	 * @return Action
	 */
	function &last() {
		$ret = null;
		$c = count($this->_paths);
		if ($c > 0) {
			$ret = $this->_paths[$c - 1];
		}
		return $ret;
	}

	/**
	 * Pops the last action off the action array
	 */
	function pop() {
		array_pop($this->_paths);
	}

	/**
	 * Pushes Action $action onto the end of the action array.
	 * @param Action
	 */
	function push($action) {
		$this->_paths[] = $action;
	}

	/**
	 * Appends the ActionPath $actionPath to this path
	 * @param ActionPath
	 */
	function append($actionPath) {
		$this->_paths = array_merge($this->_paths, $actionPath->_paths);
	}

	/**
	 * A convenient accessor for getting the name of the action at the given $index.
	 * @param integer The 0 based action
	 * @access public
	 * @return string The action name if found or null
	 */
	function getName($index) {
		$ret = null;
		if ($index < count($this->_paths)) {
			$ret = $this->_paths[$index]->getName();
		}
		return $ret;
	}

};

?>

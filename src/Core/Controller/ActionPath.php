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
class Action {
	var $_name;
	var $args_;

	/**
	 * Constructor
	 * @param string
	 */
	function Action($path) {
		$this->fromPath($path);
	}

	/**
	 * Converts the given path string to an action
	 * @param string
	 */
	function fromPath($path) {
		$this->args_ = explode(':', $path);
		$this->_name = array_shift($this->args_);
	}

	/**
	 * Converts the Action to a path string
	 * The return value is a string of the form name:arg1:arg2...argN
	 * @return string
	 */
	function toPath() {
		$ret = $this->_name;
		if (count($this->args_) > 0) {
			$ret .= ':' . implode(':', $this->args_);
		}
		return $ret;
	}

	/**
	 * Returns the name of the Action
	 * @return string
	 */
	function getName() {
		return $this->_name;
	}

	/**
	 * Sets the name of the Action
	 * @param string
	 */
	function setName($name) {
		$this->_name = $name;
	}

	/**
	 * Sets the arguments of the Action
	 * @param integer
	 * @param string
	 */
	function set($index, $value) {
		$this->args_[$index] = $value;
	}

	/**
	 * Returns the index'th argument of the Action
	 * @param integer
	 */
	function get($index) {
		return $this->args_[$index];
	}

	/**
	 * Clears all arguments of the Action
	 */
	function clear() {
		$this->args_ = array();
	}

	/**
	 * Returns the number of arguments in this action
	 * @return integer
	 */
	function count() {
		return count($this->args_);
	}

};

/**
 * @package		ARK
 * @subpackage	Controller
 */
class ActionPath { // TODO Better name is TraversalPath
/**
 * @var array
 */
var $actions_;

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
function ActionPath($path, $context = '/') {
	$this->_context = $context;
	$this->fromPath($path);
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
	$this->actions_ = array();
	if ($path) {
		$actionPaths = explode('.', $path);
		foreach($actionPaths as $a) {
			$this->actions_[] = new Action($a);
		}
	}
}

/**
 * Returns the string representation of this ActionPath.
 * @return string
 * @access public
 */
function toPath() {
	$ret = "";
	$c = count($this->actions_);
	for ($i = 0; $i < $c; $i++) {
		if ($i > 0) {
			$ret .= '.';
		}
		$ret .= $this->actions_[$i]->toPath();
	}
	return $ret;
}

/**
 * Returns the string representation of this ActionPath without Action parameters.
 * @return string
 * @access public
 */
function toPathName() {
	$ret = "";
	$c = count($this->actions_);
	for ($i = 0; $i < $c; $i++) {
		if ($i > 0) {
			$ret .= '.';
		}
		$ret .= $this->actions_[$i]->getName();
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
		$ret .= $this->actions_[$i]->toPath();
	}
	return $ret;
}

/**
 * Returns the number of actions in this ActionPath
 * @return integer The number of actions in this ActionPath
 * @access public
 */
function count() {
	return count($this->actions_);
}

/**
 * Returns a reference to the $index'th action.
 * May return null if not found.
 * @param integer The 0 based action to return
 * @return Action The action if found or null
 * @access public
 */
function &get($index) {
	$ret = null;
	if ($index < count($this->actions_)) {
		$ret = $this->actions_[$index];
	}
	return $ret;
}

/**
 * Sets the action at $index to $action
 * @param integer
 * @param Action
 */
function set($index, $action) {
	if ($index < count($this->actions_)) {
		$this->actions_[$index] = $action;
	}
}

/**
 * Returns a reference to the first Action in the ActionPath
 * May return null if there are no actions in the ActionPath.
 * @return Action
 */
function &first() {
	$ret = null;
	$c = count($this->actions_);
	if ($c > 0) {
		$ret = $this->actions_[0];
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
	$c = count($this->actions_);
	if ($c > 0) {
		$ret = $this->actions_[$c - 1];
	}
	return $ret;
}

/**
 * Pops the last action off the action array
 */
function pop() {
	array_pop($this->actions_);
}

/**
 * Pushes Action $action onto the end of the action array.
 * @param Action
 */
function push($action) {
	$this->actions_[] = $action;
}

/**
 * Appends the ActionPath $actionPath to this path
 * @param ActionPath
 */
function append($actionPath) {
	$this->actions_ = array_merge($this->actions_, $actionPath->actions_);
}

/**
 * A convenient accessor for getting the name of the action at the given $index.
 * @param integer The 0 based action
 * @access public
 * @return string The action name if found or null
 */
function getName($index) {
	$ret = null;
	if ($index < count($this->actions_)) {
		$ret = $this->actions_[$index]->getName();
	}
	return $ret;
}

};

?>

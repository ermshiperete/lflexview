<?php
/**
 * @package		ARK
 * @subpackage	Controller
 */
class Command {
	var $_name;
	var $_args = array();

	/**
	 * Constructor
	 * @param string
	 */
	public function __construct($name, $args) {
		$this->_name = $name;
		$this->_args = $args;
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

?>
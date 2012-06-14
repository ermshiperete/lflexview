<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package		ARK
 * @subpackage	Data
 * @version    $Id: validator.php,v 1.1.1.1 2006/04/13 01:11:01 cambell Exp $
 * @author		  Cambell Prince <cambell@arketec.com>
 * @link				http://www.arketec.com
 */

/**
 * ValidRule is an interface for defining requirements that instances of DataSpace must
 * conform to in order to be considered valid.
 * @package		ARK
 * @subpackage	Data
 * @see http://arketec.com
 * @see Schema
 * @access public
 */
class ValidRule {

	/**
	 * Adds the name of the Control to the Validator if this Control is required.
	 * @param Validator
	 */
	function addRequired(&$validator) {
	}

	/**
	 * Checks that the given DataSpace is valid according to this rule.
	 * @param DataSpace
	 * @return boolean true if valid.
	 */
	function checkValid(&$keySpace) {
		return false;
	}

};

/**
 * Validator is used to determine if a DataSpace is considered valid.
 * The validator has a set of rules that the DataSpace must
 * conform to in order to be considered valid.
 * @package		ARK
 * @subpackage	Data
 * @link http://arketec.com
 * @access public
 */
class Validator {
	var $rules_;
	var $errors_;

	/**
	 * Constructor
	 */
	function Validator() {
		$this->rules_ = array();
		$this->errors_ = array();
	}

	/**
	 * Adds the given $rule to this Validator
	 * @param ValidRule
	 */
	function addRule($rule) {
		$this->rules_[] = $rule;
		$rule->addRequired($this);
	}

	/**
	 * Adds an error for the given control name.
	 * @param string
	 * @param string
	 */
	function addError($name, $error) {
		$this->errors_[$name][] = $error;
	}

	/**
	 * Returns the error array for control $name
	 * @param string
	 * @return array May return null if there is no error for $name
	 */
	function getError($name) {
		return $this->errors_[$name];
	}

	/**
	 * Returns the error array for all controls
	 * The returned array is an associative array keyed on the control names. Each element in the
	 * returned array contains an array of error strings representing the errors for each control.
	 * @return array
	 */
	function getErrors() {
		return $this->errors_;
	}

	/**
	 * Add this Control name to the list of required Controls
	 * @param string
	 */
	function addRequired($name) {
		$this->required_[$name] = true;
	}

	/**
	 * Return true if the given Control is required.
	 * @param string
	 * @return boolean
	 */
	function isRequired($name) {
		return isset($this->required_[$name]);
	}

	/**
	 * Return true if all the rules are valid.
	 * @return boolean
	 */
	function isValid() {
		return count($this->errors_) == 0;
	}

	/**
	 * Return true if the given Control is valid.
	 * @param string
	 * @return boolean
	 */
	function isValidControl($name) {
		return !isset($this->errors_[$name]);
	}

	/**
	 * Checks that the given DataSpace is valid according to the rules of this Validator
	 * @param DataSpace
	 * @return boolean true if valid.
	 * @todo add ref back to validator or other error notification store in ->checkValid.
	 */
	function checkValid(&$keySpace) {
		$ret = true;
		$this->errors_ = array();
		for ($i = 0; $i < count($this->rules_); $i++) {
			$ret &= $this->rules_[$i]->checkValid($this, $keySpace);
		}
		return $ret;
	}

}

?>

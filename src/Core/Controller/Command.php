<?php
/**
 * @package		ARK
 * @subpackage	Controller
 */
class Command {
	public $name;
	public $args = array();

	/**
	 * Constructor
	 * @param string
	 * @param string[]
	 */
	public function __construct($name, $args = array()) {
		$this->name = $name;
		$this->args = $args;
	}

}

?>
<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package		ARK
 * @subpackage	Controller
 * @version    $Id: handler.php,v 1.1.1.1 2006/04/13 01:11:01 cambell Exp $
 * @author		  Cambell Prince <cambell@arketec.com>
 * @link				http://www.arketec.com
 * @see
 */

/**
 */
require_once(SGF_CORE . 'Controller/Dispatch.php');
require_once(SGF_CORE . 'Controller/Handle.php');

/**
 * Delegates handle.  Matches canHandle on the name
 * @package		ARK
 * @subpackage	Controller
 */
class Handler extends DispatchableDecorator {

	/**
	 * @param string
	 * @param Handle
	 */
	function __constuct($name, $handle) {
		parent::__construct($name, $handle);
	}

	/**
	 * Returns true if the traversals current action name equals this Dispatables name.
	 * @param Event
	 * @param Traversal
	 */
	function canHandle($e, $t) {
		$ret = ($this->_name ==  $t->actionGetNextName());
		if ($ret) {
			$this->_d = Handle::resolve($this->_d);
		}
		return $ret;
	}

}

/**
 * Delegates both canHandle and handle
 */
/*
 class DelegateHandler extends Dispatchable {
 var $func_;
 var $handle_;

 function DelegateHandler($func, $handle) {
 $this->func_ = $func;
 $this->handle_ = $handle;
 }

 function canHandle($e, $t) {
 // Delegate our answer to the functor given in the constructor
 return $this->func_->isEqual($e, $t);
 }

 function handle($e, $t) {
 // Delegate our processing to the resolved handle
 $x = Handle::resolve($this->handle_);
 $x->handle($e, $t);
 }

 };
 */

/**
 */
/*
 class MatchAction {
 var $_name;

 function MatchAction($name) {
 $this->_name = $name;
 }

 function isEqual($e, $t) {
 return $this->_name == $t->getCommandName();
 }

 };
 */


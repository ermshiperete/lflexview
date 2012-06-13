<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package		ARK
 * @subpackage	Controller
 * @version    $Id: event.php,v 1.1.1.1 2006/04/13 01:11:01 cambell Exp $
 * @author		  Cambell Prince <cambell@arketec.com>
 * @link				http://www.arketec.com
 * @see
 */

/**
 * Event Types
 */
define('EVT_Action',     1);
define('EVT_Render',     2);
define('EVT_Data',       3);
define('EVT_AjaxAction', 4);
define('EVT_AjaxRender', 5);

/**
 * @package		ARK
 * @subpackage	Controller
 */
class Event {
	/**
	 * The type of this event. Must be one of the define EVT_... types
	 * @access private
	 */
	var $type_;

	/**
	 * The current action path
	 * @access private
	 */
	var $actionPath_;

	/**
	 * The constructor.  Stores variables from the URL.
	 * @access private
	 */
	function Event($type, $actionPath) {
		$this->type_ = $type;
		$this->actionPath_ = $actionPath;
	}

	function copy() {
		return $this;
	}

	function getType() {
		return $this->type_;
	}

	function getActionPath() {
		return $this->actionPath_;
	}

	/**
	 * Get the POST data as a PostSpace dataspace.
	 * @return ArraySpace
	 */
	function getPostSpace() {
	}

};

?>

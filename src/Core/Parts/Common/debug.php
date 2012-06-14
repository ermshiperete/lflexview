<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package		ARK
 * @subpackage	Parts
 * @version    $Id: debug.php,v 1.2 2006/05/15 02:36:49 cambell Exp $
 * @author		  Cambell Prince <cambell@arketec.com>
 * @link				http://www.arketec.com
 */

/**
 */
require_once(SGF_CORE . 'Error.php');
require_once(SGF_CORE . 'Util/debug.php');
require_once(SGF_CORE . 'Controller/part.php');

/**
 * @package		ARK
 * @subpackage	Parts
 */
class DebugPart extends Part {
	/**
	 * Constructor
	 * @param string
	 * @param string
	 * @param string
	 */
	function DebugPart($name, $view, $position) {
		$this->Part($name, $view, $position);
	}

	/**
	 * Handles the onRender event
	 * @param Event
	 * @param Traversal
	 */
	function onRender($e, $t) {
		$v = $t->viewGet();
		assert(is_a($v, 'View'));
		// Error
		/*
		 $e = Error::singleton();
		 if ($e->hasError()) {
		 $this->put($v, 'ERROR_ERROR', $e->getError());
		 }
		 if ($e->hasLog()) {
		 $this->put($v, 'ERROR_LOG', $e->getLog());
		 }
		 */
		// Debug
		$d = Debug::singleton();
		if ($d->hasLog()) {
			$this->put($v, 'DEBUG_LOG', $d->getLog());
		}
		// Session
		$s = Session::singleton();
		$this->put($v, 'SESSION_STORY', $s->story_);
		$this->put($v, 'SESSION_DATA', $s->values_);

		// render children (we shouldn't have any)
		parent::onRender($e, $t);
	}

	/**
	 * Puts the data into the view
	 * @param View
	 * @param string
	 * @param mixed
	 * @access private
	 */
	function put(&$view, $tag, $value) {
		assert(is_a($view, 'View'));
		$output = print_r($value, true);
		$view->pushText($tag, $output);
	}

}

?>

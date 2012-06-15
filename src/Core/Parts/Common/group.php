<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package		ARK
 * @subpackage	Parts
 * @version    $Id: group.php,v 1.1.1.1 2006/04/13 01:11:02 cambell Exp $
 * @author		  Cambell Prince <cambell@arketec.com>
 * @link				http://www.arketec.com
 */

/**
 */
require_once(SGF_CORE . 'Controller/Part.php');

/**
 * Group is a Part with a title
 * @package		ARK
 * @subpackage	Parts
 */
class Group extends Part {
	/**
	 * @var string
	 */
	var $title_;

	/**
	 * @var string
	 */
	var $image_;

	/**
	 * Constructor
	 * @param string
	 * @param string
	 * @param string
	 */
	function Group($name, $view, $position, $title = 'Title', $image = '') {
		$this->Part($name, $view, $position);
		$this->title_ = $title;
		$this->image_ = $image;
	}

	/**
	 * Handles the onRender event.
	 * Renders the title
	 * @param Event
	 * @param Traversal
	 * @return boolean Returns true always
	 */
	function onRender($e, $t) {
		$v = $t->viewGet();
		$v->pushText('TITLE', $this->title_);
		$v->pushText('IMAGE', $this->image_);
		parent::onRender($e, $t);

		return true;
	}

}

?>

<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package		ARK
 * @subpackage	Parts
 * @version    $Id: actionpart.php,v 1.1.1.1 2006/04/13 01:11:01 cambell Exp $
 * @author		  Cambell Prince <cambell@arketec.com>
 * @link				http://www.arketec.com
 */

/**
 */
require_once(SGF_CORE . 'Controller/Part.php');

/**
 * @package		ARK
 * @subpackage	Parts
 */
class ActionPart extends Part {
	/**
	 * @var ActionPath
	 */
	var $actionPath_;

	/**
	 * @var string
	 */
	var $label_;

	/**
	 * @var string
	 */
	var $image_;

	/**
	 * Constructor
	 * @param string
	 * @param string
	 * @param string
	 * @param ActionPath
	 * @param string
	 * @param string
	 */
	function ActionPart($name, $view, $position, $actionPath, $label, $image = null) {
		$this->Part($name, $view, $position);
		$this->actionPath_ = $actionPath;
		$this->label_ = $label;
		$this->image_ = $image;
	}

	/**
	 * Handles the onRender event
	 * pushes the following variables into the view
	 *   - {LABEL} the label
	 *   - {IMAGE} the image
	 *   - {ACTION} the URL
	 *   - {NAME} the name of this part
	 *   - {ISOPEN} true of the name matches the current traversal
	 * @param Event
	 * @param Traversal
	 */
	function onRender($e, $t) {
		$v = $t->viewGet();
		$v->pushText('NAME', $this->_name);
		$v->pushText('LABEL', $this->label_);
		if ($this->image_) {
			$v->pushText('IMAGE', $this->image_);
		}
		$url = $t->urlFromPath($this->actionPath_);
		$v->pushText('ACTION', $url);
		parent::onRender($e, $t);
	}

}

?>

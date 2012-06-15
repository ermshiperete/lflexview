<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package		ARK
 * @subpackage	Parts
 * @version		$Id: Page.php,v 1.1.1.1 2006/04/13 01:11:02 cambell Exp $
 * @author		Cambell Prince <cambell@arketec.com>
 * @link			http://www.arketec.com
 * @see
 */

/**
 */
require_once(SGF_CORE . 'Controller/Part.php');

/**
 * A Page is a controller that causes the View to render to the client.
 *
 * Note that Page is a controller; it accesses the model for update based on inputs in the event,
 * and selects data for display by the template (the view).
 *
 * Pages only render if they match the action path.
 * @package		ARK
 * @subpackage	Parts
 */
class Page extends Part {
	/**
	 * @var float
	 */
	var $_startTime;

	/**
	 * @var string
	 */
	var $_title;

	/**
	 * @var array
	 */
	private $_scriptFiles;

	/**
	 * @var array
	 */
	private $_cssFiles;

	/**
	 * @var array
	 */
	var $_scripts;

	/**
	 * Constructor
	 * Pages always render only if they follow the action path.
	 * @param string
	 * @param View
	 * @param string
	 * @access public
	 */
	function __construct($name, $viewProvider, $context = null) {
		parent::__construct($name, $viewProvider, null, DECK_FollowAction, $context);
		$this->_scriptFiles = array();
		$this->_scripts = array();
		$this->_cssFiles = array();
		$this->_startTime = $this->getTime();
	}

	/**
	 * Sets the Page in the Traversal.
	 * @param Traversal
	 */
	function onRenderEnter($t) {
		parent::onRenderEnter($t);
		$t->pageSet($this);
	}

	/**
	 * Sets Page variables in the View
	 * - TITLE
	 * - TEMPLATEPATH
	 * @see src/Controller/Part#onRender($e, $t)
	 */
	function onRender($e, $t) {
		if ($this->_view == null) {
			return;
		}
		parent::onRender($e, $t);
		// Render the css files
		$this->_view->pushText('CssFile', $this->renderCssFiles());
		// Render the scripts
		$this->_view->pushText('ScriptFiles', $this->renderScriptFiles());
		$this->_view->pushText('Scripts', $this->renderScripts());
		// Render page variables
		$this->_view->pushText('Title', $this->_title);
		$viewKit = ViewKit::singleton();
		//		$this->_view->pushText('TemplatePath', $viewKit->getTemplatePath()); // TODO review: no longer relevant?
	}

	/**
	 * Called by Traversal::leave after rendering the parts of this page.
	 * This delivers the output to the output stream by calling patTemplate::displayParsedTemplate.
	 * @access protected
	 */
	function onRenderLeave($t) {
		if ($this->_view == null) {
			return;
		}
		// Calculate the time
		$totalTime = $this->getTime() - $this->_startTime;
		$this->_view->pushText('TIME', round($totalTime, 3));
		// Render the template
		$this->_view->renderToClient();
	}

	/**
	 * Child Parts call this to add a css file to the page.
	 * @param string $filePath
	 */
	public function addCssFile($filePath) {
		if (!in_array($filePath, $this->_cssFiles)) {
			$this->_cssFiles[] = $filePath;
		}
	}

	/**
	 * Child Parts call this to add a javascript file to the page.
	 * @param string $filePath
	 */
	public function addJavaScriptFile($filePath) {
		if (!in_array($filePath, $this->_scriptFiles)) {
			$this->_scriptFiles[] = $filePath;
		}
	}

	/* TODO: addJavaScript Not yet complete
	 function addJavaScript($script) {
		$this->_scripts[] = $script;
		}
		*/

	protected function renderScriptFiles() {
		$ret = '';
		foreach ($this->_scriptFiles as $file) {
			$ret .= '<script type="text/javascript" src="' . $file . '" />' . "\n";
		}
		return $ret;
	}

	protected function renderCssFiles() {
		$ret = '';
		foreach ($this->_cssFiles as $file) {
			$ret .= '<link rel="stylesheet" type="text/css" href="' . $file . '" />' . "\n";
		}
	}

	protected function renderScripts() {
		// todo wip
		$ret = '';
		return $ret;
	}

	/**
	 * Gets processor time.
	 * @access private
	 */
	function getTime() {
		list($usec, $sec) = explode(" ",microtime());
		return ((float)$usec + (float)$sec);
	}

}

?>

<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package		ARK
 * @subpackage	View
 * @version    $Id: xtplview.php,v 1.2 2006/05/16 02:18:17 cambell Exp $
 * @author		  Cambell Prince <cambell@arketec.com>
 * @link				http://www.arketec.com
 * @see
 */

/**
 */
require_once (SGF_CORE.'View/IView.php');

/**
 * @package		ARK
 * @subpackage	View
 */
class PhpView implements IView {

	private $_filePath;

	/**
	 * A mixed array holding the key value pairs for this view.
	 * The values will be either string or IDataSpace.
	 * @var array mixed
	 */
	private $_vars; // Holds all the template variables

	/**
	 * Constructor
	 * @param string The file in which this template exists
	 * @param string The name of this template (or 'block') in the file
	 */
	function __construct($filePath) {
		$this->_filePath = $filePath;
		$this->_vars = array();
	}

	/**
	 * @see IView::getViewFilePath()
	 */
	function getViewFilePath() {
		return $this->_filePath;
	}

	/**
	 * @see IView::pushData()
	 */
	function pushData($key, $data) {
		$this->_vars[$key] = $data;
	}

	/**
	 * @see IView::pushDataAsText()
	 */
	function pushDataAsText($data, $prefix = '') {
		foreach ($data as $key=>$value) {
			$this->_vars[$prefix.$key] = $value;
		}
	}


	/**
	 * Push the variable $key into the View with the value $value.
	 * @param string key
	 * @param string value
	 * @param string The scope ('block') in which to apply this variable
	 */
	function pushText($key, $value, $scope = '') {
		$this->_vars[$key] = $value;
	}

	/**
	 * Add the variable $key into the View with the value $value.
	 * This will append $value to existing values.
	 * @param string $key
	 * @param string $value
	 */
	function addText($key, $value) {
		if (array_key_exists($key, $this->_vars)) {
			$this->_vars[$key] .= $value;
		} else {
			$this->_vars[$key] = $value;
		}
	}

	function renderToParent(&$t, $position) {
		$parent = &$t->getParentView();
		if ($parent) {
			$content = $this->renderToString();
			$parent->pushText($position, $content);
		}
	}

	/**
	 * Render to the client (browser)
	 */
	function renderToClient() {
		echo $this->renderToString();
	}

	/**
	 * Render to string
	 * Returns the View as a string. The optional $scope may be used to indicate which sub view
	 * to return if a View has more than one View. e.g. Email Views often have an alternate view.
	 * @param string
	 * @return string
	 */
	function renderToString($scope = null) {
		extract($this->_vars); // Extract the vars to local namespace
		ob_start();
		// Including the file will render it directly. Templates are mostly html
		include ($this->_filePath);
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}

	function onRenderEnter() {
		$this->_vars = array();
	}

	function onRenderLeave() {
	}

}

?>

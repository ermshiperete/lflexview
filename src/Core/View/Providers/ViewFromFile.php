<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package    ARK
 * @subpackage View
 * @author     Cambell Prince <cambell@arketec.com>
 * @link       http://www.arketec.com
 */

/**
 * @package		ARK
 * @subpackage	View
 */
require_once (SGF_CORE.'View/IViewProvider.php');

/**
 * Provides a View from a given file.
 */
class ViewFromFile implements IViewProvider {

	private $_srcFilePath;
	private $_templateName;

	/**
	 * @param string
	 */
	function __construct($srcFilePath, $templateName = '') {
		$this->_srcFilePath = $srcFilePath;
		$this->_templateName = $templateName;
	}

	/**
	 * @return IView
	 * @see IViewProvider::createView()
	 */
	function createView() {
		// Source filePath looks like MyFile.php => template MyFile.html.php
		$ret = ViewKit::viewFromCache($this->viewName());
		if ($ret == NULL) {
			$filePath = $this->_srcFilePath;
			if (!file_exists($filePath)) {
				Error::err(__FILE__, __LINE__, "Could not find the View file '$filePath'");
				return NULL;
			}
			$ret = ViewKit::createView(
			$this->viewName(),
			$filePath,
			$this->_templateName
			);
		}
		return $ret;
	}

	private function viewName() {
		return __CLASS__ . '_' . $this->_srcFilePath;
	}
}
?>
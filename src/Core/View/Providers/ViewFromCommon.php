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


class ViewFromCommon implements IViewProvider {

	private $_name;
	public $_commonDirPath;

	/**
	 *
	 */
	function __construct($name) {
		$this->_name = $name;
		$this->_commonDirPath = 'Look/Default/Views/';
	}

	/**
	 * @see IViewProvider::createView()
	 */
	function createView() {
		// Source filePath looks like MyFile.php => template MyFile.html.php
		$ret = ViewKit::viewFromCache($this->viewName());
		if ($ret == NULL) {
			$filePath = $this->_commonDirPath . $this->_name . '.html.php';
			if (!file_exists($filePath)) {
				Error::err(__FILE__, __LINE__, "Could not find the View file '$filePath'");
				return NULL;
			}
			$ret = ViewKit::createView($this->viewName(), $filePath, ''); // TODO Add support for templates again one day.
		}
		return $ret;
	}

	private function viewName() {
		return __CLASS__ . '_' . $this->_name;
	}
}
?>
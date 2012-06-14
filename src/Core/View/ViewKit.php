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
 */
//require_once (SGF_CORE.'Error.php');
require_once (SGF_CORE.'View/IView.php');
require_once (SGF_CORE.'View/Providers/ViewFromCommon.php');
require_once (SGF_CORE.'View/Providers/ViewFromFile.php');
require_once (SGF_CORE.'View/Providers/ViewFromSource.php');

/**
 * @package		ARK
 * @subpackage	View
 */
class ViewKit {

	/**
	 * @static
	 * @return ViewKit
	 */
	static function connect($driver) {
		// the defaults
		if (!$driver) {
			$driver = 'php';
		}
		switch ($driver) {
			case 'pat':
				$f = 'View/pat/patkit.php';
				$c = 'PatKit';
				break;
			case 'xtpl':
				$f = 'View/xtpl/xtplkit.php';
				$c = 'XtplKit';
				break;
			case 'php':
				$f = 'View/php/phpkit.php';
				$c = 'PhpKit';
				break;
		}
		if (!class_exists($c, FALSE)) {
			require_once (SGF_CORE.$f);
		}
		if (isset($GLOBALS['_viewkit']['active'])) {
			unset($GLOBALS['_viewkit']['active']);
		}
		$GLOBALS['_viewkit']['active'] = new $c;
		return $GLOBALS['_viewkit']['active'];
	}

	/**
	 * @static
	 * @return ViewKit
	 */
	static function singleton() {
		$ret = null;
		if (isset($GLOBALS['_viewkit']['active'])) {
			$ret = &$GLOBALS['_viewkit']['active'];
		} else {
			Error::err(__FILE__, __LINE__, 'ViewKit is not connected to a driver, consider ViewKit::connect in your App::init() in app.php');
		}
		return $ret;
	}

	/**
	 * Returns the View registered with $name.
	 * The requested View must have been previously registered with the ViewKit with a call to
	 * View::register. If the requested View is not found null is returned.
	 * @static
	 * @param string
	 * @return IView
	 * @see ViewKit::addToCache
	 */
	static function viewFromCache($name) {
		$ret = null;
		if (isset($GLOBALS['_viewcache'][$name])) {
			$ret = &$GLOBALS['_viewcache'][$name];
		}
		return $ret;
	}

	/**
	 * Creates the view and registers it with the ViewKit View cache.
	 * The construction of the View is delegated to the connected ViewKit driver.
	 * @param string $name
	 * @param string $filePath
	 * @param string $templateName
	 * @param string $dirPath
	 * @return IView
	 */
	static function createView($name, $filePath, $templateName) {
		if (isset($GLOBALS['_viewcache'][$name])) {
			Error::err(__FILE__, __LINE__, "$name already in cache");
			return $GLOBALS['_viewcache'][$name];
		}
		$kit = ViewKit::singleton();
		assert($kit != NULL);
		$GLOBALS['_viewcache'][$name] = $kit->createView($name, $filePath, $templateName);
		return $GLOBALS['_viewcache'][$name];
	}

	static function providerFromFile($srcFilePath, $templateName = '') {
		return new ViewFromFile($srcFilePath, $templateName);
	}

	static function providerFromSource($srcFilePath, $templateName = '') {
		return new ViewFromSource($srcFilePath, $templateName);
	}

	static function providerFromCommon($partName) {
		return new ViewFromCommon($partName);
	}

}

?>

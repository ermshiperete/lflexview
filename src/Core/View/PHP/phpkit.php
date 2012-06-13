<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package		ARK
 * @subpackage	View
 * @version    $Id: XtplKit.php,v 1.1.1.1 2006/04/13 01:11:02 cambell Exp $
 * @author		  Cambell Prince <cambell@arketec.com>
 * @link				http://www.arketec.com
 */

/**
 */
require_once(SGF_CORE . 'View/php/phpview.php');

/**
 * @package		ARK
 * @subpackage	View
 */
class PhpKit /*extends ViewKit*/ { // TODO this doesn't work in php5. Could implement IViewConnection.

private $_path;

function __construct() {
	$this->_path = '';
}

/**
 * Creates a PhpView view
 *
 * The template file is read from the $filePath relative to the current
 * template path.
 *
 * @param string View name
 * @param string Template id. Reference within a file
 * @param string Template file name.
 * @param string Template directory name.
 * @return PhpView
 *
 * @see setTemplatePath
 */
function createView($name, $fileName, $templateName) {
	return new PhpView($fileName, $this->_path);
}

/**
 * Alias for createView
 * @see createView
 * @param string View name
 * @param string Template id. Reference within a file
 * @param string Template file name.
 * @param string Template directory name.
 * @return PhpView
 */
function createPageView($name, $fileName, $templateName) {
	return new PhpView($fileName, $this->_path);
}

/**
 * Creates a PhpEmail view
 * @param string
 * @param string
 * @param string
 * @param string
 * @return PhpEmail
 * @todo This can be turned into a composite view implemented via a view decorator. see #17.
 * nyi
 */
function createEmailView($name, $src, $templateHTML, $templateText) {
	return null;
	//!!! NYI return new PhpEmail($src, $templateHTML, $templateText);
}
}

?>

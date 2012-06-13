<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package		ARK
 * @subpackage	Controller
 * @version    $Id: urlwriter.php,v 1.1.1.1 2006/04/13 01:11:01 cambell Exp $
 * @author		  Cambell Prince <cambell@arketec.com>
 * @link				http://www.arketec.com
 */

/**
 */
//require_once(SGF_CORE . 'Controller/actionpath.php');
//require_once(SGF_CORE . 'data/arrayspace.php');
//require_once(SGF_CORE . 'data/dotpath.php');
//require_once(SGF_CORE . 'data/session.php');

/**
 * @package		ARK
 * @subpackage	Controller
 */
class URLWriter {

	/**
	 * The base reference part of the URL. Most often set to PHP_SELF
	 * @var string
	 */
	private $_base;

	/**
	 * Constructor
	 * @param string
	 */
	function __construct($base) {
		$this->_base = $base;
	}

	/**
	 * Writes the URL
	 * Note that the action path is passed as a string.
	 * @param string
	 * @return string
	 */
	function write($action) {
		$ret = $this->_base . '?act=' . $action;
		return $ret;
	}

}

?>

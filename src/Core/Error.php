<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package		ARK
 * @subpackage	Data
 * @version		$Id: error.php,v 1.1.1.1 2006/04/13 01:11:01 cambell Exp $
 * @author		Cambell Prince <cambell@arketec.com>
 * @link			http://www.arketec.com
 */

/**
 */

/**
 * @package		ARK
 * @subpackage	Data
 */
class Error {
	var $t_; // dummy

	/**
	 * Sington accessor
	 * @static
	 * @return Error
	 */
	public static function singleton() {
		assert(is_a($GLOBALS['_error']['singleton'], 'Error'));
		return $GLOBALS['_error']['singleton'];
	}

	/**
	 * Connect the error implementation
	 * @param Error
	 * @static
	 */
	public static function connect($e) {
		$GLOBALS['_error']['singleton'] = $e;
	}

	/**
	 * Constructor
	 */
	function Error() {
	}

	/**
	 * Log a general messge
	 * @param string File
	 * @param string Line
	 * @param string
	 * @param mixed
	 * @static
	 */
	public static function log($f, $l, $s, $v = null) {
		$d = Error::singleton();
		if ($d) {
			$d->_log($f, $l, $s, $v);
		}
	}

	/**
	 * Log a debug messge
	 * @param string File
	 * @param string Line
	 * @param string
	 * @param mixed
	 * @static
	 */
	public static function debug($f, $l, $s, $v = null) {
		$d = Error::singleton();
		if ($d) {
			$d->_debug($f, $l, $s, $v);
		}
	}

	/**
	 * Log a warning messge
	 * @param string File
	 * @param string Line
	 * @param string
	 * @param mixed
	 * @static
	 */
	public static function warn($f, $l, $s, $v = null) {
		$d = Error::singleton();
		if ($d) {
			$d->_warn($f, $l, $s, $v);
		}
	}

	/**
	 * Log an error messge
	 * @param string File
	 * @param string Line
	 * @param string
	 * @param mixed
	 * @static
	 */
	public static function err($f, $l, $s, $v = null) {
		$d = Error::singleton();
		if ($d) {
			$d->_err($f, $l, $s, $v);
		}
	}

	/**
	 * Log an io messge
	 * @param string File
	 * @param string Line
	 * @param string
	 * @param mixed
	 * @static
	 */
	public static function io($f, $l, $s, $v = null) {
		$d = Error::singleton();
		if ($d) {
			$d->_io($f, $l, $s, $v);
		}
	}

	/**
	 * Implementation of debug
	 * @param string
	 * @access protected
	 * @see log
	 */
	function _debug($f, $l, $s, $v) {
	}

	/**
	 * Implementation of log
	 * @param string
	 * @access protected
	 * @see log
	 */
	function _log($f, $l, $s, $v) {
	}

	/**
	 * Implementation of warn
	 * @param string File
	 * @param string Line
	 * @param string
	 * @param mixed
	 * @see err
	 */
	function _warn($f, $l, $s, $v) {
	}

	/**
	 * Implementation of err
	 * @param string File
	 * @param string Line
	 * @param string
	 * @param mixed
	 * @see err
	 */
	function _err($f, $l, $s, $v) {
	}

	/**
	 * Implementation of io
	 * @param string File
	 * @param string Line
	 * @param string
	 * @param mixed
	 * @see io
	 */
	function _io($f, $l, $s, $v) {
	}

}
?>

<?php
//--------------------------------------------------------------------------------
// Based in part on software Copyright 2003 Procata, Inc.
// Released under the LGPL license (http://www.gnu.org/licenses/lesser.html)
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the LGPL license (http://www.gnu.org/licenses/lesser.html)
//--------------------------------------------------------------------------------
/**
 * @package		ARK
 * @subpackage	Controller
 * @version      $Id: handle.php,v 1.1.1.1 2006/04/13 01:11:01 cambell Exp $
 * @author		Cambell Prince <cambell@arketec.com>
 * @link			http://www.arketec.com
 * @see
 */


/**
 * A Handle represents an uninstantiated object that takes the place of a
 * given object and can be swapped out for the object.
 * Implements lazy loading for composing object hierarchies.
 * @todo Track the WACT list for issues with zend optimiser
 * @package		ARK
 * @subpackage	Controller
 * @see http://wact.sourceforge.net/index.php/ResolveHandle
 */
class Handle {
	/**
	 * @var string
	 */
	var $file_;

	/**
	 * @var string
	 */
	var $class_;

	/**
	 * @var array
	 */
	var $args_;

	/**
	 * Constructor takes the file in which exists the implementation of class.
	 * Any arguments to the constructor of class are given in the array $args.
	 * @param string
	 * @param string
	 * @param array
	 */
	function __construct($file, $class, $args = array()) {
		$this->file_ = $file;
		$this->class_ = $class;
		$this->args_ = $args;
	}

	/**
	 * Resolves the given handle to the real class
	 * The handle is resolved only if it is a handle.  If the handle has been previously resolved,
	 * a reference to $handle is returned.
	 * @param Handle
	 * @return mixed
	 */
	public static function resolve($handle) {
		$ret = null;
		if (is_a($handle, 'Handle')) {
			$class = $handle->class_;
			if (!class_exists($class, FALSE)) {
				if ($handle->file_) {
					require_once($handle->file_);
				} else {
					//!!! Can add autoload back in if required CJP 7/3/2005
					//					$file = Config::getOptionAsPath('autoload', 'handle', $class);
					//					if (!is_null($file)) {
					//						require_once($file);
					//					}
				}
			}
			if (class_exists($class, FALSE)) {
				$a = $handle->args_;
				$args = "";
				$l = count($handle->args_);
				for ($i = 0; $i < $l; $i++) {
					if ($i > 0) {
						$args .= ',';
					}
					$args .= '$a[' . $i. ']';
				}
				$code = '$ret = new '.$class.'(' . $args . ');';
				eval($code);
			} else {
				Error::err(__FILE__, __LINE__, "Class '$handle->class_' not found in file '$handle->file_'");
			}
		} else {
			$ret = $handle;
		}
		return $ret;
	}

}

?>

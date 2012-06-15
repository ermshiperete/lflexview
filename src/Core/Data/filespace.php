<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package		ARK
 * @subpackage	Data
 * @version    $Id: arrayspace.php,v 1.1.1.1 2006/04/13 01:11:01 cambell Exp $
 * @author		  Cambell Prince <cambell@arketec.com>
 * @link				http://www.arketec.com
 */

/**
 */
require_once(SGF_CORE . 'Data/Arrayspace.php');

/**
 * The FileDataSpace is a container for a set of named key value pairs (basically an associative array).
 * Data is stored in the file as:
 *   key: value
 * Any white space before or after value is stripped.
 * Meta: The FileDataSpace has the following meta information:
 * 	File	The file name of the data source.
 * Axis: The FileDataSpace may have other axis set. e.g. 'schema'
 * @package ARK
 * @subpackage Data
 * @access public
 */
class FileSpace extends ArraySpace {

	function FileSpace() {
		$this->ArraySpace();
	}

	/**
	 * Reads data from the file given by $filePath
	 *
	 * @param string The file name to read.
	 * @see src/Data/DataSpace#read($id)
	 */
	function read($filePath) {
		$this->setMeta('File', $filePath);
		$lines = file($filePath);
		foreach ($lines as $lineNumber => $line) {
			list($key, $value) = split(':', $line);
			// Sanity check on $key
			if ($key) {
				$value = trim($value);
				$this->set($key, $value);
			} else {
				Error::err(__FILE__, __LINE__, "Empty key in file '$filePath' at line $lineNumber");
			}
		}
	}

	function write() {
		$filePath = $this->getMeta('File');
		$f = fopen($filePath, 'w');
		if ($f) {
			foreach ($this->_values as $key => $value) {
				fwrite($f, "$key: $value\n");
			}
			fclose($f);
		} else {
			Error::err(__FILE__, __LINE__, "Unable to open '$filePath' for write");
		}
	}

}

?>
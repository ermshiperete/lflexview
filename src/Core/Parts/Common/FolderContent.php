<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package		ARK
 * @subpackage	Parts
 * @version    $Id: buttoncontrol.php,v 1.1.1.1 2006/04/13 01:11:01 cambell Exp $
 * @author		  Cambell Prince <cambell@arketec.com>
 * @link				http://www.arketec.com
 */

/**
 */
require_once(SGF_CORE . 'Controller/part.php');

/**
 * A FileContent part
 * @package		ARK
 * @subpackage	Parts
 */
class FolderContent extends Part {
	var $_data;
	var $_content;
	var $fileName;

	function FolderContent() {
		$this->_extensions = array();
		$this->_extensions[] = '.html.php';

		$this->_data = new FileSpace();
		$this->_content = new PhpView('FileContentView', '', '.');

		$this->fileName = '';
	}

	function onRender(&$e, &$t) {
		// foreach key in data push into view
		$metaFilePath = $this->fileName . '.meta';
		$templateFilePath = $this->fileName . '.html.php';
		$this->_data->read($metaFilePath);
		$this->_content->_fileName = $templateFilePath;
		$it = $this->_data->iterator();
		for ($it->rewind(); $it->isValid(); $it->next()) {
			$this->_view->pushText($it->key(), $it->current(), '');
		}
	}
}

?>
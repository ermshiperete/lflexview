<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package		ARK
 * @subpackage	Controller
 * @version    $Id: URLMapper.php,v 1.1.1.1 2006/04/13 01:11:01 cambell Exp $
 * @author		  Cambell Prince <cambell@arketec.com>
 * @link				http://www.arketec.com
 */

/**
 */
//require_once(SGF_CORE . 'Controller/actionpath.php');
//require_once(SGF_CORE . 'Data/arrayspace.php');
//require_once(SGF_CORE . 'Data/dotpath.php');
//require_once(SGF_CORE . 'Data/session.php');
require_once(SGF_CORE.'Controller/IURLMapper.php');

/**
 * @package		ARK
 * @subpackage	Controller
 */
class URLMapper implements IURLMapper {

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
	 * @return bool
	 */
	function canDoSessionKey() {
		return false;
	}
	
	/**
	 * @return string
	 */
	function readSessionKey() {
		return '';
	}

	/**
	 * @param string $sessionKey
	 * @return void
	 */
	function writeSessionKey($sessionKey) {
		// Do nothing
	}
	
	/**
	 * @return ActionPath
	 */
	function readPartPath() {
		$path = $_REQUEST['act'];
		$paths = explode('.', $path);
		return new ActionPath($paths);
	}

	/**
	 * @param ActionPath $actionPath
	 * @return void
	 */
	function writePartPath($actionPath) {
		$paths = implode('.', $actionPath->getPaths());
		$path = 'act=' . $paths;
		return $path;
	}
	
	/**
	 * @return Command
	 */
	function readCommand() {
		
	}

	/**
	 * @param Command $command
	 * @return void
	 */
	function writeCommand($command) {
	}

}

?>

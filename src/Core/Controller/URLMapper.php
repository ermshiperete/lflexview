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
//require_once(SGF_CORE . 'Controller/ActionPath.php');
//require_once(SGF_CORE . 'Data/Arrayspace.php');
//require_once(SGF_CORE . 'Data/Dotpath.php');
//require_once(SGF_CORE . 'Data/Session.php');
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
		if (!isset($_REQUEST['act'])) {
			return NULL;
		}
		$path = $_REQUEST['act'];
		$paths = explode('.', $path);
		return new ActionPath($paths);
	}

	/**
	 * @param ActionPath $actionPath
	 * @return void
	 */
	function writePartPath($partPath) {
		$paths = implode('.', $partPath->getPaths());
		$url = 'act=' . $paths;
		return $url;
	}
	
	/**
	 * @return Command
	 */
	function readCommand() {
		if (!isset($_REQUEST['cmd'])) {
			return NULL;
		}
		$tokens = explode(':', $_REQUEST['cmd']);
		return new Command($tokens[0], array_slice($tokens, 1));
	}

	/**
	 * @param Command $command
	 * @return string
	 */
	function writeCommand($command) {
		if ($command == NULL) {
			return '';
		}
		$url = '&cmd=' . $command->name;
		foreach ($command->args as $arg) {
			$url .= ':' . $arg;
		}
		return $url;
	}
	
	/**
	 * @see IURLMapper::write()
	 */
	function write($partPath, $command) {
		return $this->_base . '?' . $this->writePartPath($partPath) . $this->writeCommand($command);
	}

}

?>

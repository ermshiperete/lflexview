<?php

interface IURLMapper
{
	/**
	 * @return bool
	 */
	function canDoSessionKey();
	
	/**
	 * @return string
	 */
	function readSessionKey();

	/**
	 * @param string $sessionKey
	 * @return void
	 */
	function writeSessionKey($sessionKey);
	
	/**
	 * @return ActionPath
	 */
	function readPartPath();

	/**
	 * @param ActionPath $actionPath
	 * @return void
	 */
	function writePartPath($actionPath);
	
	/**
	 * @return Command
	 */
	function readCommand();

	/**
	 * @param Command $command
	 * @return void
	 */
	function writeCommand($command);
	
	/**
	 * @param ActionPath $partPath
	 * @param Command $command
	 */
	function write($partPath, $command);
	
}

?>
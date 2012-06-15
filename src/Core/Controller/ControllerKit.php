<?php

require_once(SGF_CORE.'Controller/ActionPath.php');

class ControllerKit 
{
	static $_urlMapper;
	
	/**
	 * Connects to the default url mapper scheme. act and cmd on the url.
	 * @return void
	 */
	static function connectURLMapper($basePath) {
		require_once(SGF_CORE.'Controller/URLMapper.php');
		self::$_urlMapper = new URLMapper($basePath);
	}
	
	/**
	 * Returns the url mapper
	 * @return IURLMapper
	 */
	static function urlMapper() {
		if (self::$_urlMapper == NULL) {
			self::connectURLMapper();
		}
		return self::$_urlMapper;
	}
	
}

?>
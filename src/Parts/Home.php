<?php

class Home extends Part{
	
	function __construct() {
		parent::__construct('Home', ViewKit::providerFromCommon('Home'));
	}
	
}

?>
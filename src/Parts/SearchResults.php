<?php

class SearchResults extends Part{
	
	function __construct() {
		parent::__construct('SearchResults', ViewKit::providerFromCommon('SearchResults'));
	}
	
}

?>
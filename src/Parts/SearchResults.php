<?php

class SearchResults extends Part{
	
	function __construct() {
		parent::__construct('SearchResults', ViewKit::providerFromCommon('SearchResults'));
	}
	
	function onRender($e, $t) {
		$space = $t->dataGet();
		$this->_view->pushData('SearchResults', $space);
		parent::onRender($e, $t);
	}
	
}

?>
<?php

require_once(SGF_CORE.'Parts/Common/Form.php');

class SearchForm extends Form
{
	function __construct() {
		parent::__construct(
			'SearchForm', 
			ViewKit::providerFromCommon('SearchForm'), 
			'SearchForm', 
			DataKit::providerFromExistingData(new SimpleValueSpace())
		);
		$controlProvider = ViewKit::providerFromCommon('Control');
		$this->addChild(PartKit::textControl('SearchText', $controlProvider, 'SearchTextControl', $this, 'id', 'Search text', 20));
		$this->addChild(PartKit::buttonControl('save', ViewKit::providerFromCommon('ControlButton'), 'ButtonControl', $this, 'idb', 'Search'));
	}
	
}

?>

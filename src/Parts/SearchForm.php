<?php

require_once(SGF_CORE.'Parts/Common/Form.php');

class SearchForm extends Form
{
	public function __construct() {
		parent::__construct(
			'SearchForm', 
			ViewKit::providerFromCommon('SearchForm'), 
			'SearchForm', 
			DataKit::providerFromExistingData(new SimpleValueSpace())
		);
		$controlProvider = ViewKit::providerFromCommon('Control');
		$this->addChild(PartKit::textControl('SearchText', $controlProvider, 'SearchTextControl', $this, 'id', 'Online Dictionary Search', 20));
		$this->addChild(PartKit::buttonControl('save', ViewKit::providerFromCommon('ControlButton'), 'ButtonControl', $this, 'idb', 'Search'));
	}
	
	/**
	 * @see Form::runSave()
	 * @param Event
	 * @param Traversal
	 */
	public function runSave($e, $t) {
		require_once('Search/GetWordsForAutoSuggestCommand.php');
		//$search = 'ฉู่ฉี่หมูกรแบ';
		$search = $this->_dataSpace->get('SearchText');
		$command = new commands\GetWordsForAutoSuggestCommand(APP_LiftFilePath, LANG_IPA, $search, 0, 50);
		$space = $command->execute();
		$t->dataSet($space);
		EventQueue::singleton()->addEvent(new Event(EVT_Render, ActionPath::fromString('Page/SearchResults')));
	}
	
}

?>

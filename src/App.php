<?php

require_once(dirname(__FILE__) . '/Config.php');

require_once(SGF_CORE.'Controller/FrontController.php');
require_once(SGF_CORE.'Data/DataKit.php');
require_once(SGF_CORE.'Data/SimpleValueSpace.php');
require_once(SGF_CORE.'Parts/PartKit.php');
require_once(SGF_CORE.'View/ViewKit.php');

require_once('AppError.php');

Error::connect(new ApplicationError());

class Application extends FrontController
{
	private $_stateSpace;
	
	public function Application() {
		$this->_stateSpace = new SimpleValueSpace();
		
		ViewKit::connect('php');

		$this->setDefaultAction('Page');
		
		$page = PartKit::page('Page', ViewKit::providerFromCommon('Page'));

		$searchForm = PartKit::form(
			'SearchForm', 
			ViewKit::providerFromCommon('SearchForm'),
			'SearchForm',
			DataKit::providerFromExistingData(new SimpleValueSpace())
		);
		$page->addChild($searchForm);
		
		$page->addChild(PartKit::handler('Home', 'Parts/Home.php'));
		$page->addChild(PartKit::handler('SearchResults', 'Parts/SearchResults.php'));
		$page->addChild(PartKit::handler('LexEntry', 'Parts/LexEntry.php'));
		
		$this->addChild($page);
	}
	
	/**
	 * @return IDataSpace
	 */
	protected function getStateSpace() {
		return $this->_stateSpace;
	}

	
}

$app = new Application();
$app->run();

?>
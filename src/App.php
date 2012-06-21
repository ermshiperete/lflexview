<?php

require_once(dirname(__FILE__) . '/Config.php');

require_once(SGF_CORE.'Controller/ControllerKit.php');
require_once(SGF_CORE.'Controller/FrontController.php');
require_once(SGF_CORE.'Data/DataKit.php');
require_once(SGF_CORE.'Data/SimpleValueSpace.php');
require_once(SGF_CORE.'Data/ListSpace.php');
require_once(SGF_CORE.'Parts/PartKit.php');
require_once(SGF_CORE.'View/ViewKit.php');

require_once('AppError.php');
require_once('Parts/SearchForm.php');

Error::connect(new ApplicationError());

class Application extends FrontController
{
	private $_stateSpace;
	
	public function Application() {
		$this->_stateSpace = new SimpleValueSpace();
		
		ControllerKit::connectURLMapper('');
		ViewKit::connect('php');

		$this->setDefaultAction(ActionPath::fromString('Page/Home'));
		
		$page = PartKit::page('Page', ViewKit::providerFromCommon('Page'));

		$searchForm = new SearchForm();
		$page->addChild($searchForm);
		
		$page->addChild(PartKit::handler('Home', 'Parts/Home.php'));
		$page->addChild(PartKit::handler('SearchResults', 'Parts/SearchResults.php'));
		$page->addChild(PartKit::handler('LexicalEntry', 'Parts/LexicalEntry.php'));
		
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
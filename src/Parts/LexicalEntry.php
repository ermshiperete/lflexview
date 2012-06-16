<?php

class LexicalEntry extends Part{
	
	/**
	 * @var IDataSpace
	 */
	private $_lexicalEntry;
	
	function __construct() {
		parent::__construct('LexicalEntry', ViewKit::providerFromCommon('LexicalEntry'));
	}
	
	public function onAction($e, $t) {
		// Read the entry
		$command = $t->getCommand();
		switch ($command->name)
		{
			case 'read':
				require_once('Search/GetWordCommand.php');
				$guid = count($command->args) > 0 ? $command->args[0] : NULL;
				$command = new commands\GetWordCommand(APP_LiftFilePath, $guid);
				$space = $command->execute();
				$this->_lexicalEntry = $space;
		}
	}
	
	public function onRender($e, $t) {
		$this->_view->pushData('LexicalEntry', $this->_lexicalEntry);
	}
	
}

?>
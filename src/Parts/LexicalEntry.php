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
				// Fix the image refs for our path
				foreach ($space->getSpace('senses') as $sense) {
					$image = $sense->get('image');
					if ($image) {
						if (strlen(APP_LiftImagesFolder) > 0 && strstr($image, APP_LiftImagesFolder) === FALSE) {
							$image = APP_LiftImagesFolder . $image;
						}
						$image = APP_LiftImageURL . $image;
						$sense->set('image', $image);
					}
				}
				
				$this->_lexicalEntry = $space;
		}
	}
	
	public function onRender($e, $t) {
		$this->_view->pushData('LexicalEntry', $this->_lexicalEntry);
	}
	
}

?>
<?php

namespace commands;
require_once(dirname(__FILE__) . '/../Config.php');

class GetWordsForAutoSuggestCommand
{
	/**
	 * @var IDataSpace
	 */
	var $_dto;

	function __construct($filePath, $language, $search, $indexFrom, $indexTo) {
		$this->_filePath = $filePath;
		$this->_language = $language;
		$this->_search = $search;
		$this->_indexFrom = $indexFrom;
		$this->_indexTo = $indexTo;
		$this->_dto = new \ListSpace();
	}

	function execute() {
		$this->processFile();
		return $this->_dto;
	}

	function processFile() {
		$reader = new \XMLReader();

		$reader->open($this->_filePath,"UTF-8");
		$closestword = array();
		$this->_dto->entryCount = 0;
		//TODO XZ 2012-02: perfemance problem! why read all?
		while ($reader->read()) {
			switch ($reader->nodeType) {
				case (\XMLREADER::ELEMENT):
					if ($reader->name == "entry") {
						$node = $reader->readOuterXml();
						$XMLdata = simplexml_load_string($node);
						$lexicalForms = $XMLdata->{'lexical-unit'};
						if ($lexicalForms) {
							//if(((string)$XMLdata->{'lexical-unit'}->form->lang)== $this->_language){
							foreach ($lexicalForms->{'form'} as $form) {
								if(($form['lang'])== $this->_language){
									$sourceText=(string)$form->text;
									//find the closest
									$similarity = 0.0;
									$simtext = similar_text($this->_search, $sourceText, &$similarity);
									if ($similarity >= 60.0) {
										// set the closest match, and shortest distance
										$closestword[] = $XMLdata;
									}
								}
							}
						}
					}
			}
		}
		$reader->close();

		if(isset($closestword)) {
			$this->_dto->entryCount = count($closestword);
		}

		// Closest Word Match based upon the Request Count
		for ($i = $this->_indexFrom; $i <= $this->_indexTo; $i++) {
			if (isset($closestword[$i])) {
				$this->processModelFromNode($closestword[$i]);
			}
		}
	}

	function processModelFromNode($node) {

		$guid = (string)$node['guid'];
		$lexicalForms = $node->{'lexical-unit'};
		$entryDTO = new \ListSpace();
		$entryDTO->setID($guid);
		$multiText = $this->readMultiText($lexicalForms);
		$entryDTO->setSpace('lexical-unit', $multiText);
		$this->_dto->setSpace($guid, $entryDTO);
	}

	function readMultiText($node) {
		$multiText = new \SimpleValueSpace();
		foreach ($node->{'form'} as $form) {
			$multiText->set((string)$form['lang'], (string)$form->{'text'});
		}
		return $multiText;
	}

}
?>
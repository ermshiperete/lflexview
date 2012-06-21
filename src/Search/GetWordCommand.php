<?php
/**
 * Class to Get a word from the LIFT
 * LanguageForge Dictionary API
*/


namespace commands;
require_once(dirname(__FILE__) . '/../Config.php');

class GetWordCommand  
{
	/**
	 * @var LexWordDTO
	 */
	var $_model;
	
	function __construct($filePath, $guid) {   
		$this->_filePath = $filePath; // Path to the LIFT file
		$this->_guid = $guid;
		$this->_dto = new \ListSpace();
		$this->_dto->setID($this->_guid);
	}
	
	function execute() {
		$this->processFile($this->_guid);	
		//$this->_dto->setMercurialSHA(\lfbase\common\HgWrapper::getHgHashShort($this->GetPathOnly($this->_filePath)));
		return $this->_dto;
	}
	
	function GetPathOnly($file) {
		$filepath = substr($file, 0,strrpos($file,'/'));
		return $filepath;
	}
	
	function processFile($guid) {
				
		$reader = new \XMLReader();  
		
		$reader->open($this->_filePath);
		
		while ($reader->read()) {
		    switch ($reader->nodeType) {   
		        case (\XMLREADER::ELEMENT):
		        if ($reader->localName == "entry") {   // Reads the LIFT file and searches for the entry node
					if ($reader->getAttribute("guid") == $guid) { // Searches for a particular guid attribute in the entry 
		                $node = $reader->expand(); // expands the node for that particular guid 
		                $dom = new \DomDocument();
		                $n = $dom->importNode($node,true);
		                $dom->appendChild($n);
		                $sxe = simplexml_import_dom($n); 
		              	$this->processModelFromNode($sxe);
		            }
		        }
		    }
		}
	}
	
	function processModelFromNode($node) {
	
		$lexicalForms = $node->{'lexical-unit'};
		if ($lexicalForms) {
			$this->_dto->setSpace('lexicalUnit', $this->readMultiText($lexicalForms));
			if(isset($node->{'sense'})) {
				$senses = new \SimpleListSpace();
				$this->_dto->setSpace('senses', $senses);
				$i = 0;
				foreach ($node->{'sense'} as $sense) {
					$senses->setSpace('sense'.$i++, $this->readSense($sense));
				}
			}
			// else {
			//		$definition = $node->addChild('sense');
			//		$definition->definition->form['lang'] = 'en';
			//		$this->_dto->addSense($this->readSense($definition));
			//}
		}
	}

	function readSense($node) {
		$sense = new \TreeSpace();
		
		// Definition
		if (isset($node->{'definition'})) {
			$definition = $node->{'definition'};
			$sense->setSpace('definition', $this->readMultiText($definition));
		}
		
		// Part Of Speech
		if (isset($node->{'grammatical-info'})) {
			$partOfSpeech = (string)$node->{'grammatical-info'}->attributes()->value;
			$sense->set('partOfSpeech', $partOfSpeech);
		}
		
		// Illustration
		if (isset($node->{'illustration'})) {
			$illustration = (string)$node->{'illustration'}['href'];
			$sense->set('image', $illustration);
		}
		
		//Semantic Domain // TODO This is bogus.  Check the trait name CP 2012-06
/*		if(isset($node->{'trait'})) {
			$semanticDomainName = (string)$node->{'trait'}->attributes()->name;
			$semanticDomainValue = (string)$node->{'trait'}->attributes()->value;
			$sense->setSemanticDomainName($semanticDomainName);
			$sense->setSemanticDomainValue($semanticDomainValue);
		}
*/		
		//Examples
		$examples = $node->{'example'};		
		if ($examples) {
			$examplesDTO = new \SimpleListSpace();
			$sense->setSpace('examples', $examplesDTO);
			$i = 0;
			foreach ($examples as $example) {
				$examplesDTO->setSpace('example'.$i++, $this->readExample($example));
			}
		}
				
		return $sense;
	}
	
	function readExample($node) {		
		$example = new \ListSpace();
		
		// Example multitext		
		$exampleXml = $node;
		$example->setSpace('example', $this->readMultiText($exampleXml));
		// Translation multitext
		$translationXml = $node->{'translation'};
		if(!empty($translationXml)) {
			$example->setSpace('translation', $this->readMultiText($translationXml));
		}
		// This sets an empty translation node which is required by the editor it seems.
		//} else {
		//	$translation = $node->addChild('translation');
		//	$translation->form['lang'] = 'en';
		//	$example->setTranslation($this->readMultiText($translation));
		//}
		return $example;
	}
 
	function readMultiText($node) {
		$multiText = new \SimpleValueSpace();
		foreach ($node->{'form'} as $form) {
			$multiText->set((string)$form['lang'], (string)$form->{'text'});
		}
		return $multiText;
	}
	
	
};

?>

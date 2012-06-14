<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package    SayGoForms
 * @subpackage Data
 * @author     Cambell Prince <cambell@arketec.com>
 * @link       http://www.arketec.com
 */

/**
 */
require_once (SGF_CORE.'Data/IDataProvider.php');

/**
 * Provides an IDataSpace from a given IDataSpace.
 * A trivial implementation.
 */
class DataProviderFromData implements IDataProvider {
	/**
	 * @var IDataSpace
	 */
	private $_existingData;

	/**
	 * @param IDataSpace
	 */
	function __construct($existingData) {
		$this->_existingData = $existingData;
	}

	/**
	 * @see IDataProvider::provideData()
	 */
	function provideData($traversal) {
		assert($this->_existingData != NULL);
		$this->_existingData->reset();
		return $this->_existingData;
	}

	/**
	 * @see IDataProvider::provideDataAndRead()
	 */
	// TODO This doesn't seem to be necessary.  Who uses it?
	function provideDataAndRead($traversal) {
		assert($this->_existingData != NULL);
		if (!$this->_existingData->read()) {
			$this->_existingData->reset();
		}
		return $this->_existingData;
	}


}
?>
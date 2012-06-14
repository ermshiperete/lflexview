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
class DataProviderFromTraversal implements IDataProvider {
	/**
	 * @param IDataSpace
	 */
	function __construct() {
	}

	/**
	 * @see IDataProvider::provideData()
	 */
	function provideData($traversal) {
		$data = $traversal->dataGet();
		$data->reset();
		return $data;
	}

	/**
	 * @see IDataProvider::provideDataAndRead()
	 */
	function provideDataAndRead($traversal) {
		$data = $traversal->dataGet();
		if (!$data->read()) {
			$data->reset();
		}
		return $data;
	}


}
?>
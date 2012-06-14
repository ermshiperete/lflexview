<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package    ARK
 * @subpackage Data
 * @author     Cambell Prince <cambell@arketec.com>
 * @link       http://www.arketec.com
 */

/**
 */
require_once (SGF_CORE.'Data/IDataStore.php');
require_once (SGF_CORE.'Data/TestValueMapper.php');
require_once (SGF_CORE.'Data/schema.php');

/**
 *
 */
class TestListMapper implements IDataStore {

	/**
	 */
	function __construct() {
	}

	/**
	 * @see IDataStore::delete()
	 */
	function delete($schema, $id) {
		throw new Exception("NYI");
	}

	/**
	 * @see IDataStore::deleteAll()
	 */
	function deleteAll($schema) {
		throw new Exception("NYI");
	}


	/**
	 * @see IDataStore::query()
	 */
	function query($data, $schema) {
		throw new Exception("Should not be called");
	}

	/**
	 * @see IDataStore::update()
	 */
	function update($data) {
		throw new Exception("NYI");
	}

	/**
	 * @see IDataStore::read()
	 */
	function read($data, $schema, $id) {
		assert($schema);
		assert($data);

		// Add a SimpleValueSpace to $data for say 5 rows.
		for ($i = 0; $i < 5; $i++) {
			$space = new SimpleValueSpace(); // review: This may need to be a ValueSpace for some applications
			foreach ($schema as $key => $field) {
				$space->set($key, TestValueMapper::sampleData($field));
			}
			$data->setSpace($i, $space);
		}
		return TRUE;
	}

	/**
	 * @see IDataStore::write()
	 */
	function write($data) {
		throw new Exception("NYI");
		return 0;
	}

}

?>

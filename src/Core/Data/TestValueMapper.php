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
require_once (SGF_CORE.'Data/schema.php');

/**
 *
 */
class TestValueMapper implements IDataStore {

	/**
	 */
	function __construct() {
	}

	/**
	 * @see IDataStore::delete()
	 */
	function delete($schema, $id) {
		throw new Exception("NYI");
		return FALSE;
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
		return FALSE;
	}

	/**
	 * @see IDataStore::read()
	 */
	function read($data, $schema, $id) {
		assert($schema);
		assert($data);

		foreach ($schema as $key => $field) {
			$data->set($key, self::sampleData($field));
		}
		$data->setID($id);
		return TRUE;
	}

	public static function sampleData($field) {
		switch ($field->get(FLD_Type)) {
			case DT_DateTime:
				return "2009-10-08 08:05:03";
			case DT_Text:
				return "Example ".strtoupper($field->get(FLD_Label));
			case DT_Currency:
				return rand(10, 1000);
			case DT_Number:
				return rand(10, 1000);
			case DT_Email:
				return "example@saygoweb.com";
			case DT_PID:
				return 4;
			case DT_ID:
				return 3;
			case DT_Phone:
				return "+64 9 758 1932";
		}
		return "Example Data";
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

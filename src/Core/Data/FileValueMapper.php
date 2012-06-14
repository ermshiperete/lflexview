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
require_once (SGF_CORE.'Data/IDataStore.php');
require_once (SGF_CORE.'Data/schema.php');

/**
 *
 */
class FileValueMapper implements IDataStore {

	function __construct() {
	}

	/**
	 * @see IDataStore::delete()
	 */
	function delete($schema, $id) {
		return $this->deleteAll($schema);
	}

	/**
	 * Deletes all rows in the table.
	 * Uses:
	 * 	SC_Meta_Source
	 * @see IDataStore::deleteAll()
	 */
	function deleteAll($schema) {
		// TODO refactor this to pass in $data (similarly for delete)
		//		$src = $data->getMeta(SC_Meta_Source);
		//		return unlink($src);
		return FALSE;
	}


	/**
	 * @see IDataStore::query()
	 */
	function query($data, $schema) {
		throw new Exception("Should not be called");
	}

	/**
	 * Updates the data in the file.
	 * Calls write
	 * Uses:
	 * 	data->getSchema()
	 * 	SC_Meta_Source
	 * @see IDataStore::update()
	 * @see write()
	 */
	function update($data) {
		return $this->write($data);
	}

	/**
	 * @see IDataStore::read()
	 */
	function read($data, $schema, $id) {
		assert($data);
		$src = $data->getMeta(SC_Meta_Source);
		if (!file_exists($src)) {
			return FALSE;
		}
		$f = file($src);
		foreach ($f as $line) {
			$pair = split(':', $line, 2);
			$data->set($pair[0], trim($pair[1], " \r\n\t"));
		}
		return TRUE;
	}

	/**
	 * Writes data to the file.
	 * Uses:
	 * 	data->getSchema()
	 * 	SC_Meta_Source
	 * @see IDataStore::write()
	 */
	function write($data) {
		$src = $data->getMeta(SC_Meta_Source);
		$f = fopen($src, 'w');
		foreach ($data as $key => $value) {
			fwrite($f, $key . ': ' . $value . "\n");
		}
		fclose($f);
		return 1; // What should we return? There is no id in this DataMapper.
	}

}

?>

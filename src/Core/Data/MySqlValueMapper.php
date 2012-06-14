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
class MySqlValueMapper implements IDataStore {

	/**
	 * The mysql handle
	 * @var mysqli
	 */
	private $_handle;

	/**
	 * @param mysqli
	 */
	function __construct($handle) {
		$this->_handle = $handle;
	}

	/**
	 * @see IDataStore::delete()
	 */
	function delete($schema, $id) {
		$src = $schema->get(SC_Meta_Source);
		$primary = $schema->get(SC_Meta_Primary);
		$sql = "DELETE FROM `$src` WHERE $primary='$id'";
		$result = mysqli_query($this->_handle, $sql);
		if (!$result) {
			throw new Exception("No result from '$sql'");
		}
		// TODO more inspection of $result. affected rows perhaps
		return TRUE;
	}

	/**
	 * Deletes all rows in the table.
	 * Uses:
	 * 	SC_Meta_Source
	 * @see IDataStore::deleteAll()
	 */
	function deleteAll($schema) {
		$src = $schema->get(SC_Meta_Source);
		$sql = "TRUNCATE TABLE `$src`";
		$result = mysqli_query($this->_handle, $sql);
		if (!$result) {
			throw new Exception("No result from '$sql'");
		}
		// TODO more inspection of $result
	}


	/**
	 * @see IDataStore::query()
	 */
	function query($data, $schema) {
		throw new Exception("Should not be called");
	}

	private function querySql($data, $sql) {
		assert(is_a($data, 'SimpleValueSpace'));
		$result = mysqli_query($this->_handle, $sql);
		if (!$result) {
			throw new Exception("No result from '$sql'");
		}
		$i = 0;
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			if ($i == 0) {
				$data->import($row);
			} else {
				mysqli_free_result($result);
				throw new Exception("More than one result from '$sql'");
			}
			$i++;
		}
		mysqli_free_result($result);
		return $i > 0;
	}

	/**
	 * Updates the data in the MySql table from the schema.
	 * Uses:
	 * 	data->getSchema()
	 * 	SC_Meta_Source
	 * 	SC_Meta_Primary
	 * 	SC_Meta_ID
	 * @see IDataStore::update()
	 */
	function update($data) {
		$schema = $data->getSchema();
		$src = $schema->get(SC_Meta_Source);
		$primary = $schema->get(SC_Meta_Primary);
		$id = $data->getID();
		// TODO could do some data / schema sanity checks here. $id, $primary must be good
		$set = $this->setSQL($data, $schema, $primary);
		$sql = "UPDATE `$src` SET $set WHERE $primary='$id'";
		$result = mysqli_query($this->_handle, $sql);
		// TODO check affected rows
		if (!$result) {
			$why = mysqli_error($this->_handle);
			throw new Exception("No result from '$sql'\n" . $why);
		}
		return $id;
	}

	/**
	 * @see IDataStore::read()
	 */
	function read($data, $schema, $id) {
		assert($schema);
		assert($data);
		$src = $schema->get(SC_Meta_Source);
		$primary = $schema->get(SC_Meta_Primary);
		$sql = "SELECT * FROM `$src` WHERE $primary='$id' LIMIT 1";
		$result = $this->querySql($data, $sql);
		if ($result) {
			$data->setID($id);
		}
		return $result;
	}

	/**
	 * @see IDataStore::write()
	 */
	function write($data) {
		$id = 0;
		$schema = $data->getSchema();
		$src = $schema->get(SC_Meta_Source);
		$set = $this->setSQL($data, $schema);
		$sql = "INSERT `$src` SET $set";
		$result = mysqli_query($this->_handle, $sql);
		// TODO check affected rows
		if (!$result) {
			$why = mysqli_error($this->_handle);
			throw new Exception("No result from '$sql'\n" . $why);
		}
		// Check if primary is present in the data
		$primary = $schema->get(SC_Meta_Primary);
		if ($data->hasKey($primary)) {
			$id = $data->get($primary);
		} else {
			// Check for an auto incrementing key and set that.
			$id = mysqli_insert_id($this->_handle);
		}
		$data->setID($id);
		return $id;
	}

	/**
	 * Prepare the set statement
	 * @param boolean true if set for insert
	 * @return string
	 */
	private function setSQL($data, $schema, $withoutKey = NULL) {
		// TODO some checks on $data. e.g. is_a(), or perhaps $data->axis == 1
		$set = '';
		foreach($data as $key => $value) {
			if ($withoutKey && $withoutKey == $key) {
				continue;
			}
			if ($schema->hasSpace($key)) {
				if ($set) {
					$set .= ',';
				}
				$set .= "$key='$value'";
			}
		}
		return $set;
	}

}

?>

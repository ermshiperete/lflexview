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
require_once (SGF_CORE.'Data/Schema.php');

/**
 *
 */
class MySqlListMapper implements IDataStore {

	/**
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
		throw new Exception("Should not be called");
	}

	/**
	 * @see IDataStore::deleteAll()
	 */
	function deleteAll($schema) {
		$src = $schema->get(SC_Meta_Source);
		$sql = "TRUNCATE TABLE `$src`";
		$result = mysqli_query($this->_handle, $sql);
		if (!$result) {
			return FALSE;
		}
		// TODO more inspection of $result
		return TRUE;
	}

	private function querySql($data, $schema, $sql) {
		if (!$sql) {
			throw new Exception("Empty sql query");
		}
		$result = mysqli_query($this->_handle, $sql);
		if (!$result) {
			return FALSE;
		}
		$primary = $schema->get(SC_Meta_Primary);
		$i = 0;
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			if (array_key_exists($primary, $row)) {
				$key = $row[$primary];
			} else {
				$key = $i;
			}
			$data->setSpace($key, new SimpleValueSpace(NULL, $row));
			$i++;
		}
		mysqli_free_result($result);
		return TRUE;
	}

	public function execute($sql) {
		return mysqli_query($this->_handle, $sql);
	}

	/**
	 * @see IDataStore::update()
	 */
	function update($data) {
		throw new Exception("Should not be called");
	}

	/**
	 * @param $sql
	 * @return bool
	 */
	static function sqlHasSubstitute($sql) {
		return preg_match('/%.*%/', $sql) === 1;
	}
	
	/**
	 * @param string $sql
	 * @param IDataSpace $data
	 * @param Schema $schema
	 * @param string $id
	 * @return string
	 */
	static function sqlSubstitute($sql, $data, $schema, $id) {
		$result = $sql;
		$result = preg_replace('/%id%/', $id, $result);
		return $result;
	}
	
	/**
	 * @see IDataStore::read()
	 */
	function read($data, $schema, $id) {
		assert(is_a($data, 'ListSpace'));
		$sql = $data->getMeta(SC_Meta_Query);
		if ($this->sqlHasSubstitute($sql)) {
			$sql = $this->sqlSubstitute($sql, $data, $schema, $id);
		}
		
		return $this->querySql($data, $schema, $sql);
	}

	/**
	 * @see IDataStore::write()
	 */
	function write($data) {
		throw new Exception("Should not be called");
	}

}

?>

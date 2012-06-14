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

/**
 *
 */
class MySqlDataStoreBuilder implements IDataStore {

	/**
	 * @var mysqli
	 */
	var $_handle;

	/**
	 * @param MySqlDataStoreBase
	 */
	function __construct($handle) {
		$this->_handle = $handle;
	}

	/**
	 * @see IDataStore::delete()
	 */
	function delete($schema, $id) {
		$src = $schema->get(SC_Meta_Source);
		$sql = "DROP TABLE IF EXISTS `$src`";
		$result = mysqli_query($this->_handle, $sql);
		return $result != NULL;
	}

	/**
	 * Does nothing.
	 * No one would really want to drop the entire database.
	 * @see IDataStore::deleteAll()
	 */
	function deleteAll($schema) {
		// To scary to even think about doing.
	}

	/**
	 * Does nothing in this context
	 * @see IDataStore::query()
	 */
	function query($data, $schema) {
	}

	/**
	 * @see IDataStore::read()
	 */
	function read($data, $schema, $id) {
		//TODO Auto generated method stub
	}

	/**
	 * @see IDataStore::update()
	 */
	function update($data) {
		//TODO Auto generated method stub
	}

	/**
	 * Note: In this context $data is the Schema
	 * @see IDataStore::write()
	 */
	function write($data) {
		$retval = FALSE;
		$schema = $data;
		$sql = $this->createTableSQL($schema);
		$result = mysqli_query($this->_handle, $sql);
		$retval = ($result != NULL);
		return $retval;
	}

	/**
	 * Creates the SQL for the given Schema
	 * @param Schema
	 * @return string
	 */
	public function createTableSQL($schema) {
		// Create the related table
		$table = $schema->get(SC_Meta_Source);
		assert($table != null);
		$primary = $schema->get(SC_Meta_Primary);
		$sql = "CREATE TABLE `$table` (\n";
		$fields = '';
		foreach ($schema as $name => $f) {
			$size = '';
			$extra = '';
			// TODO refactor this switch to an array (const array?)
			switch ($f->get('type')) {
				case DT_PID:
					$type = 'int';
					$size = 11;
					$extra = 'auto_increment';
					break;
				case DT_ID:
					$type = 'int';
					$size = 11;
					break;
				case DT_Number:
					$type = 'double';
					$def = '0';
					break;
				case DT_Currency:
					$type = 'decimal';
					$def = '0.00';
					$size = '10,2';
					break;
				case DT_Date:
					$type = 'date';
					$def = '0000-00-00';
					break;
				case DT_Time:
					$type = 'time';
					$def = '00:00:00';
					break;
				case DT_DateTime:
					$type = 'datetime';
					$def = '0000-00-00 00:00:00';
					break;
				case DT_Memo:
					$type = 'text';
					break;
				case DT_Text:
					$type = 'varchar';
					$size = 128;
					break;
				case DT_Email:
					$type = 'varchar';
					$size = 128;
					break;
				case DT_URL:
					$type = 'varchar';
					$size = 128;
					break;
				case DT_Phone:
					$type = 'varchar';
					$size = 128;
					break;
				case DT_Action:
					$type = 'varchar';
					$size = 128;
					break;
			}
			if ($size) {
				$type .= "($size)";
			}
			if ($fields) {
				$fields .= ",\n";
			}
			$fields .= "`$name` $type NOT NULL $extra";
			if ($def) {
				$fields .= " default '$def'";
			}
		}
		$sql .= $fields;
		if ($primary) {
			$sql .= ",\n PRIMARY KEY (`$primary`)";
		}
		$sql .= "\n) TYPE=MyISAM;";
		//		debug('create sql', $sql);
		return $sql;
	}


}
?>

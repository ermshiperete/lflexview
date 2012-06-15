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
require_once (SGF_CORE . 'Data/IDataSpace.php');
require_once (SGF_CORE . 'Data/IDataStore.php');

/**
 *
 */
class PreConditionException extends Exception {
}

function precondition($condition) {
	$result = eval($condition);
	if (!$result) {
		throw new PreConditionException($condition);
	}
}

/**
 * DataSpaceBase is an abstract implementation for a multi axis container of key value pairs.
 * Data has a number of axis. e.g. values, meta data, schema, children etc.
 * These axes may be key value pairs, or key space pairs.
 *
 * @package		ARK
 * @subpackage	Data
 * @interface
 * @access public
 * @todo doco for this.
 */
abstract class DataSpaceBase implements IDataSpace {

	const Meta      = '_meta';
	const Schema    = '_schema';
	const Relations = '_relations';
	const Spaces    = '_spaces';
	const Values    = '_values';

	const RelationSpace = 'space';
	const RelationKey   = 'key';

	/**
	 * @var DataSpace[] $_metaSpaces
	 * @access private
	 */
	protected $_metaSpaces;

	/**
	 * @var IDataStore
	 */
	private $_dataMapper;

	/**
	 * @param IDataStore
	 */
	function __construct($dataMapper) {
		if ($dataMapper != NULL && !is_a($dataMapper, 'IDataStore')) {
			throw new Exception("dataMapper must be an IDataStore");
		}
		$this->_dataMapper = $dataMapper;
	}

	/**
	 * Initialises the $_metaSpaces array.
	 * Called by derived classes that wish to provide meta information.
	 */
	protected function constructMeta() {
		$this->_metaSpaces = array();
		$this->_metaSpaces[DataSpaceBase::Meta] = new SimpleKeyValueSpace();
	}

	/**
	 *
	 */
	function __destruct() {
	}

	/**
	 * Always returns zero.
	 * @see IDataSpace::count()
	 */
	function count() {
		return 0;
	}

	/**
	 * @see IDataSpace::delete()
	 */
	function delete() {
		if (!$this->_dataMapper) {
			return FALSE;
		}
		$schema = $this->getSchema();
		$id = $this->getID();
		return $this->_dataMapper->delete($schema, $id);
	}

	/**
	 * @see IDataSpace::enter()
	 */
	function enter($t) {
	}

	/**
	 * @see IDataSpace::erase()
	 */
	function erase($key) {
	}

	/**
	 * @see IDataSpace::eraseAll()
	 */
	function eraseAll() {
	}

	/**
	 * @see IDataSpace::get()
	 */
	function get($key) {
		return null;
	}

	/**
	 * Equivalent to getValueInSpace(Meta, 'id').
	 * @see IDataSpace::getID()
	 */
	function getID() {
		return $this->getValueInSpace(DataSpaceBase::Meta, 'id');
	}

	/**
	 * @see IDataSpace::getMeta()
	 */
	function getMeta($key) {
		return $this->getValueInSpace(DataSpaceBase::Meta, $key);
	}

	/**
	 * @see IDataSpace::getRelation()
	 */
	function getRelation($spaceKey) {
		return $this->getValueInSpace(DataSpaceBase::Relations, $spaceKey);
	}

	/**
	 * @see IDataSpace::getSchema()
	 */
	function getSchema() {
		return $this->getSpace(DataSpaceBase::Schema);
	}

	/**
	 * Returns the DataSpace $name
	 * @see IDataSpace::getSpace()
	 */
	function getSpace($name) {
		$retval = null;
		if (isset($this->_metaSpaces[$name])) {
			$retval = $this->_metaSpaces[$name];
		}
		return $retval;
	}

	/**
	 * @see IDataSpace::getSpaceInSpace()
	 */
	function getSpaceInSpace($name, $key) {
		$retval = null;
		$space = $this->getSpace($name);
		if ($space) {
			$retval = $space->getSpace($key);
		}
		return $retval;
	}

	/**
	 * @see IDataSpace::getValueInSpace()
	 */
	function getValueInSpace($name, $key) {
		$retval = null;
		$space = $this->getSpace($name);
		if ($space) {
			$retval = $space->get($key);
		}
		return $retval;
	}

	/**
	 * Always returns false.
	 * @see IDataSpace::hasKey()
	 */
	function hasKey($key) {
		return false;
	}

	/**
	 * Returns true if the DataSpace $name exists.
	 * @see IDataSpace::hasSpace()
	 */
	function hasSpace($name) {
		return isset($this->_metaSpaces) && self::isDataSpaceBaseName($name);
	}

	/**
	 * @see IDataSpace::leave()
	 */
	function leave($t) {
	}

	/**
	 * @see IDataSpace::reset()
	 */
	public function reset() {
	}

	/**
	 * @see IDataSpace::read()
	 */
	function read($pid = NULL) {
		if (!$this->_dataMapper) {
			return FALSE;
			//    		throw new Exception("No DataMapper set in '".get_class($this)."'");
		}
		$schema = $this->getSchema();
		$result = $this->_dataMapper->read($this, $schema, $pid);
		if ($result) {
			$relations = $this->getSpace(DataSpaceBase::Relations);
			if ($relations) {
				foreach ($relations as $spaceKey => $valueKey) {
					$value = $this->get($valueKey);
					$space = $this->getSpace($spaceKey);
					$space->read($value);
				}
			}
		}
		return $result;
	}

	/**
	 * @see IDataSpace::set()
	 */
	function set($key, $value) {
	}

	/**
	 * Sets the id of this DataSpace
	 * @see IDataSpace::setID()
	 */
	function setID($value) {
		$this->setValueInSpace(DataSpaceBase::Meta, 'id', $value);
	}

	/**
	 * @see IDataSpace::setMeta()
	 */
	function setMeta($key, $value) {
		$this->setValueInSpace(DataSpaceBase::Meta, $key, $value);
	}

	/**
	 * @see IDataSpace::setRelation()
	 */
	function setRelation($spaceKey, $valueKey) {
		$space = $this->getSpace(DataSpaceBase::Relations);
		// Check if relations are supported
		if (!$space) {
			if (!$this->_metaSpaces) {
				throw new Exception("Relations not supported in '" . get_class($this) . "'");
			}
			$space = new SimpleValueSpace();
			$this->setSpace(DataSpaceBase::Relations, $space);
		}
		// Check for relation already set
		if ($space->hasKey($spaceKey)) {
			throw new Exception("Relation already set for space '$spaceKey'");
		}
		// Check the schema for the prescense of $valueKey in the schema
		$schema = $this->getSchema();
		if ($schema) {
			if (!$schema->hasSpace($valueKey)) {
				$className = get_class($schema);
				throw new Exception("Relation not set. '$className' does not have '$valueKey'");
			}
		}
		$space->set($spaceKey, $valueKey); // Note: key on the space, as we can have many relations of our key to other spaces
	}

	/**
	 * @see IDataSpace::setSchema()
	 */
	function setSchema($schema) {
		$this->setSpace(DataSpaceBase::Schema, $schema);
	}

	protected static function isDataSpaceBaseName($name) {
		return $name == DataSpaceBase::Meta || $name == DataSpaceBase::Schema || $name == DataSpaceBase::Relations;
	}

	/**
	 * @see IDataSpace::setSpace()
	 */
	function setSpace($name, $space) {
		assert(is_a($space, 'IDataSpace'));
		assert(DataSpaceBase::isDataSpaceBaseName($name));
		if (isset($this->_metaSpaces)) {
			$this->_metaSpaces[$name] = $space;
		}
	}

	/**
	 * Sets DataSpace
	 * @see IDataSpace::setSpaceInSpace()
	 */
	function setSpaceInSpace($name, $key, $space) {
		$s = $this->getSpace($name);
		if ($s) {
			$s->setSpace($key, $space);
		}
	}

	/**
	 * Sets value $value for $key in DataSpace $name
	 * @see IDataSpace::setValueInSpace()
	 */
	function setValueInSpace($name, $key, $value) {
		$s = $this->getSpace($name);
		if ($s) {
			$s->set($key, $value);
		}
	}

	/**
	 * @see IDataSpace::write()
	 */
	function write($pid = NULL) {
		if (!$this->_dataMapper) {
			return 0;
		}
		$retval = 0;
		$id = $this->getID();
		if ($id || ($pid && $pid == $id)) {
			$retval = $this->_dataMapper->update($this);
		} else {
			if ($pid) {
				$schema = $this->getSchema();
				$primary = $schema->get(SC_Meta_Primary);
				$this->set($primary, $pid);
			}
			$retval = $this->_dataMapper->write($this);
		}
		return $retval;
	}
}
?>
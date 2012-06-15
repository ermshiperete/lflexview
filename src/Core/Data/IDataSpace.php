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
 * The IDataSpace is an interface for a multi axis container of key value pairs.
 * Data has a number of axis. e.g. values, meta data, schema, children etc.
 * These axes may be key value pairs, or key space pairs.
 *
 * @package		ARK
 * @subpackage	Data
 * @interface
 * @access public
 * @todo doco for this.
 */
interface IDataSpace extends IteratorAggregate {

	/**
	 * Resets the data space to it's default state
	 */
	public function reset();

	/**
	 * Reads values from the persistent store.
	 * @param integer $pid [optional] The primary key value to read
	 */
	public function read($pid = NULL);

	/**
	 * Writes values to the persistent store.
	 * Returns an integer representing the key of this object in the underlying store.
	 * @return integer
	 */
	public function write($pid = NULL);

	/**
	 * Deletes the DataSpace from the persistant store.
	 */
	public function delete();

	/**
	 * Returns true if this DataSpace has a DataSpace of the given name.
	 * @param string $name
	 * @return boolean
	 */
	public function hasSpace($name);

	/**
	 * Returns the DataSpace in space $name
	 * @param string $name
	 * @return DataSpace
	 */
	public function getSpace($name);

	/**
	 * Sets the space $name to the DataSpace $space
	 * @param string $name
	 * @param DataSpace& $space
	 */
	public function setSpace($name, $space);

	/**
	 * Calls get($key) on the space $name.
	 * This is the real getValue function that other convenience functions use,
	 * e.g. getMeta calls getValueInSpace('meta', $key).
	 * @param string $name
	 * @param string $key
	 * @return string
	 * @see get
	 */
	public function getValueInSpace($name, $key);

	/**
	 * Calls set($key, $value) on the space $name.
	 * This is the real setValue function that other convenience functions use,
	 * e.g. setMeta calls setValueInSpace('meta', $key, $value).
	 * @param string $name
	 * @param string $key
	 * @param string $value
	 * @see set
	 */
	public function setValueInSpace($name, $key, $value);

	/**
	 * Gets the DataSpace from space $key in space $name.
	 * @param string $name
	 * @param string $key
	 * @return DataSpace&
	 */
	public function getSpaceInSpace($name, $key);

	/**
	 * Sets the space $key in space $name to $space.
	 * @param string $name
	 * @param string $key
	 * @param DataSpace& $space
	 */
	public function setSpaceInSpace($name, $key, $space);

	/**
	 * Returns true if the given key exists in this DataSpace
	 * @param string $key
	 * @return boolean
	 */
	public function hasKey($key);

	/**
	 * Returns the value for the given key, or null if not present.
	 * @param string $key
	 * @return string
	 */
	public function get($key);

	/**
	 * Sets the value for the given key.
	 * @param string $key
	 * @param string $value
	 */
	public function set($key, $value);

	/**
	 * Returns the number of values in this DataSpace
	 * @return integer
	 */
	public function count();

	/**
	 * Erases (unset) the given key on the values axis.
	 * @param string $key
	 */
	public function erase($key);

	/**
	 * Erases all values from the DataSpace
	 */
	public function eraseAll();

	/**
	 * Returns the value of the key in the 'meta' axis
	 * @param string $key
	 * @return string
	 */
	public function getMeta($key);

	/**
	 * Sets the value of key in the 'meta' axis
	 * @param string $key
	 * @param string $value
	 * @return none
	 */
	public function setMeta($key, $value);

	/**
	 * Returns the space representing the given relation
	 * @param string $key
	 * @return string
	 */
	public function getRelation($spaceKey);

	/**
	 * Sets a relationship between our $valueKey and the space in $spaceKey.
	 * @param string $spaceKey
	 * @param string $valueKey
	 */
	public function setRelation($spaceKey, $valueKey);

	/**
	 * Returns the Schema that describes the keys in the DataSpace
	 * @return DataSpace
	 * @see Schema
	 */
	public function getSchema();

	/**
	 * Sets the Schema that describes the keys in the DataSpace
	 * @param Schema $value
	 */
	public function setSchema($value);

	/**
	 * Gets the unique id of this DataSpace
	 * @return string
	 */
	public function getID();

	/**
	 * Sets the unique id of this DataSpace.
	 * This should not normally be called, this is usually derived
	 * from say a primary key in a database table.
	 * @param string $value
	 * @todo review this may not be needed in the public interface
	 */
	public function setID($value);

	/**
	 * Called by a part when entering the traversal
	 * @param Traversal $t
	 * @return void
	 */
	public function enter($t);

	/**
	 * Called by a part when leaving the traversal
	 * @param Traversal $t
	 * @return void
	 */
	public function leave($t);

}
;

?>

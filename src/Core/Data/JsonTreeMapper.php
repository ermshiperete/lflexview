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
 *
 */
require_once (SGF_CORE.'Data/IDataStore.php');


class JsonTreeMapper implements IDataStore {

	private $_uri;

	/**
	 * @param string
	 */
	function __construct($uri) {
		$this->_uri = $uri;
	}

	/**
	 * @see IDataStore::delete()
	 */
	function delete($schema, $id) {
		//TODO JsonTreeMapper::delete NYI
		//		$data->import($array);
	}

	/**
	 * @see IDataStore::deleteAll()
	 */
	function deleteAll($schema) {
		//TODO JsonTreeMapper::deleteAll NYI
	}

	/**
	 * @see IDataStore::read()
	 */
	function read($data, $schema, $id) {
		// Check that $data is a TreeSpace

	}

	/**
	 * @see IDataStore::update()
	 */
	function update($data) {
		$this->write($data); // TODO review: what else could we do?
	}

	/**
	 * @see IDataStore::write()
	 */
	function write($data) {
		$s = self::encodeSpace($data);
		echo $s; // TODO output policy based on $uri in constructor
	}

	public static function encodeSpace($space) {
		$s = '{"v":';
		if ($space->hasSpace(DataSpaceBase::Values)) {
			$valueSpace = $space->getSpace(DataSpaceBase::Values);
			$s .= self::encodeValues($valueSpace);
		} else {
			$s .= 'null';
		}
		$s .= ',"s":';
		if ($space->hasSpace(DataSpaceBase::Spaces)) {
			$listSpace = $space->getSpace(DataSpaceBase::Spaces);
			$s .= '{';
			$i = 0;
			foreach ($listSpace as $key=>$childSpace) {
				if ($i > 0) {
					$s .= ',';
				}
				$s .= '"'.$key.'":'.self::encodeSpace($childSpace);
				$i++;
			}
			$s .= '}';
		} else {
			$s .= 'null';
		}
		$s .= '}';
		return $s;
	}

	public static function encodeValues($space) {
		$s = '{';
		$i = 0;
		foreach ($space as $key=>$value) {
			if ($i > 0) {
				$s .= ',';
			}
			$s .= '"'.$key.'":"'.$value.'"';
			$i++;
		}
		$s .= '}';
		return $s;
	}
}
?>

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
 * @package		ARK
 * @subpackage	Data
 * @interface
 * @access public
 * @todo doco for this.
 */
interface IDataStore {

	/**
	 * @param IDataSpace $data
	 * @param Schema $schema
	 * @param integer $id
	 * @return bool
	 */
	function read($data, $schema, $id);

	/**
	 * @param IDataSpace $data
	 * @return integer
	 */
	function write($data);

	/**
	 *
	 * @param IDataSpace $data
	 * @return integer
	 */
	function update($data);

	/**
	 *
	 * @param Schema $schema
	 * @param integer $id
	 * @return
	 */
	function delete($schema, $id);

	/**
	 *
	 * @param Schema $schema
	 */
	function deleteAll($schema);

}

?>
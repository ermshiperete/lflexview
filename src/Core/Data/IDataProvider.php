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
 * The IDataProvider is an interface for providing IDataSpaces.
 * It is a means of scheduling creation of a data space at a time convenient for
 * a part that is using it.  It also allows a variety of sources of data to be
 * used.
 * e.g. State data off the traversal, GET parameters in the traversal to determine
 * what data to load.
 *
 * @package		ARK
 * @subpackage	Data
 * @interface
 * @access public
 */
interface IDataProvider {

	/**
	 * Returns an IDataSpace that is not yet populated with data.
	 * @param Traversal $traversal
	 * @return IDataSpace
	 */
	function provideData($traversal);

	/**
	 * Returns an IDataSpace already populated and ready to use.
	 * @param Traversal $traversal
	 * @return IDataSpace
	 */
	function provideDataAndRead($traversal);
}

?>

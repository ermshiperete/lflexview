<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package    ARK
 * @subpackage View
 * @author     Cambell Prince <cambell@arketec.com>
 * @link       http://www.arketec.com
 */

/**
 * @package		ARK
 * @subpackage	View
 */
interface IView {

	/**
	 * Push the string value $text into the View with the key $key.
	 * @param string
	 * @param string
	 * @param string
	 * @todo deprecate scope
	 */
	function pushText($key, $text, $scope = null);

	/**
	 * Adds the string value $text into the View with the key $key.
	 * Appends to existing data if any is present.
	 * @param string $key
	 * @param string $text
	 */
	function addText($key, $text);

	/**
	 * Push the IDataSpace $data into the View with the key $key.
	 * @param string $name
	 * @param IDataSpace $data
	 */
	function pushData($key, $data);

	/**
	 * Push the IDataSpace $data in the same way as pushText.
	 * Each key is prefixed with the optional prefix.
	 * @param IDataSpace $data
	 * @param string $prefix [optional]
	 */
	function pushDataAsText($data, $prefix = '');

	/**
	 * Get the path to this Views resources.
	 * e.g. Template files.
	 * @return string
	 */
	function getViewFilePath();

	/**
	 * Render to the client (typically the browser)
	 */
	function renderToClient();

	/**
	 * Render to string
	 * Returns the View as a string. The optional $scope may be used to indicate which sub view
	 * to return if a View has more than one View. e.g. Email Views often have an alternate view.
	 * @param string
	 * @return string
	 * @todo deprecate scope
	 */
	function renderToString($scope = '');

	/**
	 * Called before the Part begins the render.
	 * This is a good opportunities for views (which may be shared) to reset their state.
	 */
	function onRenderEnter();

	/**
	 * Called after the Part finishes the render.
	 * @return
	 */
	function onRenderLeave();
}

?>

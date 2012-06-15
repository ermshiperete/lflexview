<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package    SayGoForms
 * @subpackage Parts
 * @author     Cambell Prince <cambell@arketec.com>
 * @link       http://www.arketec.com
 */

/**
 */
require_once (SGF_CORE.'Controller/Part.php');

/**
 * A DataPart establishes a new data context in the traversal.
 * This is used to preserve context for parts than could potentially be nested. e.g. Tables
 * @package		ARK
 * @subpackage	Parts
 */
class DataPart extends Part {

	/**
	 * @var IDataProvider
	 */
	protected $_dataProvider;

	/**
	 * Constructor
	 * @param string
	 * @param View
	 */
	function __construct($name, $viewProvider, $position, $dataProvider) {
		parent::__construct($name, $viewProvider, $position, DECK_AlwaysRender, '');
		$this->_dataProvider = $dataProvider;
	}

	/**
	 * handle
	 * Enters a new data scope in the Traversal, then calls Part::handle, then removes the data scope from
	 * the Traversal
	 * @param Event
	 * @param Traversal
	 * @see Traversal::dataEnter
	 * @see Traversal::dataLeave
	 */
	function handle($e, $t) {
		// todo: would this be better in the onRenderEnter / Leave ???
		// enter new data context
		$t->dataEnter();
		parent::handle($e, $t);
		$t->dataLeave();
	}

	/**
	 *
	 * @param Traversal $t
	 */
	function onRenderEnter($t) {
		parent::onRenderEnter($t);
		$data = $this->_dataProvider->provideData($t);
		$t->dataSet($data);
	}

}

?>

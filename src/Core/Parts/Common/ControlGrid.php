<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package    ARK
 * @subpackage Parts
 * @author     Cambell Prince <cambell@arketec.com>
 * @link       http://www.arketec.com
 */

/**
 */
require_once(SGF_CORE . 'Controller/Part.php');

/**
 * @package		ARK
 * @subpackage	Parts
 */
class ControlGrid extends Part {

	/**
	 * Constructor
	 * @param string
	 * @param IViewProvider
	 * @param string
	 */
	function __construct($name, $viewProvider, $position) {
		parent::__construct($name, $viewProvider, $position);
	}

	/**
	 * Handles the onRender event
	 * pushes the following variables into the view
	 *   - {Body} an array of the child renders
	 * @param Event
	 * @param Traversal
	 */
	function onRender($e, $t) {
		$this->renderProperties($t);
		$this->renderArguments($t);
		
		$data = $t->dataGet();
		
		$childRenders = array();
		foreach ($data as $item) {
			// We're only expecting one dispatchable, being the row
			foreach ($this->_dispatchables as $d) {
				if ($d->canHandle($e, $t)) {
					$t->enter($d);
					$t->dataEnter();
					$t->dataSet($item);
					$d->handle($e, $t);
					$view = $t->viewGet();
					assert($view != null);
					$childRenders[] = $view->renderToString();
					//				echo "--- ContainerPart --- Render " . $d->getName() . " from " . $view->_fileName . " into " . $this->getName() . " at BODY into view " . $this->_view->_fileName . "<br/>";
					$t->dataLeave();
					$t->leave($d);
				}
			}
		}
		
		$this->_view->pushData('Body', $childRenders);
	}
}

?>
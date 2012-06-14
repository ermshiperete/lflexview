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
require_once(SGF_CORE . 'Controller/part.php');

/**
 * @package		ARK
 * @subpackage	Parts
 */
class ContainerPart extends Part {

	/**
	 * Constructor
	 * @param string
	 * @param IViewProvider
	 * @param string
	 */
	function __construct($name, $view, $position) {
		parent::__construct($name, $view, $position);
	}

	/**
	 * Handles the onRender event
	 * pushes the following variables into the view
	 *   - {BODY} an array of the child renders
	 * @param Event
	 * @param Traversal
	 */
	function onRender($e, $t) {
		$this->renderProperties($t);
		$this->renderArguments($t);
		$childRenders = array();
		foreach ($this->_dispatchables as $d) {
			if ($d->canHandle($e, $t)) {
				$t->enter($d);
				$d->handle($e, $t);
				$view = $t->viewGet();
				assert($view != null);
				$childRenders[] = $view->renderToString();
				//				echo "--- ContainerPart --- Render " . $d->getName() . " from " . $view->_fileName . " into " . $this->getName() . " at BODY into view " . $this->_view->_fileName . "<br/>";
				$t->leave($d);
			}
		}
		$this->_view->pushData('Body', $childRenders);
	}

	//	function onRenderLeave(&$t) {
	//		 Do nothing to override the default behaviour of Part
	//	}

}

?>

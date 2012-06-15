<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package		ARK
 * @subpackage	Parts
 * @version    $Id: gridspace.php,v 1.1.1.1 2006/04/13 01:11:02 cambell Exp $
 * @author		  Cambell Prince <cambell@arketec.com>
 * @link				http://www.arketec.com
 */

/**
 */
require_once(SGF_CORE . 'Controller/Part.php');

/**
 * Renders DataSpace data based on type information in the Schema
 * @package		ARK
 * @subpackage	Parts
 * @todo Could refactor, have a TypedPart, implement this one in table.php as it is mainly
 * related to implementing a Table Row.  Also TableRow appears unused / redundant.
 * @todo extends TypedPart
 */
class TypedGridSpace extends Part {
	/**
	 * @var Schema
	 */
	var $schema_;

	/**
	 * @var array
	 */
	var $keys;

	/**
	 * @var Renderer
	 */
	var $renderer_;

	/**
	 * @var DataSpace
	 */
	var $space_;

	/**
	 * Constructor
	 * @param string
	 * @param string
	 * @param string
	 * @param Schema
	 * @param Renderer
	 * @param DataSpace May be null
	 */
	function TypedGridSpace($name, $view, $position, $schema, $renderer, $space) {
		$this->Part($name, $view, $position);
		$this->renderer_ = $renderer;
		$this->schema_ = $schema;
		$this->space_ = $space;
		$this->keys_ = $this->schema_->getKeys();
	}

	/**
	 * Sets the renderer
	 * @param Renderer
	 */
	function setRenderer($renderer) {
		$this->renderer_ = $renderer;
	}

	/**
	 * Sets the keys
	 * @param array
	 */
	function setKeys($keys) {
		$this->keys_ = $keys;
	}

	/**
	 * Set the DataSpace
	 * @param DataSpace
	 */
	function setSpace($space) {
		$this->space_ = $space;
	}

	/**
	 * Get the DataSpace
	 * @return DataSpace
	 */
	function getSpace() {
		return $this->space_;
	}

	/**
	 * Handles the onRender event
	 * @param Event
	 * @param Traversal
	 */
	function onRender($e, $t) {
		$v = $t->viewGet();
		$a0 = $t->getCommand();
		if ($a0) {
			$id = $a0->get(0);
		}
		$this->space_->read($id);
		$it = new ArrayIterator($this->keys_);
		for ($it->rewind(); $it->isValid(); $it->next()) {
			$key = $it->current();
			$value = $space->get($key);
			$label = $this->schema_->getAttribute($key, 'label');
			$type = $this->schema_->getAttribute($key, 'type');
			$fmt = $this->schema_->getAttribute($key, 'format');
			if ($type != DT_Hidden) {
				$this->renderer_->render($v, $t, $type, $key, $value, $fmt);
				$v->renderToParent($t, $this->_position); // todo fix me renderToParent is deprecated.
			}
		}
		return true;
	}

	/**
	 * Override the default behaviour to do nothing.
	 * The View has already been rendered in our onRender method.
	 * @see onRender
	 * @param Traversal
	 */
	function onRenderLeave($t) {
	}

}

?>

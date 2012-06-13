<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * Schema parts draw based on type information.
 * Schema parts can be used to define site wide policies for parts. The rendering is
 * parameterised on both data and type
 * @package		ARK
 * @subpackage	Parts
 * @version    $Id: typedpart.php,v 1.1.1.1 2006/04/13 01:11:02 cambell Exp $
 * @author		  Cambell Prince <cambell@arketec.com>
 * @link				http://www.arketec.com
 */

/**
 */
require_once(SGF_CORE . 'data/keyspaceiterator.php');
require_once(SGF_CORE . 'Controller/part.php');

/**
 * Renders labels to the view from the schema
 * @package		ARK
 * @subpackage	Parts
 */
class TypedLabels extends Part {
	var $schema_;
	var $keys_;

	/**
	 * Constructor
	 * @param string
	 * @param View
	 * @param Schema
	 */
	function TypedLabels($name, $view, $position, $schema, $keys = null) {
		$this->Part($name, $view, $position);
		$this->schema_ = $schema;
		if ($keys == null) {
			$this->keys_ = $this->schema_->getKeys();
		} else {
			$this->keys_ = $keys;
		}
	}

	/**
	 * Handles the onRender event
	 * @param Event
	 * @param Traversal
	 */
	function onRender(&$e, &$t) {
		$v = $t->viewGet();
		$it = new ArrayIterator($this->keys_);
		for ($it->rewind(); $it->isValid(); $it->next()) {
			$key = $it->current();
			$f = $this->schema_->get($key);
			$type = $f->get('type');
			if ($type != DT_Hidden) {
				$label = $f->get('label');
				$v->pushText('BODY', $label);
				$v->renderToParent($t, $this->_position);
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
	function onRenderLeave(&$t) {
	}

}

/**
 * Renders DataSpace data based on type information in the Schema
 * @package		ARK
 * @subpackage	Parts
 * @todo Could refactor, have a TypedPart, implement this one in table.php as it is mainly
 * related to implementing a Table Row.  Also TableRow appears unused / redundant.
 */
class TypedKeySpacePart extends Part {
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
	 * Constructor
	 * @param string
	 * @param View
	 * @param Renderer
	 * @param Schema
	 */
	function TypedKeySpacePart($name, $view, $position, $renderer, $schema, $keys = null) {
		$this->Part($name, $view, $position);
		$this->renderer_ = $renderer;
		$this->schema_ = $schema;
		if ($keys == null) {
			$this->keys_ = $this->schema_->getKeys();
		} else {
			$this->keys_ = $keys;
		}
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
	 * Handles the onRender event
	 * @todo finish of the support for keys
	 * @param Event
	 * @param Traversal
	 */
	function onRender(&$e, &$t) {
		$v = $t->viewGet();
		$space = $t->dataGet('keyspace');
		if ($space) {
			$it = new ArrayIterator($this->keys_);
			for ($it->rewind(); $it->isValid(); $it->next()) {
				$key = $it->current();
				$value = $space->get($key);
				$type = $this->schema_->getAttribute($key, 'type');
				$fmt = $this->schema_->getAttribute($key, 'format');
				if ($type != DT_Hidden) {
					$this->renderer_->render($v, $t, $type, $key, $value, $fmt);
					$v->renderToParent($t, $this->_position);
				}
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
	function onRenderLeave(&$t) {
	}

}

/**
 * Renders a single data element based on the given information
 * @package		ARK
 * @subpackage	Parts
 */
class TypedDataPart extends Part {
	var $key_;
	var $type_;
	var $renderer_;

	/**
	 * Constructor
	 * @param string
	 * @param View
	 * @param Renderer
	 * @param Schema
	 */
	function TypedDataPart($name, $view, $position, $renderer, $key, $type) {
		$this->Part($name, $view, $position);
		$this->key_ = $key;
		$this->type_ = $type;
		$this->renderer_ = $renderer;
	}

	/**
	 * Sets the renderer
	 * @param Renderer
	 */
	function setRenderer($renderer) {
		$this->renderer_ = $renderer;
	}

	/**
	 * Handles the onRender event
	 * @param Event
	 * @param Traversal
	 */
	function onRender(&$e, &$t) {
		$space = $t->dataGet('keyspace');
		if ($space) {
			$v = $t->viewGet();
			$value = $space->get($this->key_);
			$this->renderer_->render($v, $t, $this->type_, $this->key_, $value);
		}
		return true;
	}

}

?>

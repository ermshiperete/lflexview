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
require_once (SGF_CORE.'data/IDataSpace.php');
require_once (SGF_CORE.'Controller/part.php');

/**
 */
define('TA_Paging', 1);
define('TA_Filter', 2);
define('TA_Sort', 4);
define('TA_All', 7);
define('TA_NoPaging', 6);

/**
 * A Table
 * This is a useful default implementation of a Table. A Table is a Part that has a filter,
 * a pager, a header, and a body.
 * Tables have the following structure:
 *  table.filter.pager
 *              .header
 *              .body
 * @package		ARK
 * @subpackage	Parts
 * @see Filter
 * @see Pager
 * @see TableBody
 */
class Table extends DataPart {

	/**
	 * Reference to the filter
	 * @var Filter
	 */
	private $_filter;

	/**
	 * Reference to the pager
	 * @var Pager
	 */
	private $_pager;

	/**
	 * Reference to the header
	 * @var Dispatchable
	 */
	private $_header;

	/**
	 * Reference to the body
	 * @var Dispatchable
	 */
	private $_body;

	function __construct($name, $viewProvider, $position, $dataProvider) {
		parent::__construct($name, $viewProvider, $position, $dataProvider);

		//        $this->_filter = new Filter('filter');
		//        $this->_pager = new Pager('pager', 'pager', 'PAGER', new TableAccessor($it, TA_NoPaging));
		//        $this->_header = new Part('tablehdr', 'tablehdr', 'HEADER');
		$this->_body = new TableBody('tablebody', 'tablebody', 'BODY', new TableAccessor($it));

		//        $this->addChild($this->_filter);
		//        $this->_filter->_dispatchables[0] = &$this->_pager;
		//        $this->_filter->_dispatchables[1] = &$this->_header;
		//        $this->_filter->_dispatchables[2] = &$this->_body;

	}

	/**
	 * Adds a cell to the header row
	 * @param Dispatchable
	 */
	function addHeaderCell(&$d) {
		$this->_header->addChild($d);
	}

	/**
	 * Adds a cell to the body row
	 * @param Dispatchable
	 */
	function addBodyCell(&$d) {
		$this->_body->addChild($d);
	}

	/**
	 * Returns a reference to the filter
	 * @return Filter Reference
	 */
	function & getFilter() {
		return $this->_filter;
	}

	/**
	 * Sets the pager
	 * This will remove the previous pager and all it's children.
	 * @param Dispatchable
	 */
	function setPager(&$pager) {
		$this->_pager = &$pager;
		$this->_filter->_dispatchables[0] = &$this->_pager;
	}

	/**
	 * Sets the header
	 * This will remove the previous header and all it's children.
	 * @param Dispatchable
	 */
	function setHeader(&$header) {
		$this->_header = &$header;
		$this->_filter->_dispatchables[1] = &$this->_header;
	}

	/**
	 * Sets the body
	 * This will remove the previous body and all it's children.
	 * @param Dispatchable
	 */
	function setBody(&$body) {
		$this->_body = &$body;
		$this->_filter->_dispatchables[2] = &$this->_body;
	}

	/**
	 * Sets the where condition in the filter
	 * @param strng
	 * @see Filter::setWhere
	 */
	function setWhere($where) {
		$this->_filter->setWhere($where);
	}

}


/**
 * A TableBody
 * The TableBody defines a policy for rendering the data in the table.
 * This implementation uses an iterator to iterate over a set of keyspaces drawing a number of rows.
 * @package		ARK
 * @subpackage	Parts
 */
class TableBody extends Part {

	/**
	 * @var IteratorAccessor
	 */
	var $accessor_;

	/**
	 * @var integer
	 */
	var $mod_;

	/**
	 * Constructor
	 */
	function TableBody($name, $view, $position, $accessor, $mod = 2) {
		$this->Part($name, $view, $position);
		$this->accessor_ = $accessor;
		$this->mod_ = $mod;
	}

	/**
	 * Sets the current DataSpace from the current Iterator
	 * A Table expects the Iterator to exist in the Traversal, it also expects the iterator to return
	 * a DataSpace. Table renders by setting the current DataSpace in the Traversal and allowing it's
	 * children to render the row.
	 * @param Event
	 * @param Traversal
	 */
	function onRender(&$e, &$t) {
		$v = &$t->viewGet();
		$it = &$this->accessor_->getIterator($t);
		if ($it != null) {
			$count = 0;
			$mod = 0;
			for ($it->rewind(); $it->isValid(); $it->next()) {
				// The table expects
				$keyspace = &$it->current();
				$t->dataSet('keyspace', $keyspace);
				$primary = $keyspace->getID();
				$t->dataSet('primary', $primary);
				parent::onRender($e, $t); // render each cell
				$t->dataErase('keyspace');
				$mod = $count % $this->mod_;
				$v->pushText('MOD', $mod);
				$v->pushText('COUNT', $count);
				$v->pushText('PRIMARY', $primary);
				$v->renderToParent($t, $this->_position);
				$count++;
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
 * A TableRow
 * The TableRow defines a policy for rendering the data in each row of the table.
 * @package		ARK
 * @subpackage	Parts
 * @todo Is this every used? It might be redundant
 */
class TableRow extends Part {
	function TableRow($name, $view, $position) {
		$this->Part($name, $view, $position);
	}

	/**
	 * Handles the onRender event
	 * @param Event
	 * @param Traversal
	 */
	function onRender(&$e, &$t) {
		$v = &$t->viewGet();
		$space = &$t->dataGet('keyspace');
		if ($space) {
			$it = new KeySpaceIterator($space);
			for ($it->rewind(); $it->isValid(); $it->next()) {
				$v->pushText('BODY', $it->current());
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
 * TableKeyAction
 *
 * @package		ARK
 * @subpackage	Parts
 */
class TableKeyAction extends Part {

	/**
	 * @var string
	 */
	var $valueKey_;

	/**
	 * @var string
	 */
	var $actionKey_;

	/**
	 * @var integer
	 */
	var $keyPos_;

	/**
	 * @var ActionPath
	 */
	var $actionpath_;

	/**
	 * Constructor
	 * @param string
	 * @param View
	 * @param string
	 * @param string 'primary' will use the primary key from the iterator
	 * @param ActionPath
	 */
	function TableKeyAction($name, $view, $position, $valueKey, $actionKey, $actionpath) {
		$this->Part($name, $view, $position);
		$this->valueKey_ = $valueKey;
		$this->actionKey_ = $actionKey;
		$this->actionpath_ = $actionpath;
		$action = $actionpath->last();
		$this->keyPos_ = $action->count();
	}

	/**
	 * Handles the onRender event
	 * @param Event
	 * @param Traversal
	 */
	function onRender(&$e, &$t) {
		$v = &$t->viewGet();
		$space = &$t->dataGet('keyspace');
		if ($space) {
			if ($this->actionKey_ == 'primary') {
				$actionValue = $t->dataGet('primary');
			} else {
				$actionValue = $space->get($this->actionKey_);
			}
			$action = &$this->actionpath_->last();
			$action->set($this->keyPos_, $actionValue);
			$url = $t->buildURLByPath($this->actionpath_);
			$v->pushText('ACTION', $url);
			$value = $space->get($this->valueKey_);
			$v->pushText('BODY', $value);
		}
		return true;
	}

}

/**
 * TableAction
 * @package		ARK
 * @subpackage	Parts
 */
class TableAction extends Part {

	/**
	 * @var string
	 */
	var $label_;

	/**
	 * @var string
	 */
	var $image_;

	/**
	 * @var string
	 */
	var $key_;

	/**
	 * @var integer
	 */
	var $keyPos_;

	/**
	 * @var ActionPath
	 */
	var $actionpath_;

	/**
	 * Constructor
	 * @param string
	 * @param string
	 * @param string
	 * @param ActionPath
	 * @param string
	 * @param string
	 * @param string
	 */
	function TableAction($name, $view, $position, $actionpath, $label, $key = 'primary', $image = null) {
		$this->Part($name, $view, $position);
		$this->actionpath_ = $actionpath;
		$this->label_ = $label;
		$this->key_ = $key;
		$this->image_ = $image;
		$action = $actionpath->last();
		$this->keyPos_ = $action->count();
	}

	/**
	 * Handles the onRender event
	 * @param Event
	 * @param Traversal
	 */
	function onRender(&$e, &$t) {
		$v = &$t->viewGet();
		$space = &$t->dataGet('keyspace');
		if ($space) {
			if ($this->key_ == 'primary') {
				$value = $t->dataGet('primary');
			} else {
				$value = $space->get($this->key_);
			}
			$action = &$this->actionpath_->last();
			$action->set($this->keyPos_, $value);
			$url = $t->buildURLByPath($this->actionpath_);
			$v->pushText('ACTION', $url);
			$v->pushText('BODY', $this->label_);
			$v->pushText('IMAGE', $this->image_);
		}
		return true;
	}

}

/**
 * TableDeleteAction
 * @package		ARK
 * @subpackage	Parts
 */
class TableDeleteAction extends TableAction {
	/**
	 * @var string
	 */
	var $table_;

	/**
	 * @var string
	 */
	var $primaryKey_;

	/**
	 * Constructor
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 */
	function TableDeleteAction($name, $view, $position, $table, $primaryKey, $label, $image = null) {
		$this->TableAction($name, $view, $position, new ActionPath("$name:del", 'current'), $label, 'primary', $image);
		$this->table_ = $table;
		$this->primaryKey_ = $primaryKey;
	}

	/**
	 * Handles the onAction event.
	 * Deletes 1 record from the table with the primary ID from the Traversal.
	 * @param Event
	 * @param Traversal
	 * @return boolean Returns true if the event is handled.
	 */
	function onAction(&$e, &$t) {
		$ret = false;
		$a = $t->getAction();
		if ($a) {
			$op = $a->get(0);
			$primary = $a->get(1);
			// get args
			switch ($op) {
				case 'del':
					DBKit::delete($this->table_, $this->primaryKey_, $primary);
					$ret = true;
					break;
			}
		}
		return $ret;
	}

}

?>

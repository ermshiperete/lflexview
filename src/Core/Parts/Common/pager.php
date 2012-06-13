<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package		ARK
 * @subpackage	Parts
 * @version    $Id: pager.php,v 1.1.1.1 2006/04/13 01:11:02 cambell Exp $
 * @author		  Cambell Prince <cambell@arketec.com>
 * @link				http://www.arketec.com
 */

/**
 */
require_once(SGF_CORE . 'Controller/part.php');
require_once(SGF_CORE . 'data/accessor.php');

/**
 * @package		ARK
 * @subpackage	Parts
 */
class Pager extends Part {

	/**
	 * @var IteratorAccessor
	 */
	var $accessor_;

	/**
	 * @var integer
	 */
	var $count_;

	/**
	 * @var integer
	 */
	var $start_;

	/**
	 * @var integer
	 */
	var $size_;

	/**
	 * Constructor
	 * @param string
	 * @param View
	 * @param DataBinding
	 */
	function Pager($name, $view, $position, $accessor, $start = 0, $size = 10) {
		$this->Part($name, $view, $position);
		$this->accessor_ = $accessor;
		$this->count_ = -1;
		$this->start_ = $start;
		$this->size_ = $size;

	}

	/**
	 * Handles the onAction event
	 * @param Event
	 * @param Traversal
	 * @todo Fix the pager for refresh. ie. include the start in the url
	 */
	function onAction(&$e, &$t) {
		$start = $t->stateGet('start');
		$size = $t->stateGet('size');
		$it = $this->accessor_->getIterator($t);
		$this->count_ = $it->count();
		if (!$start) {
			$start = $this->start_;
			$size = $this->size_;
		}
		$action = $t->getAction();
		// calculate
		switch ($action->get(0)) {
			case 'prev':
				$start = $start - $size;
				if ($start < 0) {
					$start = 0;
				}
				break;
			case 'next':
				if ($start + $size < $this->count_) {
					$start = $start + $size;
				}
				break;
			case 'page':
				$page = $action->get(1);
				break;
		}
		$t->stateSet('start', $start);
		$t->stateSet('size', $size);
	}

	/**
	 * Handles the onRender event
	 * Pushes the following tags into the View:
	 * - {COUNT}	The total number of records
	 * - {START}	The starting record number
	 * - {SIZE}	The number of records in this page
	 * - {END}		The end record number
	 * - {PREV_ACTION} The URL for the 'previous' action
	 * - {NEXT_ACTION} The URL for the 'next' action
	 * Sets a node 'pager' in the traversal with start and size i.e. $t->dataSet('pager', $pager);
	 * @param Event
	 * @param Traversal
	 */
	function onRender(&$e, &$t) {
		$v = $t->viewGet();
		if ($this->count_ < 0) {
			$it = $this->accessor_->getIterator($t);
			$this->count_ = $it->count();
		}
		// render pager
		$v->pushText('COUNT', $this->count_);
		$prev = $t->buildURLByAction(new Action($this->_name . ':prev'));
		$v->pushText('PREV_ACTION', $prev);
		$next = $t->buildURLByAction(new Action($this->_name . ':next'));
		$v->pushText('NEXT_ACTION', $next);

		// set pager in the traversal data
		$pager = $t->stateGetNode();
		if ($pager == null) {
			$t->stateSet('start', $this->start_);
			$t->stateSet('size', $this->size_);
			$pager = $t->stateGetNode();
		}
		if ($pager != null) {
			$start = $pager->get('start');
			$v->pushText('START', $start + 1);
			$size = $pager->get('size');
			$v->pushText('SIZE', $size);
			$v->pushText('END', $start + $size);
			$t->dataSet('pager', $pager);

		} else {
			Debug::log('pager is null');
		}

		// render children (we shouldn't have any)
		parent::onRender($e, $t);
	}

}

?>

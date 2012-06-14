<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package		ARK
 * @subpackage	Parts
 * @version    $Id: datecontrol.php,v 1.1.1.1 2006/04/13 01:11:01 cambell Exp $
 * @author		  Cambell Prince <cambell@arketec.com>
 * @link				http://www.arketec.com
 */

/**
 */
require_once(SGF_CORE . 'Controller/control.php');

/**
 * A TextControl
 * @package		ARK
 * @subpackage	Parts
 */
class DateComboControl extends Control {

	var $_size;
	var $_months;
	var $_days;

	/**
	 * Constructor
	 * @param string
	 * @param View
	 * @param Form
	 * @param integer
	 * @param string
	 * @param integer
	 */
	function __construct($name, $viewProvider, $position, &$form, $id, $label, $size) {
		parent::__construct($name, $viewProvider, $position, $form, $id, $label);
		$this->_size = $size;
		$this->_months = array();
		for ($i = 1; $i <= 12; $i++) {
			$this->_months[] = strftime('%b', mktime(0, 0, 0, $i, 1, 2000));
		}
		$this->_days = array();
		for ($i = 1; $i <= 31; $i++) {
			$this->_days[] = sprintf('%02d', $i);
		}
	}

	/**
	 * Handles the onData event
	 * @param Event
	 * @param Traversal
	 */
	function onData($e, $t) {
		$d = $_POST[$this->_name . '_d'] . ' ' . $_POST[$this->_name . '_m'];
		$tt = strtotime($d);
		$value = strftime('%Y-%m-%d', $tt);
		$this->form_->set($this->_name, $value);
	}

	/**
	 * Handles the onRender event
	 * Pushes TITLE REQD into the view. Also pushes the HTML input tag as BODY into the view.
	 * @param Event
	 * @param Traversal
	 */
	function onRender($e, $t) {
		$v = $t->viewGet();
		$v->pushText('TITLE', $this->label_);
		$v->pushText('REQD', $this->isRequired());

		// Build the month year array
		$dtinfo = getdate();
		$month = $dtinfo['mon'] - 1;
		$year = $dtinfo['year'];
		$monthyear = array();
		for ($i = 0; $i < 12; $i++) {
			$monthyear[] = $this->_months[$month] . ' ' . $year;
			$month++;
			if ($month == 12) {
				$month = 0;
				$year++;
			}
		}

		// Figure out the defaults
		$value = $this->get();
		if ($value) {
			list($year, $month, $day) = explode('-', $value);
			$value_d = $day;
			$value_m = $this->_months[$month - 1] . ' ' . $year;
		} else {
			$value_d = $dtinfo['mday'];
			$value_m = $monthyear[0];
		}

		$body = '';
		$body .= $this->combo($this->_name . '_d', $this->_days, $value_d);
		$body .= '&nbsp;';
		$body .= $this->combo($this->_name . '_m', $monthyear, $value_m);
		$v->pushText('BODY', $body);
		return true;
	}

	function combo($name, $items, $selected) {
		$s = '<select name="' . $name . '" size="1">';
		for ($i = 0; $i < count($items); $i++) {
			if ($items[$i] == $selected) {
				$s .= '<option selected>' . $items[$i] . '</option>';
			} else {
				$s .= '<option>' . $items[$i] . '</option>';
			}
		}
		$s .= '</select>';
		return $s;
	}

}

?>

<?php
//--------------------------------------------------------------------------------
// Copyright 2008 SayGoWeb.
// Released under the GPL license (http://www.gnu.org/copyleft/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package      SAYGOWEB_Website
 * @subpackage
 * @author       Cambell Prince <cambell@saygoweb.com>
 * @link         http://www.saygoweb.com
 */

/**
 */
require_once(SGF_CORE.'Error.php');
require_once(SGF_CORE.'Util/SqlFormat.php');

class ApplicationError extends Error {

	/**
	 * Constructor
	 */
	function __construct() {
		set_error_handler(array($this, 'handleError'));
		set_exception_handler(array($this, 'handleException'));
	}

	function __destruct() {
		restore_exception_handler();
		restore_error_handler();
	}


	/**
	 * Implementation of log
	 * @param string
	 * @access protected
	 * @see log
	 */
	function _log($f, $l, $msg, $v) {
		//		$this->_db($f, $l, 'log', $msg);
		$this->_display($f, $l, $msg, $v);
		$this->_file($f, $l, $msg);
	}

	/**
	 * Implementation of error
	 * @param string File
	 * @param string Line
	 * @param string
	 * @param mixed
	 * @see error
	 */
	function _err($f, $l, $msg, $v) {
		//		$this->_db($f, $l, 'err', $msg);
		$this->_display($f, $l, $msg, $v);
		$this->_file($f, $l, $msg);
	}

	function _io($f, $l, $msg, $v) {
		$this->_display($f, $l, $msg, $v);
		$this->_file($f, $l, $msg);
	}

	/*
	 function _db($f, $l, $svc, $msg) {
		$db = DBKit::singleton();
		$s = Session::singleton();
		if ($db) {
		$uid = $s->getUID();
		$cid = $s->get('cid');
		$sid = $s->getSKID();
		$qs = $db->qstr($msg);
		$sql = "INSERT INTO arks_log (uid,cid,sid,dtc,svc,err) VALUES ('$uid','$cid','$sid',NOW(),'$svc',$qs)";
		//debug('sq', $sql);
		$db->Execute($sql);
		}
		}
		*/
	function _file($f, $l, $msg) {
		if (strlen($msg) > 0) {
			$f = $this->strip($f);
			//$s = Session::singleton();
			//$sid = $s->getSKID();
			$now = sqlfmt_toISOTimeUTC();
			$str = "$now $f $l $msg\n";
			error_log($str);
		}
	}

	function _display($f, $l, $msg) {
		$bt = debug_backtrace();
		//		var_dump($bt);
		echo '<table style="font-size: 8pt; border: 1px solid red; width: 66%;">';
		echo "<tr><td><b>Error:\n" . $this->strip($f) . "\t($l)</b></td><td>$msg</td></tr>";
		foreach($bt as $t) {
			if ($t['class'] != 'ApplicationError' && $t['class'] != 'Error') {
				$function = $t['class'] . $t['type'] . $t['function'];
				//				$args = var_dump($t['args']);
				echo "<tr><td style=\"border-bottom: 1px solid grey; width: 50%;\">" . $this->strip($t['file']) . "($t[line])</td><td style=\"border-bottom: 1px solid grey;\">$function</td><td></td></tr>";
			}
		}
		echo '</table>';
		echo "<br/>\n";
	}

	private function strip($filePath) {
		return str_replace(ERROR_PathFilter, '', $filePath);
	}
	
	public function handleError($error_level,$error_message,$error_file,$error_line,$error_context) {
		$refId = \strtoupper(uniqid("ER"));
		$msg = "";
		switch ($error_level)
		{
			case E_WARNING:
				$msg = "[E_WARNING]";
				break;
			case E_NOTICE:
				$msg = "[E_NOTICE]";
				break;
			case E_USER_ERROR:
				$msg = "[E_USER_ERROR]";
				break;
			case E_USER_WARNING:
				$msg = "[E_USER_WARNING]";
				break;
			case  E_USER_NOTICE:
				$msg = "[E_USER_NOTICE]";
				break;
			case E_RECOVERABLE_ERROR:
				$msg = "[E_RECOVERABLE_ERROR]";
				break;
			case  E_ALL:
				$msg = "[E_ALL]";
				break;
		}
		//$msg=$msg . "[REF: $refId] : [T: $errorType] [L: $errorLine] [F: $errorFile] \n $errorString \n";
		$fileName = $this->strip($error_file);
		$msg=$msg . "[REF: $refId] : $error_message in $fileName line $error_line\n";

		error_log($msg);

		switch ($error_level)
		{
			case E_USER_ERROR:
				$this->_display($error_file, $error_line, $msg);
				exit;
				// finish
				return true;
			case E_USER_WARNING:
			case  E_USER_NOTICE:
				break;
		}
	}

	function handleException($exception) {

		// these are our templates
		$refId = \strtoupper(uniqid("EX"));
		$traceline = "#%s %s(%s): %s(%s)";

		$msg = "PHP Fatal error [REF: $refId] :  Uncaught exception '%s' with message '%s' in %s:%s\nStack trace:\n%s\n  thrown in %s on line %s";

		// alter your trace as you please, here
		$trace = $exception->getTrace();
		foreach ($trace as $key => $stackPoint) {
			// I'm converting arguments to their type
			// (prevents passwords from ever getting logged as anything other than 'string')
			$trace[$key]['args'] = \array_map('gettype', $trace[$key]['args']);
		}
		// build your tracelines
		$result = array();
		foreach ($trace as $key => $stackPoint) {
			$result[] = sprintf(
				$traceline,
				$key,
				\array_key_exists('file', $stackPoint)? $this->strip($stackPoint['file']) : "",
				\array_key_exists('line', $stackPoint)? $stackPoint['line'] : "",
				\array_key_exists('function', $stackPoint)? $stackPoint['function'] : "",
				\implode(', ', $stackPoint['args'])
			);
		}
		// trace always ends with {main}
		$result[] = '#' . ++$key . ' {main}';

		// write tracelines into main template
		$msg = sprintf(
			$msg,
			get_class($exception),
			$exception->getMessage(),
			$exception->getFile(),
			$exception->getLine(),
			\implode("\n", $result),
			$exception->getFile(),
			$exception->getLine()
		);
		error_log($msg);
		$this->_display($exception->getFile(), $exception->getLine(), $msg);
		return true;
	}

}

?>

<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package    SayGoForms
 * @subpackage Util
 * @author     Cambell Prince <cambell@arketec.com>
 * @link       http://www.arketec.com
 */

/**
 * @param time UNIX date/time stamp. Default is now.
 * @return string ISO date/time string as YYYY-MM-DD HH:MM:SS
 * @see sqlfmt_fromISOTimeUTC
 */
function sqlfmt_toISOTimeUTC($time = null) {
	if ($time == null) {
		$time = time();
	}
	return gmdate("Y-m-d H:i:s", $time); // NOTE: gmdate() is independent of locale settings
}

/**
 * @see sqlfmt_toISOTimeUTC
 * @param string as ISO date/time string
 * @return time UNIX date/time stamp
 */
function sqlfmt_fromISOTimeUTC($s) {
	$a = split('[T :-]', $s);
	return gmmktime($a[3], $a[4], $a[5], $a[1], $a[2], $a[0]);
}

/**
 * Converts 0000-00-00 00:00:00 to 0000-00-00T00:00:00
 * @param string
 * @return string
 */
function sqlfmt_toTTime($s) {
	return strtoupper(str_replace(' ', 'T', $s));
}

/**
 * Converts 0000-00-00T00:00:00 to 0000-00-00 00:00:00
 * @param string
 * @return string
 */
function sqlfmt_fromTTime($s) {
	return str_replace('T', ' ', strtoupper($s));
}

function sqlfmt_nullTTime() {
	return '0000-00-00T00:00:00';
}

function sqlfmt_nullTime() {
	return '0000-00-00 00:00:00';
}

?>

<?php

//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package		ARK
 * @subpackage	Data
 * @version		$Id: user.php,v 1.1.1.1 2006/04/13 01:11:01 cambell Exp $
 * @author		Cambell Prince <cambell@arketec.com>
 * @link			http://www.arketec.com
 */

/**
 */
require_once(SGF_CORE . 'Data/dataspace.php');
require_once(SGF_CORE . 'Data/session.php');
require_once(SGF_CORE . 'Util/utilpassword.php');

/**
 * @package		ARK
 * @subpackage	Data
 * @access public
 */
class User extends DataSpaceDecorator {
	/**
	 * @param DataSpace
	 * @return User singleton
	 * @access public
	 */
	function & connect($io) {
		if (isset($GLOBALS['_user']['active'])) {
			unset($GLOBALS['_user']['active']);
		}
		$GLOBALS['_user']['active'] = new User($io);
		return $GLOBALS['_user']['active'];
	}

	/**
	 * Returns the current active User object set by a prior call to connect.
	 * @see connect
	 * @return User singleton
	 * @access public
	 */
	function & singleton() {
		if (!isset($GLOBALS['_user']['active'])) {
			Error::err(__FILE__, __LINE__, 'User is not connected to any driver. Consider User::connect(...) in your app.php');
		}
		return $GLOBALS['_user']['active'];
	}

	//--------------------------------------------------------------------------------

	/**
	 * Private constructor.  User is a singleton, access is given to a single object by
	 * connect and singleton.
	 * @param DataSpace
	 * @see connect
	 * @see singleton
	 * @access protected
	 */
	function User($io) {
		$this->DataSpaceDecorator($io);
	}

	/**
	 * Attempts to read the user data from the data source
	 */
	function read() {
		$s = & Session :: singleton();
		$uid = $s->getUID();
		if ($uid != 0) {
			parent :: read($s->getUID());
		}
	}

	/**
	 * Attempts to login using the given $usr and $pwd to authenticate the user
	 * @param string
	 * @param string
	 */
	function login($usr, $pwd) {
		$retval = false;
		$s = & Session :: singleton();
		$db = & DBKit :: singleton();
		$sql = "SELECT uid,priv,pwd FROM " . SGF_UserTable . " WHERE usr='$usr' LIMIT 1";
		$result = $db->Execute($sql);
		if ($result) {
			$row = $result->FetchRow();
			if ($row && password_check($pwd, $row['pwd'])) {
				$s->setUID($row['uid']);
				$s->setPrivilege($row['priv']);
				parent::read($row['uid']);
				$retval = true;
			} else {
				$s->setPrivilege(0);
			}
		} else {
			$s->setPrivilege(0);
		}
		return $retval;
	}

	/**
	 * Logs the current user out.
	 * Sets the privilege in the session to 0
	 */
	function logout() {
		$s = & Session :: singleton();
		$s->setPrivilege(0);
		// write our cookie as this never seems to happen
		//$s->writeCookie();
	}

	/**
	 * Returns true if the user $usr exists
	 * @param string
	 * @return boolean
	 */
	function exists($usr) {
		$ret = false;
		$db = & DBKit :: singleton();
		$sql = "SELECT uid FROM " . SGF_UserTable . " WHERE usr='$usr' LIMIT 1";
		$result = $db->Execute($sql);
		if ($result) {
			$row = $result->FetchRow();
			if ($row) {
				$ret = true;
			}
		}
		return $ret;
	}

}
?>

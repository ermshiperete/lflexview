<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package		ARK
 * @subpackage	Data
 * @version    $Id: session.php,v 1.1.1.1 2006/04/13 01:11:01 cambell Exp $
 * @author		  Cambell Prince <cambell@arketec.com>
 * @link				http://www.arketec.com
 * @see
 */

/**
 */
require_once (SGF_CORE.'Data/ValueSpace.php');
require_once (SGF_CORE.'Data/SessionSchema.php');
require_once (SGF_CORE.'Data/SimpleTreeSpace.php');
require_once (SGF_CORE.'Util/Sqlformat.php');

/**
 * Security defines
 */
define('SESSION_None', 0);
define('SESSION_IP', 1);
define('SESSION_Referrer', 2);
define('SESSION_Browser', 4);
define('SESSION_All', 7);

/**
 * State defines
 */
define('SESSION_NoState', 1);
define('SESSION_NewKey', 2);
define('SESSION_OldKey', 3);
define('SESSION_Valid', 11);
define('SESSION_Expired', 12);
define('SESSION_Insecure', 13);

/**
 * The Session is an IDataSpace implemented by ArraySpace that delgates read and write to a
 * storage mechanism implemented by a driver.
 * @package		ARK
 * @subpackage	Data
 * @see http://arketec.com
 * @abstract
 * @access public
 */
class Session extends ValueSpace {

	const FLD_SK = 'sk';
	const FLD_UID = 'uc';
	const FLD_Priv = 'priv';
	const FLD_IP = 'ip';
	const FLD_Browser = 'browser';
	const FLD_Expiry = 'dtm';
	const FLD_Created = 'dtc';
	const FLD_Story = 'story';
	const FLD_Data = 'data';

	const SPC_State = 'state';

	/**
	 * Creates a Session with the driver $uri if one does not already exists.
	 * This Session becomes the active session. i.e. That returned by singleton.
	 * Supported schemes are:
	 * 	datakit://scid/source
	 *
	 * @param string $uri
	 * @see singleton
	 * @return Session singleton
	 */
	public static function connect($uri) {
		// the defaults
		$s = split(':', $uri, 2);
		$scheme = $s[0];
		$dataMapper = NULL;
		$source = '';
		$scid = 'default';
		switch ($scheme) {
			case 'datakit':
				// Determine the scid and source
				$parts = self::parseURIDataKit($uri);
				$scid = $parts['scid'];
				$source = $parts['source'];
				$dataMapper = DataKit::createMapper(DataKit::SessionMapper, $scid);
				break;
		}
		$session = new Session($dataMapper);
		$schema = $session->getSchema();
		$schema->set(SC_Meta_Source, $source);
		$GLOBALS['_session']['active'] = $session;
		return $GLOBALS['_session']['active'];
	}

	public static function parseURIDataKit($uri) {
		$retval = FALSE;
		$matches = array();
		$result = preg_match('/\b((?#protocol)datakit):\/\/((?#scid)[^:@\/]+)\/((?#table)[^:@\/]+)/', $uri, $matches);
		if ($result == 1) {
			$retval = array();
			$retval['protocol'] = $matches[1];
			$retval['scid'] = $matches[2];
			$retval['source'] = $matches[3];
		}
		return $retval;
	}

	/**
	 * Return true if a session is connected.
	 * @return boolean
	 */
	public static function isConnected() {
		return isset($GLOBALS['_session']['active']);
	}

	/**
	 * Returns the current active session object set by a prior call to connect.
	 * @see connect
	 * @return Session singleton
	 */
	public static function & singleton() {
		if (!isset($GLOBALS['_session']['active'])) {
			Error::log(__FILE__, __LINE__, 'Session not connected');
			$GLOBALS['_session']['active'] = new Session();
		}
		return $GLOBALS['_session']['active'];
	}

	//--------------------------------------------------------------------------------

	/**
	 * _options
	 * @see defines above
	 * @access protected
	 */
	var $_options;

	/**
	 * Indicates how secure this session is.
	 * This is nothing to do with user privilege, or login state. Rather it is how well this session relates
	 * to what the user has previously done. e.g. same IP address, same browser and so on.
	 * @access protected
	 */
	var $_secureState;

	/**
	 * Indicates how state of the key
	 * It is either a new key or an old key (i.e. existing key).
	 * @access protected
	 */
	var $_keyState;

	/**
	 * @var IDataSpace
	 */
	var $_state;

	/**
	 * Private constructor.  Session is a singleton, access is given to a single object by
	 * connect and singleton.
	 * @see connect
	 * @see singleton
	 * @access protected
	 */
	public function __construct($dataMapper) {
		parent::__construct($dataMapper);
		$this->setSchema( new SessionSchema());
		$this->reset();
	}

	/**
	 * Reset the session to its default state
	 */
	function reset() {
		$this->_options['security_0'] = SESSION_All;
		$this->_options['CookieTime'] = 31536000; // 365 days in seconds
		$this->_options['Expiry'] = 60; // 60 minutes
		$this->_options['WriteCookie'] = true;
		if (defined('COOKIES_ALLOW_WRITE')) {
			$this->_options['WriteCookie'] = COOKIES_ALLOW_WRITE;
		}
		$this->_options['ReadCookie'] = true;
		if (defined('COOKIES_ALLOW_READ')) {
			$this->_options['ReadCookie'] = COOKIES_ALLOW_READ;
		}
		// Set dtc, this will be overwritten on read if it exists.
		$t = sqlfmt_toISOTimeUTC();
		$this->set(self::FLD_Created, $t);

		$this->set(self::FLD_Story, '');

		$this->set(self::FLD_SK, '');

		$this->setID(0);

		$this->set(self::FLD_UID, 0);
		$this->set(self::FLD_Priv, 0);

		$this->_keyState = SESSION_NoState;
		$this->_secureState = SESSION_NoState;
	}

	/**
	 */
	public function read($sk = NULL) {
		//		$this->reset(); // TODO: review !!! Should this reset here?
		$done = false;
		if ($sk == null) {
			// Try the GET URL first
			if (!$done) {
				if (isset($_GET['sk'])) {
					$this->set(self::FLD_SK, $_GET['sk']);
					$this->_keyState = SESSION_OldKey;
					if (parent::read($_GET['sk'])) {
						$done = true;
						$this->set(self::FLD_Story, '');
						$this->addToStory('sk from GET URL old key');
					} else {
						$this->set(self::FLD_Story, '');
						$this->addToStory('sk from GET URL no data');
					}
				}
			}
			// Try the cookie next
			if ($this->_options['ReadCookie'] && !$done) {
				$key = $this->readCookie();
				if ($key) {
					$this->set(self::FLD_SK, $key);
					$this->_keyState = SESSION_OldKey;
					if (parent::read($key)) {
						$done = true;
						$this->set(self::FLD_Story, '');
						$this->addToStory('sk from cookie old key');
					} else {
						$this->set(self::FLD_Story, '');
						$this->addToStory('sk from cookie no data');
					}
				}
			}
			// Otherwise make a new sk
			if (!$done) {
				// Create a new sk
				$this->set(self::FLD_SK, $this->createKey());
				$this->_keyState = SESSION_NewKey;
				$this->addToStory('sk from new key');
			}
		} else {
			// TODO review: Surely this is not possible / a good thing???
			// commented out for now.
			/*
			 $this->sk_ = $sk;
			 $this->_keyState = SESSION_OldKey;
			 $this->_story[] = 'sk set';
			 $this->readData();
			 */
			throw new Exception("What do you think you're doing!");
		}
		// Check that this sk is secure
		if ($this->isSecure()) {
			if ($this->isRecent()) {
				$this->_secureState = SESSION_Valid;
			} else {
				$this->_secureState = SESSION_Expired;
				// TODO Notify observers or post message
				// eraseAll is quite a harsh measure
				// an observer / event handler may just do logout but keep the data.
				$this->addToStory("session expired reset privilege");
				$this->setPrivilege(0);
				//				$this->eraseAll();
			}
		} else {
			// TODO Notify observers or post message
			$this->_secureState = SESSION_Insecure;

			// eraseAll is quite a harsh measure
			// an observer / event handler may just do logout but keep the data.
			$this->addToStory("session insecure reset privilege");
			$this->setPrivilege(0);
			//		  $this->eraseAll();
		}
		if ($this->_options['WriteCookie']) {
			// Update the cookie if we can
			$this->writeCookie();
		}
		//		print_r($this->get(self::FLD_Story));
	}

	/**
	 */
	function write($pid = NULL) {
		// Update expired
		$t = sqlfmt_toISOTimeUTC();
		$this->set(self::FLD_Expiry, $t);
		parent::write($pid);
	}

	function readCookie() {
		$ret = null;
		if (isset($_COOKIE['sgf_session'])) {
			$ret = $_COOKIE['sgf_session'];
		}
		return $ret;
	}

	function writeCookie() {
		$sk = $this->getSK();
		if ($sk) {
			setCookie('sgf_session', $sk, time() + $this->_options['CookieTime'], '/');
		}
	}

	/**
	 * Returns one of the Key States
	 * @return define
	 * @see
	 */
	public function getKeyState() {
		return $this->_keyState;
	}

	/**
	 * Returns the security state
	 * @return define
	 * @see
	 */
	public function getSecureState() {
		return $this->_secureState;
	}

	/**
	 * @param string $key
	 * @param string $value
	 */
	public function setOption($key, $value) {
		$this->_options[$key] = $value;
	}

	/**
	 * @return string
	 */
	public function getSK() {
		return $this->get(self::FLD_SK);
	}

	/**
	 * @access public
	 */
	private function getSKID() {
		// TODO is anyone really going to use this? unique on sk should be fine.
		return $this->getID();
	}

	/**
	 * Returns the user ID (UID) associated with this session.
	 * Note that this does NOT imply that the user currently has privilege to do anything.
	 * getPriv should be checked to determine this.
	 * @return integer
	 * @access public
	 * @see getPriv
	 */
	function getUID() {
		return $this->get(self::FLD_UID);
	}

	/**
	 * Sets the user ID (UID) associated with this session.
	 * This should only be used by classes well qualified to make this decision. e.g. User
	 * @param integer
	 * @access public
	 * @see User
	 */
	function setUID($uid) {
		$this->set(self::FLD_UID, $uid);
	}

	/**
	 * Returns the privilege associated with this session.
	 * @return integer
	 * @access public
	 */
	function getPrivilege() {
		return $this->get(self::FLD_Priv);
	}

	/**
	 * Sets the privilege associated with this session.
	 * @param integer
	 * @access public
	 */
	function setPrivilege($privilege) {
		$this->set(self::FLD_Priv, $privilege);
	}

	/**
	 */
	public function createKey() {
		$t = microtime();
		$s = $_SERVER['UNIQUE_ID'].$_SERVER['REMOTE_ADDR'].$t.$_SERVER['HTTP_USER_AGENT'];
		$ret = md5($s);
		return $ret;
	}

	/**
	 * Determines whether the session is current (recent) or expired
	 * @return boolean
	 */
	public function isRecent() {
		$ret = true;
		$isotime = $this->get(self::FLD_Expiry);
		if ($isotime == null) {
			$isotime = sqlfmt_toISOTimeUTC();
			$this->set(self::FLD_Expiry, $isotime);
		}
		$t = sqlfmt_fromISOTimeUTC($isotime);
		$now = time();
		if ($t + $this->_options['Expiry'] * 60 < $now) {
			$ret = false;
			$this->addToStory('Session expired '.$isotime.' UTC');
		}
		return $ret;
	}

	/**
	 * Determines whether the session is secure.
	 * In this context secure means 'well tied to the user'.
	 * @return boolean
	 * @access protected
	 */
	function isSecure() {
		$ret = true;
		// Having all options off is not a good idea.
		$options = $this->_options['security_0'];
		if (!$options) {
			$options = SESSION_All;
		}
		// Check IP
		if (($options & SESSION_IP) != 0) {
			$ip = $this->get(self::FLD_IP);
			if ($ip == null) {
				$this->set(self::FLD_IP, $_SERVER['REMOTE_ADDR']);
			} else if ($ip != $_SERVER['REMOTE_ADDR']) {
				$ret = false;
				$this->addToStory("Could not fix to IP: $ip vs ".$_SERVER['REMOTE_ADDR']);
			}
		}

		// Check referrer
		if (($options & SESSION_Referrer) != 0) {
		}

		// Check browser
		if (($options & SESSION_Browser) != 0) {
			$browser = $this->get(self::FLD_Browser);
			if ($browser != $_SERVER['HTTP_USER_AGENT']) {
				$ret = false;
				$this->addToStory("Could not fix to browser: $browser vs ".$_SERVER['HTTP_USER_AGENT']);
				$this->set(self::FLD_Browser, $_SERVER['HTTP_USER_AGENT']);
			}
		}

		return $ret;

	}

	private function addToStory($msg) {
		$story = $this->get(self::FLD_Story);
		$story .= $msg."\n";
		$this->set(self::FLD_Story, $story);
	}



	/**
	 * @see DataSpaceBase::setSpace()
	 */
	function setSpace($name, $space) {
		if ($name == self::SPC_State) {
			$this->_state = $space;
		} else {
			parent::setSpace($name, $space);
		}
	}

	/**
	 * @see SimpleValueSpace::getSpace()
	 */
	function getSpace($name) {
		if ($name == self::SPC_State) {
			return $this->_state;
		} else {
			return parent::getSpace($name);
		}
	}

}

?>

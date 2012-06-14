<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package    ARK
 * @subpackage Data
 * @author     Cambell Prince <cambell@arketec.com>
 * @link       http://www.arketec.com
 */

/**
 */
require_once (SGF_CORE.'Data/DataSpaceBase.php');
require_once (SGF_CORE.'Data/IDataSpace.php');

class MySqlValueSpace extends ValueSpace {

	function __construct($dsid = 'default') {
		parent::__construct(
			DataKit::get($dsid)->createMapper(MySqlConnection::ValueMapper)
		);

	}

}

class MySqlListSpace extends ListSpace {

	function __construct($dsid = 'default') {
		parent::__construct(
			DataKit::get($dsid)->createMapper(MySqlConnection::ListMapper)
		);

	}

}

?>

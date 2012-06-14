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
require_once (SGF_CORE."Data/TreeSpace.php");

/**
 * Gets the node from the path in the given DataSpace.
 * @param string The path to set
 * @param IDataSpace The space containing the data
 * @return IDataSpace
 */
function dot_getSpace($path, $v) {
	$ret = null;
	$dotPath = explode(".", $path);
	$r0 = $v;
	$c = count($dotPath);
	for ($i = 0; $i < $c; $i++) {
		$r1 = $r0->getSpaceInSpace(DataSpaceBase::Spaces, $dotPath[$i]);
		if ($r1 == null) {
			$r0->setSpaceInSpace(DataSpaceBase::Spaces, $dotPath[$i], new TreeSpace());
			$r1 = $r0->getSpaceInSpace(DataSpaceBase::Spaces, $dotPath[$i]);
			assert($r1 != null);
		}
		$r0 = $r1;
	}
	$ret = $r0;
	return $ret;
}

?>

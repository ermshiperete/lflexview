<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package    SayGoForms
 * @subpackage Parts
 * @author     Cambell Prince <cambell@arketec.com>
 * @link       http://www.arketec.com
 */

/**
 */
class PartKit
{
	public static function textControl($name, $viewProvider, $position, $form, $id, $label, $size) {
		require_once(SGF_CORE.'Parts/Controls/TextControl.php');
		return new TextControl($name, $viewProvider, $position, $form, $id, $label, $size);
	}

	public static function textAreaControl($name, $viewProvider, $position, $form, $id, $label, $rows, $cols) {
		require_once(SGF_CORE.'Parts/Controls/TextControl.php');
		return new TextArea($name, $viewProvider, $position, $form, $id, $label, $rows, $cols);
	}

	public static function buttonControl($name, $viewProvider, $position, $form, $id, $label) {
		require_once(SGF_CORE.'Parts/Controls/ButtonControl.php');
		return new ButtonControl($name, $viewProvider, $position, $form, $id, $label);
	}

	public static function radioControl($name, $viewProvider, $position, $form, $id, $label, $selectProvider) {
		require_once(SGF_CORE.'Parts/Controls/RadioControl.php');
		return new RadioControl($name, $viewProvider, $position, $form, $id, $label, $selectProvider);
	}

	public static function checkControl($name, $viewProvider, $position, $form, $id, $label) {
		require_once(SGF_CORE.'Parts/Controls/CheckControl.php');
		return new CheckControl($name, $viewProvider, $position, $form, $id, $label);
	}

	public static function comboControl($name, $viewProvider, $position, $form, $id, $label, $selectProvider) {
		require_once(SGF_CORE.'Parts/Controls/ComboControl.php');
		return new ComboControl($name, $viewProvider, $position, $form, $id, $label, $selectProvider);
	}

}

?>
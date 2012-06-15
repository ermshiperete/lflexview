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
require_once (SGF_CORE.'Controller/Part.php');
require_once (SGF_CORE.'Data/ValueSpace.php');
require_once (SGF_CORE.'Data/FileValueMapper.php');
require_once (SGF_CORE.'Parts/Std/FileContentSchema.php');

/**
 * A FileContent part
 * @package		ARK
 * @subpackage	Parts
 */
class FileContent extends Part {
	// TODO Could easily refactor this class a) DataPart b) add _content as child
	private $_data;

	private $_baseFilePath;

	/**
	 * @param string $name
	 * @param IViewProvider $viewProvider
	 * @param string $position
	 */
	function __construct($name, $viewProvider, $position, $baseFilePath) {
		parent::__construct($name, $viewProvider, $position);

		$this->_data = $this->createDataSpace();
		$this->_baseFilePath = $baseFilePath;

		$this->addChild(new Part(
			'FileContentContent', 
		ViewKit::providerFromFile(self::viewFilePath($baseFilePath)),
			'Content'
			));

	}

	/**
	 * @return IDataSpace
	 */
	function createDataSpace() {
		$space = new ValueSpace(new FileValueMapper());
		$space->setSchema(new FileContentSchema());
		return $space;
	}

	function onRender($e, $t) {
		parent::onRender($e, $t);
		// Load the meta data
		$this->_data->setMeta(SC_Meta_Source, self::metaFilePath($this->_baseFilePath));
		$this->_data->read();
		// Push the meta data into the template
		$this->_view->pushDataAsText($this->_data);
	}

	/**
	 * Returns the FilePath of the meta file.
	 * $baseFilePath.meta
	 * @param string $baseFilePath
	 * @return string
	 */
	public static function metaFilePath($baseFilePath) {
		return $baseFilePath . '.meta';
	}

	/**
	 * Returns the FilePath of the view file.
	 * $baseFilePath.html.php
	 * @param string $baseFilePath
	 * @return string
	 */
	public static function viewFilePath($baseFilePath) {
		return $baseFilePath . '.html.php';
	}
}

?>

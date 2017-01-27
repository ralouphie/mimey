<?php

namespace Mimey;

/**
 * Class for converting MIME types to file extensions and vice versa.
 */
class MimeTypes implements MimeTypesInterface
{
	/** @var array The cached built-in mapping array. */
	private static $built_in;

	/** @var array The mapping array. */
	protected $mapping;

	/**
	 * Create a new mime types instance with the given mappings.
	 *
	 * If no mappings are defined, they will default to the ones included with this package.
	 *
	 * @param array $mapping An associative array containing two entries.
	 * Entry "mimes" being an associative array of extension to array of MIME types.
	 * Entry "extensions" being an associative array of MIME type to array of extensions.
	 * Example:
	 * <code>
	 * array(
	 *   'extensions' => array(
	 *     'application/json' => array('json'),
	 *     'image/jpeg'       => array('jpg', 'jpeg'),
	 *     ...
	 *   ),
	 *   'mimes' => array(
	 *     'json' => array('application/json'),
	 *     'jpeg' => array('image/jpeg'),
	 *     ...
	 *   )
	 * )
	 * </code>
	 */
	public function __construct($mapping = null)
	{
		if ($mapping === null) {
			$this->mapping = self::getBuiltIn();
		} else {
			$this->mapping = $mapping;
		}
	}

	/**
	 * @inheritdoc
	 */
	public function getMimeType($extension)
	{
		$extension = $this->cleanInput($extension);
		if (!empty($this->mapping['mimes'][$extension])) {
			return $this->mapping['mimes'][$extension][0];
		}
		return null;
	}

	/**
	 * @inheritdoc
	 */
	public function getExtension($mime_type)
	{
		$mime_type = $this->cleanInput($mime_type);
		if (!empty($this->mapping['extensions'][$mime_type])) {
			return $this->mapping['extensions'][$mime_type][0];
		}
		return null;
	}

	/**
	 * @inheritdoc
	 */
	public function getAllMimeTypes($extension)
	{
		$extension = $this->cleanInput($extension);
		if (isset($this->mapping['mimes'][$extension])) {
			return $this->mapping['mimes'][$extension];
		}
		return array();
	}

	/**
	 * @inheritdoc
	 */
	public function getAllExtensions($mime_type)
	{
		$mime_type = $this->cleanInput($mime_type);
		if (isset($this->mapping['extensions'][$mime_type])) {
			return $this->mapping['extensions'][$mime_type];
		}
		return array();
	}

	/**
	 * Get the built-in mapping.
	 *
	 * @return array The built-in mapping.
	 */
	protected static function getBuiltIn()
	{
		if (self::$built_in === null) {
			self::$built_in = require(dirname(__DIR__) . '/mime.types.php');
		}
		return self::$built_in;
	}

	/**
	 * Normalize the input string using lowercase/trim.
	 *
	 * @param string $input The string to normalize.
	 *
	 * @return string The normalized string.
	 */
	private function cleanInput($input)
	{
		return strtolower(trim($input));
	}
}

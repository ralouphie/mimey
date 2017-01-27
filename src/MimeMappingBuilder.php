<?php

namespace Mimey;

/**
 * Class for converting MIME types to file extensions and vice versa.
 */
class MimeMappingBuilder
{
	/** @var array The mapping array. */
	protected $mapping;

	/**
	 * Create a new mapping builder.
	 *
	 * @param array $mapping An associative array containing two entries. See `MimeTypes` constructor for details.
	 */
	private function __construct($mapping)
	{
		$this->mapping = $mapping;
	}

	/**
	 * Add a conversion.
	 *
	 * @param string $mime The MIME type.
	 * @param string $extension The extension.
	 * @param bool   $prepend_extension Whether this should be the preferred conversion for MIME type to extension.
	 * @param bool   $prepend_mime Whether this should be the preferred conversion for extension to MIME type.
	 */
	public function add($mime, $extension, $prepend_extension = true, $prepend_mime = true)
	{
		$existing_extensions = empty($this->mapping['extensions'][$mime]) ? array() : $this->mapping['extensions'][$mime];
		$existing_mimes = empty($this->mapping['mimes'][$extension]) ? array() : $this->mapping['mimes'][$extension];
		if ($prepend_extension) {
			array_unshift($existing_extensions, $extension);
		} else {
			$existing_extensions[] = $extension;
		}
		if ($prepend_mime) {
			array_unshift($existing_mimes, $mime);
		} else {
			$existing_mimes[] = $mime;
		}
		$this->mapping['extensions'][$mime] = array_unique($existing_extensions);
		$this->mapping['mimes'][$extension] = array_unique($existing_mimes);
	}

	/**
	 * @return array The mapping.
	 */
	public function getMapping()
	{
		return $this->mapping;
	}

	/**
	 * Compile the current mapping to PHP.
	 *
	 * @return string The compiled PHP code to save to a file.
	 */
	public function compile()
	{
		$mapping = $this->getMapping();
		$mapping_export = var_export($mapping, true);
		return "<?php return $mapping_export;";
	}

	/**
	 * Save the current mapping to a file.
	 *
	 * @param string   $file    The file to save to.
	 * @param int      $flags   Flags for `file_put_contents`.
	 * @param resource $context Context for `file_put_contents`.
	 *
	 * @return int|bool The number of bytes that were written to the file, or false on failure.
	 */
	public function save($file, $flags = null, $context = null)
	{
		return file_put_contents($file, $this->compile(), $flags, $context);
	}

	/**
	 * Create a new mapping builder based on the built-in types.
	 *
	 * @return MimeMappingBuilder A mapping builder with built-in types loaded.
	 */
	public static function create()
	{
		return self::load(dirname(__DIR__) . '/mime.types.php');
	}

	/**
	 * Create a new mapping builder based on types from a file.
	 *
	 * @param string $file The compiled PHP file to load.
	 *
	 * @return MimeMappingBuilder A mapping builder with types loaded from a file.
	 */
	public static function load($file)
	{
		return new self(require($file));
	}

	/**
	 * Create a new mapping builder that has no types defined.
	 *
	 * @return MimeMappingBuilder A mapping builder with no types defined.
	 */
	public static function blank()
	{
		return new self(array('mimes' => array(), 'extensions' => array()));
	}
}

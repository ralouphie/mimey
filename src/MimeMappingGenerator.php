<?php

namespace Mimey;

/**
 * Generates a mapping for use in the MimeTypes class.
 *
 * Reads text in the format of httpd's mime.types and generates a PHP array containing the mappings.
 */
class MimeMappingGenerator
{
	protected $mime_types_text;

	/**
	 * Create a new generator instance with the given mime.types text.
	 *
	 * @param string $mime_types_text The text from the mime.types file.
	 */
	public function __construct($mime_types_text)
	{
		$this->mime_types_text = $mime_types_text;
	}

	/**
	 * Read the given mime.types text and return a mapping compatible with the MimeTypes class.
	 *
	 * @return array The mapping.
	 */
	public function generateMapping()
	{
		$mapping = array();
		$lines = explode("\n", $this->mime_types_text);
		foreach ($lines as $line) {
			$line = trim(preg_replace('~\\#.*~', '', $line));
			$parts = $line ? array_values(array_filter(explode("\t", $line))) : array();
			if (count($parts) === 2) {
				$mime = trim($parts[0]);
				$extensions = explode(' ', $parts[1]);
				foreach ($extensions as $extension) {
					$extension = trim($extension);
					if ($mime && $extension) {
						$mapping['mimes'][$extension][] = $mime;
						$mapping['extensions'][$mime][] = $extension;
						$mapping['mimes'][$extension] = array_unique($mapping['mimes'][$extension]);
						$mapping['extensions'][$mime] = array_unique($mapping['extensions'][$mime]);
					}
				}
			}
		}
		return $mapping;
	}

	/**
	 * Read the given mime.types text and generate mapping code.
	 *
	 * @return string The mapping PHP code for inclusion.
	 */
	public function generateMappingCode()
	{
		$mapping = $this->generateMapping();
		$mapping_export = var_export($mapping, true);
		return "<?php return $mapping_export;";
	}
}

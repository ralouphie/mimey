<?php

namespace Mimey;

/**
 * Class for converting MIME types to file extensions and vice versa.
 */
class MimeTypes implements MimeTypesInterface
{
    /** @var array|null The cached built-in mapping array. */
    private static ?array $built_in = null;

    /** @var array The mapping array. */
    protected array $mapping;

    /**
     * Create a new mime types instance with the given mappings.
     *
     * If no mappings are defined, they will default to the ones included with this package.
     *
     * @param array|null $mapping An associative array containing two entries.
     *                            Entry "mimes" being an associative array of extension to array of MIME types.
     *                            Entry "extensions" being an associative array of MIME type to array of extensions.
     *                            Example:
     *                            <code>
     *                            array(
     *                            'extensions' => array(
     *                            'application/json' => array('json'),
     *                            'image/jpeg'       => array('jpg', 'jpeg'),
     *                            ...
     *                            ),
     *                            'mimes' => array(
     *                            'json' => array('application/json'),
     *                            'jpeg' => array('image/jpeg'),
     *                            ...
     *                            )
     *                            )
     *                            </code>
     */
    public function __construct(array $mapping = null)
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
    public function getMimeType(string $extension): null|string
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
    public function getExtension(string $mime_type): null|string
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
    public function getAllMimeTypes(string $extension): array
    {
        $extension = $this->cleanInput($extension);

        return $this->mapping['mimes'][$extension] ?? [];
    }

    /**
     * @inheritdoc
     */
    public function getAllExtensions(string $mime_type): array
    {
        $mime_type = $this->cleanInput($mime_type);

        return $this->mapping['extensions'][$mime_type] ?? [];
    }

    /**
     * Get the built-in mapping.
     *
     * @return array The built-in mapping.
     */
    protected static function getBuiltIn(): array
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
    private function cleanInput(string $input): string
    {
        return strtolower(trim($input));
    }
}

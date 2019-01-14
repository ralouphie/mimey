<?php

namespace Mimey\Tests;

use Mimey\MimeTypes;
use PHPUnit\Framework\TestCase;

class MimeTypesTest extends TestCase
{
	/** @var \Mimey\MimeTypes */
	protected $mime;

	protected function setUp()
	{
		$this->mime = new MimeTypes([
			'mimes' => [
				'json' => ['application/json'],
				'jpeg' => ['image/jpeg'],
				'jpg' => ['image/jpeg'],
				'bar' => ['foo', 'qux'],
				'baz' => ['foo'],
			],
			'extensions' => [
				'application/json' => ['json'],
				'image/jpeg' => ['jpeg', 'jpg'],
				'foo' => ['bar', 'baz'],
				'qux' => ['bar'],
			],
		]);
	}

	public function getMimeTypeProvider()
	{
		return [
			['application/json', 'json'],
			['image/jpeg', 'jpeg'],
			['image/jpeg', 'jpg'],
			['foo', 'bar'],
			['foo', 'baz'],
		];
	}

	/**
	 * @dataProvider getMimeTypeProvider
	 */
	public function testGetMimeType($expectedMimeType, $extension)
	{
		$this->assertEquals($expectedMimeType, $this->mime->getMimeType($extension));
	}

	public function getExtensionProvider()
	{
		return [
			['json', 'application/json'],
			['jpeg', 'image/jpeg'],
			['bar', 'foo'],
			['bar', 'qux'],
		];
	}

	/**
	 * @dataProvider getExtensionProvider
	 */
	public function testGetExtension($expectedExtension, $mimeType)
	{
		$this->assertEquals($expectedExtension, $this->mime->getExtension($mimeType));
	}

	public function getAllMimeTypesProvider()
	{
		return [
			[
				['application/json'], 'json',
			],
			[
				['image/jpeg'], 'jpeg',
			],
			[
				['image/jpeg'], 'jpg',
			],
			[
				['foo', 'qux'], 'bar',
			],
			[
				['foo'], 'baz',
			],
		];
	}

	/**
	 * @dataProvider getAllMimeTypesProvider
	 */
	public function testGetAllMimeTypes($expectedMimeTypes, $extension)
	{
		$this->assertEquals($expectedMimeTypes, $this->mime->getAllMimeTypes($extension));
	}

	public function getAllExtensionsProvider()
	{
		return [
			[
				['json'], 'application/json',
			],
			[
				['jpeg', 'jpg'], 'image/jpeg',
			],
			[
				['bar', 'baz'], 'foo',
			],
			[
				['bar'], 'qux',
			],
		];
	}

	/**
	 * @dataProvider getAllExtensionsProvider
	 */
	public function testGetAllExtensions($expectedExtensions, $mimeType)
	{
		$this->assertEquals($expectedExtensions, $this->mime->getAllExtensions($mimeType));
	}

	public function testGetMimeTypeUndefined()
	{
		$this->assertNull($this->mime->getMimeType('undefined'));
	}

	public function testGetExtensionUndefined()
	{
		$this->assertNull($this->mime->getExtension('undefined'));
	}

	public function testGetAllMimeTypesUndefined()
	{
		$this->assertEquals([], $this->mime->getAllMimeTypes('undefined'));
	}

	public function testGetAllExtensionsUndefined()
	{
		$this->assertEquals([], $this->mime->getAllExtensions('undefined'));
	}

	public function testBuiltInMapping()
	{
		$mime = new MimeTypes();
		$this->assertEquals('json', $mime->getExtension('application/json'));
		$this->assertEquals('application/json', $mime->getMimeType('json'));
	}
}

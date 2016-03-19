<?php

class MimeTypesTest extends \PHPUnit_Framework_TestCase
{
	/** @var \Mimey\MimeTypes */
	protected $mime;

	protected function setUp()
	{
		$this->mime = new \Mimey\MimeTypes(array(
			'mimes' => array(
				'json' => array('application/json'),
				'jpeg' => array('image/jpeg'),
				'jpg' => array('image/jpeg'),
				'bar' => array('foo', 'qux'),
				'baz' => array('foo')
			),
			'extensions' => array(
				'application/json' => array('json'),
				'image/jpeg' => array('jpeg', 'jpg'),
				'foo' => array('bar', 'baz'),
				'qux' => array('bar')
			)
		));
	}

	public function testGetMimeType()
	{
		$this->assertEquals('application/json', $this->mime->getMimeType('json'));
		$this->assertEquals('image/jpeg', $this->mime->getMimeType('jpeg'));
		$this->assertEquals('image/jpeg', $this->mime->getMimeType('jpg'));
		$this->assertEquals('foo', $this->mime->getMimeType('bar'));
		$this->assertEquals('foo', $this->mime->getMimeType('baz'));
	}

	public function testGetExtension()
	{
		$this->assertEquals('json', $this->mime->getExtension('application/json'));
		$this->assertEquals('jpeg', $this->mime->getExtension('image/jpeg'));
		$this->assertEquals('bar', $this->mime->getExtension('foo'));
		$this->assertEquals('bar', $this->mime->getExtension('qux'));
	}

	public function testGetAllMimeTypes()
	{
		$this->assertEquals(array('application/json'), $this->mime->getAllMimeTypes('json'));
		$this->assertEquals(array('image/jpeg'), $this->mime->getAllMimeTypes('jpeg'));
		$this->assertEquals(array('image/jpeg'), $this->mime->getAllMimeTypes('jpg'));
		$this->assertEquals(array('foo', 'qux'), $this->mime->getAllMimeTypes('bar'));
		$this->assertEquals(array('foo'), $this->mime->getAllMimeTypes('baz'));
	}

	public function testGetAllExtensions()
	{
		$this->assertEquals(array('json'), $this->mime->getAllExtensions('application/json'));
		$this->assertEquals(array('jpeg', 'jpg'), $this->mime->getAllExtensions('image/jpeg'));
		$this->assertEquals(array('bar', 'baz'), $this->mime->getAllExtensions('foo'));
		$this->assertEquals(array('bar'), $this->mime->getAllExtensions('qux'));
	}

	public function testGetMimeTypeUndefined()
	{
		$this->assertEquals(null, $this->mime->getMimeType('undefined'));
	}

	public function testGetExtensionUndefined()
	{
		$this->assertEquals(null, $this->mime->getExtension('undefined'));
	}

	public function testGetAllMimeTypesUndefined()
	{
		$this->assertEquals(array(), $this->mime->getAllMimeTypes('undefined'));
	}

	public function testGetAllExtensionsUndefined()
	{
		$this->assertEquals(array(), $this->mime->getAllExtensions('undefined'));
	}

	public function testBuiltInMapping()
	{
		$mime = new \Mimey\MimeTypes();
		$this->assertEquals('json', $mime->getExtension('application/json'));
		$this->assertEquals('application/json', $mime->getMimeType('json'));
	}
}

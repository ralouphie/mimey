<?php

namespace Mimey\Tests;

use Mimey\MimeTypes;
use Mimey\MimeMappingBuilder;
use PHPUnit\Framework\TestCase;

class MimeMappingBuilderTest extends TestCase
{
	public function testFromEmpty()
	{
		$builder = MimeMappingBuilder::blank();
		$builder->add('foo/bar', 'foobar');
		$builder->add('foo/bar', 'bar');
		$builder->add('foo/baz', 'foobaz');
		$mime = new MimeTypes($builder->getMapping());
		$this->assertEquals('bar', $mime->getExtension('foo/bar'));
		$this->assertEquals(['bar', 'foobar'], $mime->getAllExtensions('foo/bar'));
		$this->assertEquals('foobaz', $mime->getExtension('foo/baz'));
		$this->assertEquals(['foobaz'], $mime->getAllExtensions('foo/baz'));
		$this->assertEquals('foo/bar', $mime->getMimeType('foobar'));
		$this->assertEquals(['foo/bar'], $mime->getAllMimeTypes('foobar'));
		$this->assertEquals('foo/bar', $mime->getMimeType('bar'));
		$this->assertEquals(['foo/bar'], $mime->getAllMimeTypes('bar'));
		$this->assertEquals('foo/baz', $mime->getMimeType('foobaz'));
		$this->assertEquals(['foo/baz'], $mime->getAllMimeTypes('foobaz'));
	}

	public function testFromBuiltIn()
	{
		$builder = MimeMappingBuilder::create();
		$mime1 = new MimeTypes($builder->getMapping());
		$this->assertEquals('json', $mime1->getExtension('application/json'));
		$this->assertEquals('application/json', $mime1->getMimeType('json'));

		$builder->add('application/json', 'mycustomjson');
		$mime2 = new MimeTypes($builder->getMapping());
		$this->assertEquals('mycustomjson', $mime2->getExtension('application/json'));
		$this->assertEquals('application/json', $mime2->getMimeType('json'));

		$builder->add('application/mycustomjson', 'json');
		$mime3 = new MimeTypes($builder->getMapping());
		$this->assertEquals('mycustomjson', $mime3->getExtension('application/json'));
		$this->assertEquals('application/mycustomjson', $mime3->getMimeType('json'));
	}

	public function testAppendExtension()
	{
		$builder = MimeMappingBuilder::blank();
		$builder->add('foo/bar', 'foobar');
		$builder->add('foo/bar', 'bar', false);
		$mime = new MimeTypes($builder->getMapping());
		$this->assertEquals('foobar', $mime->getExtension('foo/bar'));
	}

	public function testAppendMime()
	{
		$builder = MimeMappingBuilder::blank();
		$builder->add('foo/bar', 'foobar');
		$builder->add('foo/bar2', 'foobar', true, false);
		$mime = new MimeTypes($builder->getMapping());
		$this->assertEquals('foo/bar', $mime->getMimeType('foobar'));
	}

	public function testSave()
	{
		$builder = MimeMappingBuilder::blank();
		$builder->add('foo/one', 'one');
		$builder->add('foo/one', 'one1');
		$builder->add('foo/two', 'two');
		$builder->add('foo/two2', 'two');
		$file = tempnam(sys_get_temp_dir(), 'mapping_test');
		$builder->save($file);
		$mapping_included = require $file;
		$this->assertEquals($builder->getMapping(), $mapping_included);
		$builder2 = MimeMappingBuilder::load($file);
		unlink($file);
		$this->assertEquals($builder->getMapping(), $builder2->getMapping());
	}
}

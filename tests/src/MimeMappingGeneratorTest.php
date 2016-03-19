<?php

class MimeMappingGeneratorTest extends \PHPUnit_Framework_TestCase
{
	public function testGenerateMapping()
	{
		$generator = new \Mimey\MimeMappingGenerator(
			"#ignore\tme\n" .
			"application/json\t\t\tjson\n" .
			"image/jpeg\t\t\tjpeg jpg #ignore this too\n\n" .
			"foo\tbar baz\n" .
			"qux\tbar\n"
		);
		$mapping = $generator->generateMapping();
		$expected = array(
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
		);
		$this->assertEquals($expected, $mapping);

		$code = $generator->generateMappingCode();
		$file = tempnam(sys_get_temp_dir(), 'mapping_test');
		file_put_contents($file, $code);
		$mapping_included = require $file;
		unlink($file);
		$this->assertEquals($mapping, $mapping_included);
	}
}

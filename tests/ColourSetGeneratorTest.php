<?php


use WeAreNotMachines\Similarity\Generators\ColourSetGenerator;

class ColourSetGeneratorTest extends PHPUnit_Framework_TestCase {
	
	private $generator;

	public function setUp() {
		$this->generator = new ColourSetGenerator();
	}

	public function testGenerateFunctionGeneratesAnArrayOfColours() {

		$this->assertInternalType("array", $this->generator->generate(), 'Generate should return an array');
	}

	public function testGeneratorReturnsAUniqueSet() {
		$set = $this->generator->generate();
		$this->assertEquals(array_unique($set), $set, 'The sets should be the same');
	}

}
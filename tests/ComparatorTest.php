<?php

use WeAreNotMachines\Similarity\Comparator;

class ComparatorTest extends PHPUnit_Framework_TestCase {
	
	private $t1;
	private $tf;

	public function setup() {

		$this->t1 = new Comparator([
			"scalar" => [
				"colour" => "red",
				"size" => "large",
				"shape" => "round",
				"texture" => "furry"
			],
			"vector" => [
				"temparature" => 38.2,
				"weight" => 15,
				"height" => 102.56,
				"radius" => 19
			]
		]);


		$this->tf = new Comparator([
			"scalar" => [
				"colour" => ["red", "blue", "yellow"],
				"size" => "small",
				"shape" => "round",
				"texture" => "smooth"
			],
			"vector" => [
				"temparature" => 38.2,
				"weight" => 15,
				"height" => 102.56,
				"radius" => 19
			]
		]);
	}
	
	public function testScalarAttributesCanBeReturned() {
		$this->assertInternalType('array', $this->t1->getScalarAttributes(), "Scalar attributes returned from getScalarAttributes are not an array");
		$this->assertInternalType('array', $this->t1->getAttributes('scalar'), "Scalar attributes returned from getAttributes('scalar') are not an array");
		$this->assertEquals(["colour" => "red","size" => "large","shape" => "round","texture" => "furry"],$this->t1->getScalarAttributes(),"The scalar attributes from the test type should be the array tested");
	}

	public function testVectorAttributesCanBeReturned() {
		$this->assertInternalType('array', $this->t1->getScalarAttributes());
		$this->assertInternalType('array', $this->t1->getAttributes('vector'), "Vector attributes returned from getAttributes('vector') are not an array");
		$this->assertEquals(["temparature" => 38.2,"weight" => 15,"height" => 102.56,"radius" => 19], $this->t1->getVectorAttributes(), "The vector attributes from the test type should be the array tested");
	}

	/**
	 * @expectedException LogicException
	 */
	public function testExceptionThrownWhenValidatedWithNonScalarAttributes() {
		// $this->setExpectedException("LogicException", "The scalar attributes contain non-scalar values");
		$this->tf->validateScalarAttributes();
	}

}
<?php

use WeAreNotMachines\Similarity\Calculator;
use WeAreNotMachines\Similarity\Comparator;

class CalculatorTest extends PHPUnit_Framework_TestCase {

	private $dog;
	private $cat;
	private $bird;

	private $t1;
	private $t2;
	

	public function setup() {
		$this->dog = (object) ['legs'=>4, 'tail'=>true, 'name'=>'fido', 'mammal'=>true, 'weight'=>8, 'height'=>86.4, 'texture'=>'furry', 'colour'=>'brown'];
		$this->cat = (object) ['legs'=>4, 'tail'=>true, 'name'=>'kitty', 'mammal'=>true, 'weight'=>3, 'height'=>46.4, 'texture'=>'furry', 'colour'=>'black'];
		$this->bird = (object) ['legs'=>2, 'tail'=>true, 'name'=>'tweety', 'mammal'=>false, 'weight'=>0.8, 'height'=>18.4, 'texture'=>'feathery', 'colour'=>'multi'];

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

		$this->t2 = new Comparator([
			"scalar" => [
				"colour" => "red",
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

		$this->t3 = new Comparator([
			"scalar" => [
				"colour" => "black",
				"texture" => "smooth",
				"flavour" => "lemon",
			],
			"vector" => [
				"temparature" => 38.2,
				"height" => 102.56,
				"radius" => 19
			]
		]);

	}

	public function testDifferentLengthArraysAreNormalized() {
		$union = array("flavour"=>null, "size"=>null, "shape"=>null);
		ksort($union);
		$c = new Calculator($this->t1, $this->t3, false);
		$calcUnion = $c->getNormalizedAttributeFiller("scalar", null);
		$this->assertEquals(array_keys($union), array_keys($calcUnion), "The keys for the calculated union should match those provided");
		$c->normalizeValues();
		$this->assertEquals(array_keys($c->getComparator("a")->getScalarAttributes()), array_keys($c->getComparator("b")->getScalarAttributes()), "The keys for the scalar attributes between each comparator should be equal");
	}

	public function testJaccardSimilarityIsCalculated() {
		$c = new Calculator($this->t1, $this->t2);
		$this->assertEquals(0.5, $c->jaccardSimilarity(), "Jaccard Similarity between \$t1 and \$t2 scalar attributes is 0.5");
		$c2 = new Calculator($this->t1, $this->t3);
		$this->assertEquals(0, $c2->jaccardSimilarity(), "Jaccard Similariry between \$1 and \$t3 scalar attributes is 0");
		$c3 = new Calculator($this->t2, $this->t3);
		$this->assertEquals(0.2, $c3->jaccardSimilarity(), "Jaccard Similarity between \$t2 and \$t3 scalar attribtues is 0.2");
	}

}
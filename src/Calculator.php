<?php

namespace WeAreNotMachines\Similarity;

use WeAreNotMachines\Similarity\Analysers\JaccardAnalyser;

class Calculator {
	
	private $a;
	private $b;

	public function __construct(Comparator $a, Comparator $b, $normalize=true, $normalizeScalarAs=null, $normalizeVectorAs=0) {
		$this->a = $a;
		$this->b = $b;
		if ($normalize) $this->normalizeValues($normalizeScalarAs, $normalizeVectorAs);
	}

	public function normalizeValues($scalarReplacement=null, $vectorReplacement=0) {
		$scalarKeys = $this->getNormalizedAttributeFiller("scalar", $scalarReplacement);
		$vectorKeys = $this->getNormalizedAttributeFiller("vector", $vectorReplacement);

		$this->a->fillMissingAttributes("scalar", $scalarKeys);
		$this->b->fillMissingAttributes("scalar", $scalarKeys);
		$this->a->fillMissingAttributes("vector", $vectorKeys);
		$this->b->fillMissingAttributes("vector", $vectorKeys);
		return $this;
	}
	public function getNormalizedAttributeFiller($attributeType, $replaceWith) {
		$filler = array_fill_keys(array_keys(array_diff_key($this->a->getAttributes($attributeType), $this->b->getAttributes($attributeType)) + array_diff_key($this->b->getAttributes($attributeType), $this->a->getAttributes($attributeType))), $replaceWith);
		ksort($filler);
		return $filler;
	}

	public function getComparator($id) {
		if (!in_array(strtolower($id), array("a", "b"))) throw new \LogicException("Acceptable values for comparators in a calculator are a, b");
		return $this->{strtolower($id)};
	}

	public function getScalar($comparator) {
		return $this->getComparator($comparator)->getScalarAttributes();
	}

	public function getScalarKeys($comparator) {
		return array_keys($this->getScalar($comparator));
	}

	public function getVector($comparator) {
		return $this->getComparator($comparator)->getVectorAttributes();
	}

	public function getVectorKeys($comparator) {
		return array_keys($this->getVector($comparator));
	}

	public function jaccardSimilarity() {
		$a = new JaccardAnalyser($this->a, $this->b);
		return $a->similarity;
	}




}
<?php

namespace WeAreNotMachines\Similarity\Analysers;

use WeAreNotMachines\Similarity\Comparator;

class JaccardAnalyser {

	public $similarity;

	public function __construct(Comparator $a, Comparator $b) {
		$this->similarity = $this->analyse($a, $b);
	}

	public function analyse(Comparator $a, Comparator $b) {
		$aa = $a->getScalarAttributes();
		$ba = $b->getScalarAttributes();

		if (count($aa) != count($ba)) throw new \LogicException("Cannot compute similary: attributes arrays are unequal");

		$this->similarity = count(array_intersect_assoc($aa, $ba)) / count($aa);
		return $this->similarity;
	}

}
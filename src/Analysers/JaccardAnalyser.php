<?php

namespace WeAreNotMachines\Similarity\Analysers;

use WeAreNotMachines\Similarity\Comparator;
/**
 * Computes the Jaccard similarity between scalar attributes of two scalar attribute sets.
 * The Jaccard similarity is the ratio of features present between each attribute set.
 * For example, a pair of attributes sets with a cardinality of 8 features, with 4 features in common will have a Jaccard similarity of 0.5.
 * Jaccard Similarity is expressed as J(a, b) = |a intersect b| / |a union b| = |a intersect b| / |a| + |b| - |a intersect b|
 * When a and b have no attributes to compare they considered equal
 */
class JaccardAnalyser {

	public $similarity;

	public function __construct(Comparator $a, Comparator $b) {
		$this->similarity = $this->analyse($a, $b);
	}

	/**
	 * Calculates the Jacard Similarity between the comparators' scalar attributes
	 * @param  Comparator $a
	 * @param  Comparator $b
	 * @return float        Jaccard Similarity
	 */
	public function analyse(Comparator $a, Comparator $b) {
		$aa = $a->getScalarAttributes();
		$ba = $b->getScalarAttributes();

		if (count($aa) != count($ba)) throw new \LogicException("Cannot compute similary: attributes arrays are unequal");

		if (count($aa)==0 && count($ba)==0) return 1; //follows the convention that two empty objects are equal

		$this->similarity = count(array_intersect_assoc($aa, $ba)) / count($aa);
		return $this->similarity;
	}

}
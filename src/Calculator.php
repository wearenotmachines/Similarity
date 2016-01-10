<?php

namespace WeAreNotMachines\Similarity;

use WeAreNotMachines\Similarity\Analysers\JaccardAnalyser;
/**
 * A control class to run similarity calculations
 */
class Calculator {
	
	/**
	 * A comparator
	 * @var Comparator
	 */
	private $a;
	/**
	 * A comparator
	 * @var Comparator
	 */
	private $b;

	/**
	 * Instantiate a new Calculator instance with a pair of comparators
	 * @param Comparator $a                 
	 * @param Comparator $b                 
	 * @param boolean    $normalize         Whether or not to normalize the comparator attribute sets on construction
	 * @param mixed     $normalizeScalarAs A filler value for the normalized arrays
	 * @param integer    $normalizeVectorAs A filler value for the normalized arrays
	 */
	public function __construct(Comparator $a, Comparator $b, $normalize=true, $normalizeScalarAs=null, $normalizeVectorAs=0) {
		$this->a = $a;
		$this->b = $b;
		if ($normalize) $this->normalizeValues($normalizeScalarAs, $normalizeVectorAs);
	}

	/**
	 * Fills in missing keys in the comparator attribute sets with the values specified
	 * @param  mixed  $scalarReplacement Replacement value
	 * @param  numeric $vectorReplacement Replacement value for vector sets
	 * @return Calculator
	 */
	public function normalizeValues($scalarReplacement=null, $vectorReplacement=0) {
		$scalarKeys = $this->getNormalizedAttributeFiller("scalar", $scalarReplacement);
		$vectorKeys = $this->getNormalizedAttributeFiller("vector", $vectorReplacement);

		$this->a->fillMissingAttributes("scalar", $scalarKeys);
		$this->b->fillMissingAttributes("scalar", $scalarKeys);
		$this->a->fillMissingAttributes("vector", $vectorKeys);
		$this->b->fillMissingAttributes("vector", $vectorKeys);
		return $this;
	}
	/**
	 * Accessor for the attribute filler before it is applied to the Comparators
	 * This will compute the union set of attributes using the keys and return an array filled with the filler value specified
	 * @param  string $attributeType vector|scalar
	 * @param  mixed $replaceWith   The value to fill the missing attributes with
	 * @return array                
	 */
	public function getNormalizedAttributeFiller($attributeType, $replaceWith) {
		$filler = array_fill_keys(array_keys(array_diff_key($this->a->getAttributes($attributeType), $this->b->getAttributes($attributeType)) + array_diff_key($this->b->getAttributes($attributeType), $this->a->getAttributes($attributeType))), $replaceWith);
		ksort($filler);
		return $filler;
	}

	/**
	 * Accessor for either of the comparators
	 * @param  string $id a|b
	 * @return Comparator
	 */
	public function getComparator($id) {
		if (!in_array(strtolower($id), array("a", "b"))) throw new \LogicException("Acceptable values for comparators in a calculator are a, b");
		return $this->{strtolower($id)};
	}

	/**
	 * Accessor for the scalar of attributes of the specified comparator
	 * @param  string $comparator a|b
	 * @return array             scalar attributes set for the specified comparator
	 */
	public function getScalar($comparator) {
		return $this->getComparator($comparator)->getScalarAttributes();
	}
	/**
	 * Accessor for the keys of the scalar attributes for the specified comparator
	 * @param  string $comparator a|b
	 * @return array
	 */
	public function getScalarKeys($comparator) {
		return array_keys($this->getScalar($comparator));
	}
	/**
	 * Accessor for the vector of attributes of the specified comparator
	 * @param  string $comparator a|b
	 * @return array             vector attributes set for the specified comparator
	 */
	public function getVector($comparator) {
		return $this->getComparator($comparator)->getVectorAttributes();
	}
	/**
	 * Accessor for the keys of the vector attributes for the specified comparator
	 * @param  string $comparator a|b
	 * @return array
	 */
	public function getVectorKeys($comparator) {
		return array_keys($this->getVector($comparator));
	}

	/**
	 * Compute the Jaccard similarity between the scalar attributes of the pair of Comparators
	 * @return float The Jaccard Similarity
	 */
	public function jaccardSimilarity() {
		$a = new JaccardAnalyser($this->a, $this->b);
		return $a->similarity;
	}




}
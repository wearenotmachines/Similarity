<?php

namespace WeAreNotMachines\Similarity;
/**
 * A value object structured to hold attribute arrays for analysis.
 * Comparators will hold an array of scalar (binary) attributes (features that are either present by value or absent) and vector attributes (feature groups that define ordered sets of values that describe a vector or aggregate feature)
 */
class Comparator {
	
	/**
	 * The feature sets to compare
	 * @var array
	 */
	private $attributes = ["scalar"=>[], "vector"=>[]];

	/**
	 * Instantiate a new Comparator optionally from an array of attribute sets
	 * @param array|Callable $attributes an array of attributes or a generator function to create them 
	 */
	public function __construct($attributes=null) {
		$this->initialize($attributes);
	}

	/**
	 * Builds the attribute sets either from an array or from a Callable that generates the required array.
	 * If an array of attributes is passed it should be a hash of attributes keyed ['scalar'=>[], 'vector'=>[]], scalar attributes should be and will be considered as an unordered hash (keys will be sorted for consistency), vectors will be an ordered set either with fixed string keys or numeric indexes
	 * If a function is passed to generate the attributes array, it should return the hash as described above
	 * @param  array|Callable $withAttributes
	 * @return Comparator
	 */
	public function initialize($withAttributes=null) {
		if (empty($withAttributes)) return true;
		if (is_array($withAttributes)) {
			$this->attributes = $withAttributes;
		} else if (is_callable($withAttributes)) {
			$this->attributes = $withAttributes();
		}
		ksort($this->attributes['scalar']);
		return $this;
	}

	/**
	 * Adds an attribute
	 * @param string $type  scalar|vector
	 * @param string $key   The attribute key
	 * @param mixed $value The attribute value
	 */
	public function addAttribute($type, $key, $value) {
		$this->attributes[$type][$key] = $value;
		return $this;
	}

	/**
	 * Convenience function to add a scalar attribute
	 * @param string $key   the attribute key
	 * @param mixed $value The attribute value
	 */
	public function addScalarAttribute($key, $value) {
		$this->addAttribute("scalar", $jey, $value);
		ksort($this->attributes['scalar']);
		return $this;
	}

	/**
	 * A convenience function to add a vector attribute
	 * @param string $key   the attribute name
	 * @param mixed $value the attribute value
	 */
	public function addVectorAttribute($key, $value) {
		return $this->addAttribute("vector", $key, $value);
	}

	/**
	 * Return all attribute or the scalar / vector attributes only
	 * @param  string $type optional scalar|vector
	 * @return array
	 */
	public function getAttributes($type=null) {
		return empty($type) ? $this->attributes : $this->attributes[$type];
	}

	/**
	 * Convenience function to get Scalar attributes
	 * @return array
	 */
	public function getScalarAttributes() {
		return $this->attributes['scalar'];
	}

	/**
	 * Convenience function to get Vector attributes
	 * @return array
	 */
	public function getVectorAttributes() {
		return $this->attributes['vector'];
	}

	/**
	 * Fills in missing named attributes with the value specified - is use to normalise the attributes sets so that calculations that require even attribute sets can run without bothering you
	 * @param  string $type    The attribute set to fill scalar|vector
	 * @param  mixed $missing The value to fill missing attributes with
	 * @return Comparator
	 */
	public function fillMissingAttributes($type, $missing) {
		$this->attributes[$type] = array_merge($missing,$this->attributes[$type]);
		if ($type=="scalar") {
			ksort($this->attributes[$type]);
		}
		return $this;
	}

	/**
	 * Checks that all attributes declared as scalar pass is_scalar
	 * @return boolean
	 * @throws \LogicException If any attribute declared scalar is not a scalar type
	 */
	public function validateScalarAttributes() {
		$invalid = array_filter($this->attributes['scalar'], function($item) {
			return !is_scalar($item);
		});
		if (!empty($invalid)) throw new \LogicException("The scalar attributes contain non-scalar values");
		return true;
	}

}
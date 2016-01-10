<?php

namespace WeAreNotMachines\Similarity;

class Comparator {
	
	private $attributes = ["binary"=>[], "vector"=>[]];

	public function __construct($attributes=null) {
		$this->initialize($attributes);
	}

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

	public function addAttribute($type, $key, $value) {
		$this->attributes[$type][$key] = $value;
		return $this;
	}

	public function addScalarAttribute($key, $value) {
		$this->addAttribute("scalar", $jey, $value);
		ksort($this->attributes['scalar']);
		return $this;
	}

	public function addVectorAttribute($key, $value) {
		return $this->addAttribute("vector", $key, $value);
	}

	public function getAttributes($type=null) {
		return empty($type) ? $this->attributes : $this->attributes[$type];
	}

	public function getScalarAttributes() {
		return $this->attributes['scalar'];
	}

	public function getVectorAttributes() {
		return $this->attributes['vector'];
	}

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
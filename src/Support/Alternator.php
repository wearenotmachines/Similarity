<?php
namespace WeAreNotMachines\Similarity\Support;

class Alternator {
	
	private $dataset;	
	private $alternates = [];

	public function __construct(array $dataset) {
		$this->dataset = $dataset;
	}

	public function getAlternates() {
		if (!empty($this->alternates)) return $this->alternates;
		foreach ($this->dataset AS $offset=>$value) {
			$this->alternates[$value] = array_values(array_diff($this->dataset, [$value]));
		}
		return $this->alternates;
	}

	public function getSet() {
		return $this->dataset;
	}
}
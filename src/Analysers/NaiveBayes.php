<?php

namespace WeAreNotMachines\Similarity\Analysers;

use WeAreNotMachines\Similarity\Support\FeatureSetAggregator;

class NaiveBayes {

	private $aggregator;

	public function __construct($dataset = null) {
		$this->aggregator = new FeatureSetAggregator($dataset);
	}
	

	public function train($classification, array $features) {
		foreach ($features AS $feature) {
			$this->aggregator->incrementFeature($classification, $feature);
		}
		$this->aggregator->incrementClassification($classification);
		return $this;
	}

	public function trainFromArray(array $array) {
		$key = key($array);
		$values = $array;
		return $this->train($key, $values[$key]);
	}

	public function getAggregatorData() {
		return $this->aggregator->getData();
	}

	public function featureProbability($feature, $classification) {
		$featureCount = $this->aggregator->featureCount($classification, $feature);
		$catCount = $this->aggregator->countFeatures($classification);
		if ($catCount==0) return 0;
		return $featureCount/$catCount;
	}

}
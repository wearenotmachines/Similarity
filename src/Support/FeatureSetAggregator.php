<?php

namespace WeAreNotMachines\Similarity\Support;

class FeatureSetAggregator {
		
		private $classifications = [];
		private $features = [];

		public function __construct($data=null) {
			if (!empty($data) && !isset($data['features'])) throw new \RuntimeException("FeatureSetAggregator must be initialised with a dataset containing an array of features - this can be empty but must be present");
			if (!empty($data) && !isset($data['classifications'])) throw new \RuntimeException("FeatureSetAggregator must be initialised with a dataset containing an array of classifications - this can be empty but must be present");
			if (empty($data)) $data = ["classifications" =>[],"features" =>[]];
			$this->classifications = $data['classifications'];
			$this->features = $data['features'];
		}

		public function feature($classification, $label, $count=0) {
			$this->features[$classification][$label] = $count;
			return [$label => $this->features[$classification][$label]];
		}

		public function incrementFeature($classification, $label) {
			if (!isset($this->features[$classification][$label])) $this->features[$classification][$label] = 0;
			$this->features[$classification][$label]++;
			return [$label => $this->features[$classification][$label]];
		}

		public function classification($label, $count=0) {
			$this->classifications[$label] = $count;
			return [$label => $this->classifications[$label]];
		}

		public function incrementClassification($label) {
			if (!isset($this->classifications[$label])) $this->classifications[$label] = 0;
			$this->classifications[$label]++;
			return [$label => $this->classifications[$label]];
		}

		public function getFeature($classification, $label) {
			if (!isset($this->features[$classification][$label])) return [$label=>0];
			return [$label => $this->features[$classification][$label]];
		}

		public function getClassification($label) {
			if (!isset($this->classifications[$label])) throw new \RuntimeException("There is no $label classification");
			return [$label => $this->classifications[$label]];
		}

		public function getData() {
			return [
				"classifications" => $this->classifications,
				"features" => $this->features
			];
		}

		public function countFeatures($classification) {
			return !empty($this->features[$classification]) ? count($this->features[$classification]) : 0;
		}

		public function getClassifications() {
			return array_keys($this->classifications);
		}

		public function itemCount() {
			return array_sum($this->classifications);
		}

		public function featureCount($classification, $label) {
			return current($this->getFeature($classification, $label));
		}


}
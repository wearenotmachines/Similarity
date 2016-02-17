<?php

namespace WeAreNotMachines\Similarity\Support;

class CsvDatasetParser {
		
	public $path;
	public $handle;
	public $data;
	public $header;
	public $hasHeader;

	public function __construct($path, $hasHeader=true) {
		$this->path = $path;
		$this->handle = fopen($path, "r");
		$this->hasHeader = $hasHeader;
	}

	public function readLine() {
		$this->data = fgetcsv($this->handle);
		return $this->data;
	}

	public function readAll() {
		rewind($this->handle);
		$this->data = [];
		while (($row = fgetcsv($this->handle, 1000, ","))!==false) {
			$this->data[] = $row;
		}
		if ($this->hasHeader) {
			$this->header = $this->data[0];
			$this->data = array_slice($this->data, 1);
		}
		return $this;
	}

	public function rewind() {
		rewind($this->handle);
	}

	public function getNestedDataset($indexColumnOffset=0, $limit=null) {
		$data = [];
		foreach ($this->data AS $index=>$row) {
			if (!empty($limit) && $index>=$limit) break;
			$data[] = [$row[$indexColumnOffset]=>array_filter($row, function($key) use ($indexColumnOffset) {
				return $key != $indexColumnOffset;
			}, ARRAY_FILTER_USE_KEY)];
		}
		return count($data) > 1 ? $data : current($data);
	}
}
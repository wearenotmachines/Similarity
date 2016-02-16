<?php

namespace WeAreNotMachines\Similarity\Persistence;

class JsonStorage implements Persistable, Incrementable {
	
	private $path;
	private $data;
	private $fileHandle;

	public function __construct($path=null) {
		if (!empty($path))  {
			$this->path = $path;
			$this->connect(array("path"=>$path));
		}
	}

	public function connect(array $config) {
		if (empty($config['path'])) throw new \InvalidArgumentException("Connection config array must contain a path attribute");
		$this->fileHandle = fopen($config['path'], "c+");
		if (empty($this->fileHandle)) throw new \RuntimeException("Cannot open or create path for JSON storage at ".$this->path);
	}

	public function load($what=null) {
		if (empty($this->fileHandle)) throw new \RuntimeException("JsonStorage path is not available yet");
		if (!empty($this->data)) {
			return empty($what) ? $this->data : $this->data->$what;
		}
		$this->data = json_decode(fread($this->fileHandle, filesize($this->path)), true);
		if (json_last_error()>JSON_ERROR_NONE) throw new \RuntimeException("JSON error while loading data: ".json_last_error_msg());
		return empty($what) ? $this->data : $this->data->$what;
	}

	public function save($what=null, $where=null) {
		if (!empty($what) && !empty($where)) {
			$this->data->$where = $what;
		} else if (!empty($what)) {
			$this->data = $what;
		}
		return fwrite($this->fileHandle, json_encode($this->data, JSON_PRETTY_PRINT));
	}

	public function increment($what) {
		$this->data->$what++;
	}
}
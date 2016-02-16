<?php
namespace WeAreNotMachines\Similarity\Generators;

use \ArrayAccess;
use \Iterator;

class ColourSetGenerator implements Generatable, ArrayAccess, Iterator {
	
	public $colours = ["white", "black", "red", "blue", "yellow", "green", "orange", "pink", "turquoise", "violet", "scarlet", "gold", "silver", "brown", "indigo", "grey", "purple", "mauve", "lilac", "teal", "russet", "taupe"];
	public $position;


	public function generateSet($size=8) {
		shuffle($this->colours);
		return array_slice($this->colours, 0, $size);
	}

	public function generate() {
		return $this->generateSet();
	}

	public function offsetSet($index, $value) {
		$this->colours[$index] = $value;
	}

	public function offsetGet($index) {
		return $this->offsetExists($index) ? $this->colours[$index] : null;
	}

	public function offsetExists($index) {
		return !empty($this->colours[$index]);
	}

	public function offsetUnset($index) {
		unset($this->colours[$index]);
	}

	public function current() {
		return $this->colours[$this->position];
	}

	public function next() {
		$this->position++;
		return $this->colours[$this->position];
	}

	public function rewind() {
		$this->position = 0;
	}

	public function valid() {
		return isset($this->colours[$this->position]);
	}

	public function key() {
		return $this->position;
	}

}
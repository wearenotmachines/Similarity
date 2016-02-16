<?php
namespace WeAreNotMachines\Similarity\Generators;

interface Generatable {
	
	public function generate();
	public function generateSet($size);
}
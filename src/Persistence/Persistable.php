<?php

namespace WeAreNotMachines\Similarity\Persistence;

interface Persistable {
		
		public function connect(array $config);
		public function load($what=null);
		public function save($what=null, $where=null);

}
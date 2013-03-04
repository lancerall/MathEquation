<?php


class Criteria
{
	// With comments!
	public $target;
	public $varset;
	
	public function Criteria($target, $varset){
		$this->target = $target;
		$this->varset = $varset;
	}
	
	public function toString(){
		print "Target:\n";
		print_r($this->target);
		
		print "Vars:\n";
		print_r($this->varset);
	}
}


?>
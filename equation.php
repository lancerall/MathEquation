<?php

class Equation
{
	public $equation = '';
	public $score = '';
	public $index = 0;

	public function Equation($equation,$score,$index){
		$this->equation = $equation;
		$this->score = $score;
	}
	
	public function evaluateFit($criteria){
		for($z=0;$z<count($criteria->varset);$z++){			
			$result = $this->calculateResult($this->equation, $criteria->varset[$z]);
			$myScore = abs($result - $criteria->target[$z]);
			$this->score += $myScore;
			//print "Criteria set [$z] Received result of $result, target of ".$criteria->target[$z]." results in a score of $myScore"."\n";
		}
		//print "overall score : $this->score\n\n";
	}

	private function calculateResult($equation,$vars){		
		$alphabet = range('a','z');
		unset($alphabet[4]);
		$alphabet = array_values($alphabet);

		$m = new EvalMath;
		
		foreach($vars as $k=>$v){
			$m->evaluate($alphabet[$k].' = '.$v);
			//print "eval : ".$alphabet[$k].' = '.$v."\n";
		}
		$result = $m->evaluate($equation);
	
		return $result;
	}
	
	public function toString(){
		print "Equation : ".$this->equation."\n";
		print "Score : ".$this->score."\n";
		print "Index : ".$this->index."\n";
	}
	
	public function commit(){
	
		GLOBAL $CONFIG_store_results_db;
		GLOBAL $CONFIG_echo_all_queries;
	
		IF ($CONFIG_store_results_db){
			$query = "insert into equations (equation,resulting,score) values ('$this->equation','$this->index',$this->score) ;";
			if ($CONFIG_echo_all_queries) echo $query."\n";
			mysql_query($query);
		}

	}
	
}



?>
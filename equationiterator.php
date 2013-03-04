<?php

class EquationIterator
{	
	public $equation = null;
	
	private $min_equation_length = 11; // minimum number of chars long, i.e. a+b+c+d = 7
	
	private $index = 0; // count of rows

	public $indexA = 0; //$CONFIG_initial_indexA;
	private $indexB = 0; //$CONFIG_initial_indexB;
		
	private $maxB = 0; // $maxB=pow($dict_lenB,4)+1000; // chars ^ digits
	
	private $convertedA = 0;
	private $newindexA = 0;
	
	private $convertedB = 0;
	private $newindexB = 0;
	
	private $dictionaryA = 0; //$CONFIG_var_dictionary;
	private $dictionaryB = 0; //$CONFIG_op_dictionary;

	private $dict_lenA = 0; //strlen($dictionaryA);
	private $dict_lenB = 0; //strlen($dictionaryB);

	private $freq_equation_rate_echo_index = 0;
	
	public function EquationIterator($initial_indexA, $initial_indexB, $var_dictionary, $op_dictionary){
		$this->indexA = $initial_indexA;
		$this->indexB = $initial_indexB;
				
		$this->dictionaryA = $var_dictionary;
		$this->dictionaryB = $op_dictionary;
		
		$this->dict_lenA = strlen($this->dictionaryA);
		$this->dict_lenB = strlen($this->dictionaryB);		
		
		//$this->maxB = pow($this->dict_lenB,4)+1000; // chars ^ digits // TODO : 4 and 1000 are hardcoded
		$this->maxB = base_convert(33333,4,10);
		
		//print "Max B is $this->maxB \n";
	}
	
	public function show_equation_rate_check(){
		GLOBAL $CONFIG_echo_equation_rate;
		GLOBAL $CONFIG_freq_equation_rate_echo;
		GLOBAL $CONFIG_calc_time_unit;
		GLOBAL $CONFIG_echo_time_elapsed;
		GLOBAL $CONFIG_echo_num_calculations;
		GLOBAL $CONFIG_echo_calc_rate;
		GLOBAL $CONFIG_calc_time_unit_label;
		GLOBAL $time_start;
		
		/*
		print $CONFIG_echo_equation_rate."\n";
		print $this->index."\n";
		print $this->freq_equation_rate_echo_index."\n";
		print $CONFIG_freq_equation_rate_echo."\n\n";
		*/
		
		if ($CONFIG_echo_equation_rate && $this->index>0 && $this->freq_equation_rate_echo_index % $CONFIG_freq_equation_rate_echo ==0) 
		{
			$time_current = microtime(true);
			$time_elapsed = round($time_current - $time_start, 1);
			$equations_calculated = $this->index;
			$equations_per_time = round($equations_calculated / ($time_elapsed / $CONFIG_calc_time_unit),0);
			if ($CONFIG_echo_time_elapsed) print "Time elapsed : $time_elapsed \n";
			if ($CONFIG_echo_num_calculations) print "Calculations : $equations_calculated \n";
			if ($CONFIG_echo_calc_rate) print "Equations per $CONFIG_calc_time_unit_label : $equations_per_time \n";
			$this->freq_equation_rate_echo_index = 0;
		}
		else {}
		
	}
	

	public function next(){
		GLOBAL $CONFIG_max_digitsA;
		GLOBAL $CONFIG_max_digitsB;
		GLOBAL $CONFIG_echo_indexes;
		
		//print "\n";
		
		$this->indexB++;

		//print "Check : $this->indexB / $this->maxB \n";
		if($this->indexB > $this->maxB){ 
			//print "resetting \n";
			//$this->indexB = base_convert(10000,4,10);
			$this->indexB=0;
			$this->indexA++;
		}
		
		$this->freq_equation_rate_echo_index++;
		$this->index++;
		
		$this->convertedA = base_convert($this->indexA,10,$this->dict_lenA);
		$this->newindexA = base_convert($this->convertedA,$this->dict_lenA,10);
		$this->convertedB = base_convert($this->indexB,10,$this->dict_lenB);
		$this->newindexB = base_convert($this->convertedB,$this->dict_lenB,10);
		
		$this->convertedA = str_pad($this->convertedA, $CONFIG_max_digitsA, "0", STR_PAD_LEFT);
		$this->convertedB = str_pad($this->convertedB, $CONFIG_max_digitsB, "0", STR_PAD_LEFT);
		
		
		if ($CONFIG_echo_indexes) print "A[$this->indexA] $this->convertedA \t";
		 //print "NewIndexA: $this->newindexA \n";
		if ($CONFIG_echo_indexes) print "B[$this->indexB] : $this->convertedB \n";
		 //print "NewIndex: $this->newindexB \n";
		 
		if ($this->indexA > 46655) return false;
		else return new Equation($this->buildEquation(), 0);
	}
	
	private function buildEquation(){
		//print "Building equation...\n";
		
		$equation = '';
		$z=0; // index of vars
		$y=0; // index of ops

		while (true){
			$equation .= $this->dictionaryA[base_convert($this->convertedA[$z],$this->dict_lenA,10)];	
			$equation .= $this->dictionaryB[base_convert($this->convertedB[$y],$this->dict_lenB,10)];	
			//print "built $equation\n";

			$z++;
			$y++;
			
			#print "y=$y z=$z to $convertedB\n";
			#sleep(3);
			
			if ($y == strlen($this->convertedB)) {
				$equation .= $this->dictionaryA[base_convert($this->convertedA[$z],$this->dict_lenA,10)];	// Add last var
				
				/*
				while (strlen($equation) <= $this->min_equation_length -1) {
					$equation .=  $this->dictionaryB[0] . $this->dictionaryA[0];
				}
				*/
				
				//print "Breaking, equation is now $equation\n";

				break;
			}
			
		}
		//print "Returning $equation\n";
		return $equation;
	}

}


	public function toString(){
		print "Index : $this->index \n";
		print "IndexA : $this->indexA \n";
		print "IndexB : $this->indexB \n";
		print "Var dict : \t";
		print_r($this->dictionaryA);
		print "\n";
		print "Op dict :\t";
		print_r($this->dictionaryB);
		print "\n";

		print "Max B : $this->maxB \n";
		
		print "ConvertedA : $convertedA \n";
		print "NewIndexA : $newindexA \n";
	
		print "ConvertedB : $convertedB \n";
		print "NewIndexB : $newindexB \n";
	}


?>
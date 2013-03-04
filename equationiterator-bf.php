<?php

class EquationIterator
{	
	public $equation = null;
	//private $min_equation_length = 11; // minimum number of chars long, i.e. a+b+c+d = 7
	private $index = 0; // count of rows
	public $indexA = 0; //$CONFIG_initial_indexA;
	private $convertedA = 0;
	private $newindexA = 0;
	private $dictionaryA = 0; //$CONFIG_var_dictionary;
	private $dict_lenA = 0; //strlen($dictionaryA);
	private $freq_equation_rate_echo_index = 0;
	
	public function EquationIterator($initial_indexA, $dictionary){
		$this->indexA = $initial_indexA;
		$this->dictionaryA = $dictionary;
		$this->dict_lenA = strlen($this->dictionaryA);
	}
	
	public function show_equation_rate_check(){
		GLOBAL $CONFIG_echo_equation_rate;
		GLOBAL $CONFIG_freq_equation_rate_echo;
		GLOBAL $CONFIG_store_performance_data;
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
			
			if ($CONFIG_store_performance_data){
				$query = 'insert into performance (recordedTime, numRecords, hostName) values ("'.strftime('%Y-%m-%d %H:%M:%S').'",'.$equations_per_time.',"'.system('echo $HOSTNAME').'");';
				mysql_query($query);
			}
			$this->freq_equation_rate_echo_index = 0;
		}
		else {}
		
	}
	

	public function next(){
		GLOBAL $CONFIG_echo_indexes;
		GLOBAL $CONFIG_max_index_bf;
		
		//print "\n";
		$this->indexA++;
		$this->index++;
		
		$this->freq_equation_rate_echo_index++;
		
		$this->convertedA = base_convert($this->indexA,10,$this->dict_lenA);
		$this->newindexA = base_convert($this->convertedA,$this->dict_lenA,10);
		$this->convertedA = $this->convertedA;
		
		if ($CONFIG_echo_indexes) print "A[$this->indexA] $this->convertedA \t";
		 //print "NewIndexA: $this->newindexA \n";
		 
		if ($this->indexA > $CONFIG_max_index_bf) return false;
		else return new Equation($this->buildEquation(), 0, 0);
	}
	
	private function buildEquation(){
		//print "\n\nBuilding equation...\n";
		
		$equation = '';
		$convertedAlength = strlen($this->convertedA);
		$z=0; // index of vars

		while ($z < $convertedAlength){
			$equation .= $this->dictionaryA[base_convert($this->convertedA[$z],$this->dict_lenA,10)];	
			//print "building... $equation\t [$z] \t $convertedAlength\n";
			$z++;
		}
		//print "Returning $equation\n";
		return $equation;
	}




	public function toString(){
		print "Index : $this->index \n";
		print "IndexA : $this->indexA \n";
		print "\n";
		print "Dict :\t";
		print_r($this->dictionaryA);
		print "\n";

		print "ConvertedA : $convertedA \n";
		print "NewIndexA : $newindexA \n";
	}

}

?>
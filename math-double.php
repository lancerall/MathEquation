<?php
$time_start = microtime(true);
print "Starting $time_start...\n";

include('config.php');
include('mathclass.php');

$conn = mysql_connect($CONFIG_dbaddress, $CONFIG_dbuser, $CONFIG_dbpass);
if (!$conn) die("Not connected");
else print "DB connected.\n";

$db = mysql_select_db($CONFIG_dbname);
if (!$conn) die("No db selected");
else print "DB selected.\n";

IF ($CONFIG_truncate_at_start) {
	print "Truncating data...\n";
	mysql_query("truncate table equations;");
}

print "Starting search...\n";

$dictionaryA = $CONFIG_var_dictionary;
$dictionaryB = $CONFIG_op_dictionary;

$dict_lenA = strlen($dictionaryA);
$dict_lenB = strlen($dictionaryB);

$target1 = $CONFIG_target1;
$target2 = $CONFIG_target2;
$minscore = $CONFIG_minscore;

if ($CONFIG_echo_total_search_perms) {
	//print "dict len $dict_len, max eq ele $CONFIG_max_equation_elements, initial ind $CONFIG_initial_index\n";
	//$total_search_perms = ($dict_lenA^$CONFIG_max_equation_elements) - $CONFIG_initial_index;
	//echo "Total search permutations: $total_search_perms\n";
}

$maxB=pow($dict_lenB,4)+1000; // chars ^ digits
echo "$dict_lenB \n";

echo "MaxB $maxB \n";

$index = 0; // count of rows
for ($indexA=$CONFIG_initial_indexA;$indexA<pow(($dict_lenA+$dict_lenB),$CONFIG_max_equation_elements);$indexA++){
	#print "starting a\n";
	for ($indexB=$CONFIG_initial_indexB;$indexB<$maxB;$indexB++){
	#print "starting b\n";
	#print "IndexA $indexA \n";
	$freq_equation_rate_echo_index++;
	$convertedA = base_convert($indexA,10,$dict_lenA);
	#print "Base $dict_lenA: $convertedA \n";
	$newindexA = base_convert($convertedA,$dict_lenA,10);
	#print "NewIndex: $newindexA \n";
	
	#print "IndexB $indexB \n";
	$convertedB = base_convert($indexB,10,$dict_lenB);
	#print "Base $dict_lenB: $convertedB \n";
	$newindexB = base_convert($convertedB,$dict_lenB,10);
	#print "NewIndex: $newindexB \n";
	
	$equation = "";
	$score = 0;
	$result = 0;
	
	$z=0;
	$y=0;
	
	while (true){
		$equation .= $dictionaryA[base_convert($convertedA[$z],$dict_lenA,10)];	
		$equation .= $dictionaryB[base_convert($convertedB[$y],$dict_lenB,10)];	
		#print "built $equation\n";
		
		$z++;
		$y++;
		$leng = strlen($convertedB);
		
		#print "y=$y z=$z to $convertedB\n";
		#sleep(3);
		if ($y == strlen($convertedB)) {
			$equation .= $dictionaryA[base_convert($convertedA[$z],$dict_lenA,10)];	// Add last var
			#print "Breaking, equation is now $equation\n";
			
			break;
		}
	}
		
	$m = new EvalMath;
	$m->evaluate('a = '.$CONFIG_a1); //Hero Attack
	$m->evaluate('b = '.$CONFIG_b1); //Hero Units
	$m->evaluate('c = '.$CONFIG_c1); // Unit Attack
	$m->evaluate('d = '.$CONFIG_d1); // Enemy Vitality
	$m->evaluate('f = '.$CONFIG_f1); // Enemy Defense
	$m->evaluate('g = '.$CONFIG_g1); // Enemy Army Size	
	$result = $m->evaluate($equation);
	
	$m2 = new EvalMath;
	$m2->evaluate('a = '.$CONFIG_a2); //Hero Attack
	$m2->evaluate('b = '.$CONFIG_b2); //Hero Units
	$m2->evaluate('c = '.$CONFIG_c2); // Unit Attack
	$m2->evaluate('d = '.$CONFIG_d2); // Enemy Vitality
	$m2->evaluate('f = '.$CONFIG_f2); // Enemy Defense
	$m2->evaluate('g = '.$CONFIG_g2); // Enemy Army Size	
	$result2 = $m2->evaluate($equation);
	
	$index++; // increase count of calc'd rows
	
	if ($result)
	{
		if ($CONFIG_echo_valid_equations) print "$indexA $indexB ($convertedA $convertedB) :\t$equation = $result \n";
		$score = abs($result - $target1);
		#print "Score1 $score ";
		$score += abs($result2 - $target2);
		#print "Score total $score\n";
				
		if ($CONFIG_echo_all_scores) echo $score." is score\n";
		if ($score <= $minscore || $score < $CONFIG_minimum_score_floor)
		{
			$minscore = $score;
			$minscoreequation = $equation;
			if ($CONFIG_echo_new_best_fit) print "Min score ($minscore) for equation: $equation \n";

			IF ($CONFIG_store_results_db){
				$query = "insert into equations (equation,resulting,score) values ('$equation','$result $result2',$score) ;";
				if ($CONFIG_echo_all_queries) echo $query."\n";
				mysql_query($query);
			}
		}
	}	
	else if ($CONFIG_echo_all_equations) print "$index ($converted) : $equation \t = $result \n";

	if ($CONFIG_echo_best_score_periodic && $index % $CONFIG_freq_best_score_echo==0) print "Min score ($minscore) for equation: $equation \n";
	if ($CONFIG_echo_equation_rate && $index>0 && $freq_equation_rate_echo_index % $CONFIG_freq_equation_rate_echo ==0) 
	{
		$time_current = microtime(true);
		$time_elapsed = round($time_current - $time_start, 1);
		$equations_calculated = $index;
		$equations_per_time = round($equations_calculated / ($time_elapsed / $CONFIG_calc_time_unit),0);
		if ($CONFIG_echo_time_elapsed) print "Time elapsed : $time_elapsed \n";
		if ($CONFIG_echo_num_calculations) print "Calculations : $equations_calculated \n";
		if ($CONFIG_echo_calc_rate) print "Equations per $CONFIG_calc_time_unit_label : $equations_per_time \n";
		$freq_equation_rate_echo_index = 0;
	}
	else {}
	sleep($CONFIG_sleep_per_equation);
	usleep($CONFIG_microsleep_per_equation);

	
	}	// end "for indexB"
	
}	// end "for indexA"

?>
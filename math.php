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

$dictionary=$CONFIG_dictionary;
$dict_len=strlen($dictionary);
$target = $CONFIG_target;
$minscore = $CONFIG_minscore;

if ($CONFIG_echo_total_search_perms) {
	print "dict len $dict_len, max eq ele $CONFIG_max_equation_elements, initial ind $CONFIG_initial_index\n";
	$total_search_perms = ($dict_len^$CONFIG_max_equation_elements) - $CONFIG_initial_index;
	echo "Total search permutations: $total_search_perms\n";
}

for ($index=$CONFIG_initial_index;$index<$dict_len^$CONFIG_max_equation_elements;$index++){
	#print "Index $index \n";
	$freq_equation_rate_echo_index++;
	$converted = base_convert($index,10,$dict_len);
	#print "Base $dict_len: $converted \n";
	$newindex = base_convert($converted,$dict_len,10);
	#print "NewIndex: $newindex \n";
	
	$equation = "";
	$score = 0;
	$result = 0;
	
	for ($z=0;$z<strlen($converted);$z++){
		$equation .= $dictionary[base_convert($converted[$z],$dict_len,10)];				
	}
	
	$m = new EvalMath;
	$m->evaluate('a = '.$CONFIG_a); //Hero Attack
	$m->evaluate('b = '.$CONFIG_b); //Hero Units
	$m->evaluate('c = '.$CONFIG_c); // Unit Attack
	$m->evaluate('d = '.$CONFIG_d); // Enemy Vitality
	$m->evaluate('f = '.$CONFIG_f); // Enemy Defense
	$m->evaluate('g = '.$CONFIG_g); // Enemy Army Size	
	$result = $m->evaluate($equation);
	
	if ($result)
	{
		if ($CONFIG_echo_valid_equations) print "$index ($converted) : $equation = $result \n";
		$score = abs($result - $target);
		if ($CONFIG_echo_all_scores) echo $score." is score\n";
		if ($score <= $minscore || $score < $CONFIG_minimum_score_floor)
		{
			$minscore = $score;
			$minscoreequation = $equation;
			if ($CONFIG_echo_new_best_fit) print "Min score ($minscore) for equation: $equation \n";

			IF ($CONFIG_store_results_db){
				$query = "insert into equations (equation,resulting,score) values ('$equation',$result,$score) ;";
				mysql_query($query);
			}
		}
	}	
	else if ($CONFIG_echo_all_equations) print "$index ($converted) : $equation = $result \n";

	if ($CONFIG_echo_best_score_periodic && $index%$CONFIG_freq_best_score_echo==0) print "Min score ($minscore) for equation: $equation \n";
	if ($CONFIG_echo_equation_rate && $index - $CONFIG_initial_index > 0 && $freq_equation_rate_echo_index % $CONFIG_freq_equation_rate_echo ==0) 
	{
		$time_current = microtime(true);
		$time_elapsed = round($time_current - $time_start, 1);
		$equations_calculated = $index - $CONFIG_initial_index;
		$equations_per_time = round($equations_calculated / ($time_elapsed / $CONFIG_calc_time_unit),0);
		if ($CONFIG_echo_time_elapsed) print "Time elapsed : $time_elapsed \n";
		if ($CONFIG_echo_num_calculations) print "Calculations : $equations_calculated \n";
		if ($CONFIG_echo_calc_rate) print "Equations per $CONFIG_calc_time_unit_label : $equations_per_time \n";
		$freq_equation_rate_echo_index = 0;
	}
	else {}
	sleep($CONFIG_sleep_per_equation);

}

?>
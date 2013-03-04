<?php
require('config.php');
require('mathclass.php');
require('equation.php');
require('criteria.php');
require('equationiterator-bf.php');


$time_start = microtime(true);
$time_start_str = strftime("%Y-%m-%d %H-%M-%S");
print "Starting $time_start...\n";

connect_to_db();

IF ($CONFIG_truncate_at_start) truncate_at_start();
if ($CONFIG_echo_total_search_perms) echo_total_search_perms();

print "Starting search...\n";

$minscore = $CONFIG_minscore;

$bestEquation = new Equation("",$CONFIG_minscore,0);

$myCriteria = new Criteria($CONFIG_target_set,$CONFIG_equation_variables_set);
$eqi = new EquationIterator($CONFIG_initial_indexA, $CONFIG_dictionary);

while ($eq = $eqi->next()){
	$eq->evaluateFit($myCriteria);
	if ($CONFIG_echo_all_equations) print "$eq->equation = $eq->score \n";

	if ($eq->score < $bestEquation->score || $eq->score <= $CONFIG_minimum_score_floor)
	{
	/*
		print_r($eq);
		print "\n\n";
		print_r($bestEquation);
		print "\n\n";
		sleep(3);
	*/
		$bestEquation = new Equation($eq->equation,$eq->score, $eqi->indexA);
		IF ($CONFIG_echo_new_best_fit){
			print "\n\nbest new eq:\n";
			$eq->toString();
		}
		if ($CONFIG_store_results_db) $eq->commit();
	}
	

	//show_best_score($index, $minscore, $equation);
	$eqi->show_equation_rate_check();
	pause_execution();
}
	
print "\n\n";
print $time_start_str."\n";
print strftime("%Y-%m-%d %H-%M-%S")."\n";

$bestEquation->toString();
$eqi->toString();
		
	

	

function connect_to_db(){
GLOBAL $CONFIG_dbaddress;
GLOBAL $CONFIG_dbuser;
GLOBAL $CONFIG_dbpass;
GLOBAL $CONFIG_dbname;

$conn = mysql_connect($CONFIG_dbaddress, $CONFIG_dbuser, $CONFIG_dbpass);
if (!$conn) die("Not connected");
else print "DB connected.\n";

$db = mysql_select_db($CONFIG_dbname);
if (!$conn) die("No db selected");
else print "DB selected.\n";

}

function truncate_at_start(){
	print "Truncating data...\n";
	mysql_query("truncate table equations;");
}

function echo_total_search_perms(){
	//print "dict len $dict_len, max eq ele $CONFIG_max_equation_elements, initial ind $CONFIG_initial_index\n";
	//$total_search_perms = ($dict_lenA^$CONFIG_max_equation_elements) - $CONFIG_initial_index;
	//echo "Total search permutations: $total_search_perms\n";
}

function show_best_score($index, $minscore, $equation){
	GLOBAL $CONFIG_echo_best_score_periodic;
	GLOBAL $CONFIG_freq_best_score_echo;
	
	if ($CONFIG_echo_best_score_periodic && $index % $CONFIG_freq_best_score_echo==0) print "Min score ($minscore) for equation: $equation \n";
}

function pause_execution(){
	GLOBAL $CONFIG_sleep_per_equation;
	GLOBAL $CONFIG_microsleep_per_equation;
	
	sleep($CONFIG_sleep_per_equation);
	usleep($CONFIG_microsleep_per_equation);
}

function evaluate_equation($equation, $vars){
	$m = new EvalMath;
	$m->evaluate('a = '.$vars[0]); //Hero Attack
	$m->evaluate('b = '.$vars[1]); //Hero Units
	$m->evaluate('c = '.$vars[2]); // Unit Attack
	$m->evaluate('d = '.$vars[3]); // Enemy Vitality
	$m->evaluate('f = '.$vars[4]); // Enemy Defense
	$m->evaluate('g = '.$vars[5]); // Enemy Army Size	
	$result = $m->evaluate($equation);
	return $result;
}

function echo_score($score){
	GLOBAL $CONFIG_echo_all_scores;

	if ($CONFIG_echo_all_scores) echo $score." is score\n";
}

?>
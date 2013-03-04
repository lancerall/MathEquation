<?php
error_reporting(0);
date_default_timezone_set('America/Chicago');

// Math equation configs

$CONFIG_dbaddress = "127.0.0.1";
$CONFIG_dbuser = "root";
$CONFIG_dbpass = "silicon";
$CONFIG_dbname = "math";
$CONFIG_truncate_at_start = false;

$CONFIG_dictionary='2abcdfg+-*/^()'; 	// ()^12
$CONFIG_var_dictionary='abcdfg';
$CONFIG_op_dictionary='+-*/'; 

$CONFIG_minscore = 1000000; 		// minimum score initial value
$CONFIG_initial_index = 0;	// start with x permutation of combos
$CONFIG_initial_indexA = 444000000000;//base_convert(17273747570,14,10) /*9331*/;	
$CONFIG_initial_indexB = 0; // base_convert(33332,4,10);	

$CONFIG_echo_indexes = true;

$CONFIG_max_digitsA = 6;
$CONFIG_max_digitsB = 5;

$CONFIG_max_index_bf = 1000000000000; // max index for "brute force" iterator program

#print base_convert(111111,6,10)."\n";
$CONFIG_max_equation_elements = 13;	// max number of pieces of the equation

$CONFIG_sleep_per_equation = 0;		// number of seconds to sleep after each equation
$CONFIG_microsleep_per_equation = 0;		// number of microseconds to sleep after each equation

$CONFIG_echo_new_best_fit = false;	
$CONFIG_minimum_score_floor = 10;	// write all records better than this fit to db

$CONFIG_echo_valid_equations = true;
$CONFIG_echo_all_equations = true;
$CONFIG_echo_all_scores = false;
$CONFIG_store_results_db = true;

/*
$CONFIG_target = 664; 				// target value
$CONFIG_target1 = 664; 				// target value
$CONFIG_target2 = 1091; 				// target value
*/

/***********************************************
COG 
************************************************/
$CONFIG_target_set = array(664, 1091);

$CONFIG_a1 = 1407;
$CONFIG_b1 = 705;
$CONFIG_c1 = 257;
$CONFIG_d1 = 280;
$CONFIG_f1 = 180;
$CONFIG_g1 = 1400;

$CONFIG_a2 = 1407;
$CONFIG_b2 = 705;
$CONFIG_c2 = 384;
$CONFIG_d2 = 280;
$CONFIG_f2 = 180;
$CONFIG_g2 = 1400;


/**************************************************
f*b+a/c+d-g
$CONFIG_target_set = array(71.5, 83.5, 3273);

$CONFIG_a1 = 7;
$CONFIG_b1 = 7;
$CONFIG_c1 = 14;
$CONFIG_d1 = 3;
$CONFIG_f1 = 10;
$CONFIG_g1 = 2;

$CONFIG_a2 = 15;
$CONFIG_b2 = 7;
$CONFIG_c2 = 10;
$CONFIG_d2 = 9;
$CONFIG_f2 = 12;
$CONFIG_g2 = 11;

$CONFIG_a3 = 18;
$CONFIG_b3 = 46;
$CONFIG_c3 = 9;
$CONFIG_d3 = 41;
$CONFIG_f3 = 72;
$CONFIG_g3 = 82;
**************************************************/


$CONFIG_equation_variables_set[0] = array($CONFIG_a1,$CONFIG_b1,$CONFIG_c1,$CONFIG_d1,$CONFIG_f1,$CONFIG_g1);
$CONFIG_equation_variables_set[1] = array($CONFIG_a2,$CONFIG_b2,$CONFIG_c2,$CONFIG_d2,$CONFIG_f2,$CONFIG_g2);
//$CONFIG_equation_variables_set[2] = array($CONFIG_a3,$CONFIG_b3,$CONFIG_c3,$CONFIG_d3,$CONFIG_f3,$CONFIG_g3);


$CONFIG_echo_total_search_perms = false;

$CONFIG_echo_best_score_periodic = false;
$CONFIG_freq_best_score_echo = 10;

$CONFIG_echo_all_queries = false;

$CONFIG_echo_equation_rate = true;
$CONFIG_store_performance_data = true;

	$CONFIG_freq_equation_rate_echo = 1000000;

	$CONFIG_echo_time_elapsed = false;
	$CONFIG_echo_num_calculations = false;
	$CONFIG_echo_calc_rate = true;
	$CONFIG_calc_time_unit = 60; 				// seconds divided by x (minute = 60, hour = 3600)
	$CONFIG_calc_time_unit_label = "minute"; 	// make sure this matches


?>
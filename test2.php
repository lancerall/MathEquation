<?php

$myvar = 10;
$targetdigits = 3;

print strlen($myvar)."\n";
print "target digits $targetdigits\n\n";

while (strlen($myvar) <= $targetdigits -1) {
	print $myvar."\n";
	$myvar++;
}
				
?>
#!/usr/bin/env php
Comparing different types of loops.
	- forWithVar: for using a variable as maximum value (2^24). Cheap.
	- forWithFunction: for using a function as maximum value (2^24). Expensive
	  and stupid.
	- whileWithVar: while loop that runs as long $i is not 2^24. Same as
	  forWithVar.
	- whileWithFunction: while loop that runs as long as $i is not pow(2, 24).
	  Expensive and stupid.
	- whileWithBreakVar: eternal while loop that breaks if $i is equal to
	  2^24, which is checked using if. Slower than forWithVar/whileWithVar.
	  whileWithBreakVar: eternal while loop that breaks if $i is equal to
	  pow(2, 24), which is checked using if. Slowest.

Results are as expected. I, however, prefer while/break in many cases, as it is
easier to expand if you need more complex conditions.
<?php
use plibv4\profiler\Profiler;
require_once __DIR__."/../vendor/autoload.php";

$max = pow(2, 24);
Profiler::startTimer("forWithVar");
for($i = 0;$i<$max;$i++) {
	$number = 2+6;
}
Profiler::endTimer("forWithVar");

Profiler::startTimer("forWithFunction");
for($i = 0;$i<pow(2, 24);$i++) {
	$number = 2+6;
}
Profiler::endTimer("forWithFunction");

$max = pow(2, 24);
Profiler::startTimer("whileWithVar");
$i = 0;
while($i<$max) {
	$number = 2+6;
	$i++;
}
Profiler::endTimer("whileWithVar");
$max = pow(2, 24);

Profiler::startTimer("whileWithFunction");
$i = 0;
while($i<pow(2, 24)) {
	$number = 2+6;
	$i++;
}
Profiler::endTimer("whileWithFunction");

Profiler::startTimer("whileBreakVar");
$i = 0;
while(true) {
	if($i===$max) {
		break;
	}
	$number = 2+6;
	$i++;
}
Profiler::endTimer("whileBreakVar");

Profiler::startTimer("whileBreakFunction");
$i = 0;
while(true) {
	if($i===pow(2, 24)) {
		break;
	}
	$number = 2+6;
	$i++;
}
Profiler::endTimer("whileBreakFunction");


Profiler::printTimers();
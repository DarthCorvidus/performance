#!/usr/bin/env php
Comparing the cost of...
 - calling a function (function)
 - calling a method on the same instance (methodSame)
 - creating a new function and calling a method (methodNew)

As expected, function is cheapest, methodNew most expensive. Difference between
function and method is negligible.
<?php
use plibv4\profiler\Profiler;
require_once __DIR__."/../vendor/autoload.php";

function add(int $int1, int $int2): int {
	return $int1 + $int2;
}

class Add {
	private $int1;
	function __construct(int $int1) {
		$this->int1 = $int1;
	}
	
	function add(int $int2) {
		return $this->int1 + $int2;
	}
}

$max = pow(2, 24);

Profiler::startTimer("function");
for($i = 0; $i<$max;$i++) {
	add(5, 3);
}
Profiler::endTimer("function");

Profiler::startTimer("methodSame");
$add1 = new Add(5);
for($i = 0; $i<$max;$i++) {
	$add1->add(3);
}
Profiler::endTimer("methodSame");

Profiler::startTimer("methodNew");
for($i = 0; $i<$max;$i++) {
	$add2 = new Add(5);
	$add2->add(3);
}
Profiler::endTimer("methodNew");


Profiler::printTimers();
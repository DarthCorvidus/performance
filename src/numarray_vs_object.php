#!/usr/bin/env php
I am currently pondering whether it would be feasible to create type safe arrays
start to end, including explode, implode, array_pop and so on. Now what would be
the performance impact of such an approach?
Depends upon:
	* With no real payload - just assigning an int - object is slower,
	  guesstimated at around 1:3 to 1:4.
	* When instantiating array/object every round, object is slower at about
	  1:5 to 1:6.
	* When having a high load added to the loop, the difference becomes
	  negligible.

Performance will be less of an issue in most use cases. A few unoptimized SQL
queries and the performance impact will be much worse.
However, as I have a long running use case in mind - server - the question
remains if memory leaks and so on could become a problem.
<?php
use plibv4\profiler\Profiler;
require_once __DIR__."/../vendor/autoload.php";
class ListInt {
	private array $array = array();
	function __construct() {
		;
	}
	
	public function addItem(int $int): void {
		$this->array[] = $int;
	}
	
	public function getItem(int $i): int  {
		if(!isset($this->array[$i])) {
			throw new \OutOfBoundsException("item ".(string)$i." not in ListInt");
		}
		return $this->array[$i];
	}
}


$max = pow(2, 24);



Profiler::startTimer("array");
$array = array();
for($i=0;$i<$max;$i++) {
	$array[] = $i;
	$array[$i];
}
Profiler::endTimer("array");

Profiler::startTimer("object");
$list = new ListInt();
for($i=0;$i<$max;$i++) {
	$list->addItem($i);
	$list->getItem($i);
}
Profiler::endTimer("object");


Profiler::startTimer("arrayMany");
for($i=0;$i<$max;$i++) {
	$array = array();
	$array[] = $i;
	$array[0];
}
Profiler::endTimer("arrayMany");


Profiler::startTimer("objectMany");
for($i=0;$i<$max;$i++) {
	$list = new ListInt();
	$list->addItem($i);
	$list->getItem(0);
}
Profiler::endTimer("objectMany");

Profiler::startTimer("arrayRand");
$array = array();
for($i=0;$i<$max;$i++) {
	$random = random_int(0, $max);
	$array[] = $random;
	$array[$i];
}
Profiler::endTimer("arrayRand");

Profiler::startTimer("objectRand");
$list = new ListInt();
for($i=0;$i<$max;$i++) {
	$random = random_int(0, $max);
	$list->addItem($random);
	$list->getItem($i);
}
Profiler::endTimer("objectRand");

Profiler::printTimers();
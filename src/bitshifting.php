#!/usr/bin/env php
<?php
use plibv4\profiler\Profiler;
require_once __DIR__."/../vendor/autoload.php";

$max = pow(2, 24);
Profiler::startTimer("divideMath");
for($i = 0;$i<$max;$i++) {
	$int = 4096/1024;
}
echo $int.PHP_EOL;
Profiler::endTimer("divideMath");
Profiler::startTimer("divideMathCeil");
for($i = 0;$i<$max;$i++) {
	$int = (int)ceil(4097/1024);
}
echo $int.PHP_EOL;
Profiler::endTimer("divideMathCeil");

Profiler::startTimer("divideBitshift");
for($i = 0;$i<$max;$i++) {
	$int = (4097>>1);
}
echo "4097>>10: ".$int.PHP_EOL;
Profiler::endTimer("divideBitshift");

Profiler::startTimer("multiplyMath");
for($i = 0;$i<$max;$i++) {
	$int = (1024*4);
}
Profiler::endTimer("multiplyMath");
echo $int.PHP_EOL;

Profiler::startTimer("multiplyBitshift");
for($i = 0;$i<$max;$i++) {
	$int = (4<<10);
}
echo $int.PHP_EOL;
Profiler::endTimer("multiplyBitshift");

Profiler::startTimer("getPaddedSizeMath");
for($i = 0;$i<$max;$i++) {
	$int = ceil(4106/1024)*1024;
}
echo $int.PHP_EOL;
Profiler::endTimer("getPaddedSizeMath");

Profiler::startTimer("getPaddedSizeBitshift");
for($i = 0;$i<$max;$i++) {
	$int = (4106 >> 10);
}
echo $int.PHP_EOL;
Profiler::endTimer("getPaddedSizeBitshift");


Profiler::printTimers();
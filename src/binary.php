#!/usr/bin/env php
<?php
use plibv4\profiler\Profiler;
require_once __DIR__."/../vendor/autoload.php";
class BinaryTheirs {
	function readUint32(string $binary) {
		$result = unpack("N", $binary);
	return $result[1];
	}
}

class BinaryMine {
	private static array $hashmap = array();
	function __construct() {
		if(empty(self::$hashmap)) {
			for($i = 0; $i<256;$i++) {
				self::$hashmap[chr($i)] = $i;
			}
		}
	}
	
	function readUint32(string $binary) {
		$int0 = self::$hashmap[$binary[0]];
		$int1 = self::$hashmap[$binary[1]];
		$int2 = self::$hashmap[$binary[2]];
		$int3 = self::$hashmap[$binary[3]];
	return ($int0<<24) ^ ($int1<<16) ^ ($int2<<8) ^ ($int3);
	}
	
	function readUint32Ord(string $binary) {
		$int0 = ord($binary[0]);
		$int1 = ord($binary[1]);
		$int2 = ord($binary[2]);
		$int3 = ord($binary[3]);
	return ($int0<<24) ^ ($int1<<16) ^ ($int2<<8) ^ ($int3);
	}

}

$be = "\x0A\x0B\x0C\x0D";
$le = "\x0D\x0C\x0B\x0A";

$theirs = new BinaryTheirs();
$mine = new BinaryMine();

echo $theirs->readUint32($be).PHP_EOL;
echo $mine->readUint32($be).PHP_EOL;
echo $mine->readUint32Ord($be).PHP_EOL;

$max = pow(2, 24);

Profiler::startTimer("theirs");
for($i=0;$i<$max;$i++) {
	$theirs->readUint32($be);
}
Profiler::endTimer("theirs");

Profiler::startTimer("mine");
for($i=0;$i<$max;$i++) {
	$mine->readUint32($be);
}
Profiler::endTimer("mine");

Profiler::startTimer("mineOrd");
for($i=0;$i<$max;$i++) {
	$mine->readUint32Ord($be);
}
Profiler::endTimer("mineOrd");


Profiler::printTimers();
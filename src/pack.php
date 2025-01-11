#!/usr/bin/env php
<?php
use plibv4\profiler\Profiler;
use plibv4\binary\Pack;
require_once __DIR__."/../vendor/autoload.php";
$rounds = pow(2, 24);
for($i = 0; $i<8; $i++) {
	echo ($i<<3).PHP_EOL;
}


Profiler::init();
Profiler::startTimer("php_pack");
for($i = 0;$i<$rounds;$i++) {
	$bigEndian = pack("N", $i);
	$littleEndian = pack("V", $i);
}
Profiler::endTimer("php_pack");

Profiler::startTimer("plibv4_pack_be");
for($i = 0;$i<$rounds;$i++) {
	$bigEndian = Pack::uInt32(Pack::BE, $i);
	#$littleEndian = Pack::uInt32(Pack::LE, $i);
	#$littleEndian = pack("V", $i);
}
Profiler::endTimer("plibv4_pack_be");

Profiler::startTimer("plibv4_pack_le");
for($i = 0;$i<$rounds;$i++) {
	$bigEndian = Pack::uInt32(Pack::LE, $i);
	#$littleEndian = Pack::uInt32(Pack::LE, $i);
	#$littleEndian = pack("V", $i);
}
Profiler::endTimer("plibv4_pack_le");


/*
Profiler::startTimer("plibv4_facade");
for($i = 0;$i<$rounds;$i++) {
	$bigEndian = IntVal::uint32BE($i);
	$littleEndian = IntVal::uint32LE($i);
	#$littleEndian = pack("V", $i);
}
Profiler::endTimer("plibv4_facade");
*/

Profiler::printTimers();
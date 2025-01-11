#!/usr/bin/env php

Testing several ways to call a function/method, both directly and by using
call_user_func, which implies callback pattern (yuck).

Description:

 - native:          native strlen call
 - wrapperFunction: user defined function calling strlen
 - methodStatic:    Static method
 - methodSame:      Method of unchanged instance called
 - methodChanged:   Instance state is changed by additional call
 - methodRenew:     New instance is constructed on every loop

Native is of course fastest, PHP knows it's own method. Even a function wrapper
increases call time up to four times, but methodSame/methodStatic do not improve
that by a large margin. methodChange adds another function call, which only
about doubles the time.
methodRenew however is dreadful, as it has to create an instance on every loop.

Proper object oriented design comes with a cost, but "recycling" of objects
should be considered where there is no risk of side effects.
Using call_user_func adds additional weight, but there are a lot of other
reasons why you should NEVER use callback and opt for proper observers instead.
 
<?php
use plibv4\profiler\Profiler;
require_once __DIR__."/../vendor/autoload.php";

function myStrLen(string $string): int {
	return strlen($string);
}

class MyString {
	private string $string;
	function __construct(string $string) {
		$this->string = $string;
	}
	function length(): int {
		return strlen($this->string);
	}
	
	static function strlen(string $string): int {
		return strlen($string);
	}
	
	function setString(string $string) {
		$this->string = $string;
	}
}


$max = pow(2, 24);
Profiler::init();
Profiler::startTimer("native");
for($i = 0; $i<$max;$i++) {
	strlen("Hello World!");
}
Profiler::endTimer("native");

Profiler::startTimer("wrapperFunction");
for($i = 0; $i<$max;$i++) {
	myStrlen("Hello World!");
}
Profiler::endTimer("wrapperFunction");

Profiler::startTimer("methodStatic");
for($i = 0; $i<$max;$i++) {
	MyString::strlen("Hello World!");
}
Profiler::endTimer("methodStatic");

$string = new MyString("Hello World!");
Profiler::startTimer("methodSame");
for($i = 0; $i<$max;$i++) {
	$string->length();
}
Profiler::endTimer("methodSame");

$string = new MyString("Hello World!");
Profiler::startTimer("methodChange");
for($i = 0; $i<$max;$i++) {
	$string->length();
	$string->setString("Hello world!");
}
Profiler::endTimer("methodChange");


Profiler::startTimer("methodRenew");
for($i = 0; $i<$max;$i++) {
	$string = new MyString("Hello World!");
	$string->length();
}
Profiler::endTimer("methodRenew");


Profiler::startTimer("callbackNative");
for($i = 0; $i<$max;$i++) {
	call_user_func("strlen", "Hello World!");
}
Profiler::endTimer("callbackNative");

Profiler::startTimer("callbackWrapperFunction");
for($i = 0; $i<$max;$i++) {
	call_user_func("myStrlen", "Hello World!");
}
Profiler::endTimer("callbackWrapperFunction");

Profiler::startTimer("callbackMethodStatic");
for($i = 0; $i<$max;$i++) {
	call_user_func(array("MyString", "strlen"), "Hello World!");
}
Profiler::endTimer("callbackMethodStatic");

$string = new MyString("Hello World!");
Profiler::startTimer("callbackMethodSame");
for($i = 0; $i<$max;$i++) {
	call_user_func(array($string, "length"));
}
Profiler::endTimer("callbackMethodSame");

$string = new MyString("Hello World!");
Profiler::startTimer("callbackMethodChange");
for($i = 0; $i<$max;$i++) {
	call_user_func(array($string, "length"));
	$string->setString("Hello world!");
}
Profiler::endTimer("callbackMethodChange");

Profiler::startTimer("callbackMethodRenew");
for($i = 0; $i<$max;$i++) {
	$string = new MyString("Hello World!");
	call_user_func(array($string, "length"));
}
Profiler::endTimer("callbackMethodRenew");






Profiler::printTimers();
<?php

spl_autoload_register(function ($name) {
	if(strpos($name, "Spyc") !== false) {
		require(dirname(__FILE__)."/spyc/Spyc.php");	
	} elseif(strpos($name, "Twig") === false) {
		require(dirname(__FILE__)."/$name.php");
	} 
});

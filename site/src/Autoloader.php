<?php

spl_autoload_register(function ($name) {
	if(strpos($name, "Spyc") !== false) {
		require("spyc/Spyc.php");	
	} elseif(strpos($name, "Twig") === false) {
		require($name.".php");
	} 
});

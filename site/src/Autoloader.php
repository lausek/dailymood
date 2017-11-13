<?php

spl_autoload_register(function ($name) {
	if(strpos($name, "Twig") === false) {
		require($name.".php");
	}
});
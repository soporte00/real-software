<?php 
/**
 * Auto load required file
 */
spl_autoload_register(function($class){ 
	
	require dirname(__FILE__,2) ."/". str_replace('\\','/',$class) .".php"; 
});
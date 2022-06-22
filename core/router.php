<?php

/**
 * cleaning URL 
 */
$filteredUrl = isset($_GET['url']) == null ? '' : filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
$route = explode('/', strtolower($filteredUrl));


/**
 * redirecting to home page
 */
if (
	$route[0] == 'index' ||
	$route[0] == 'home' ||
	$route[0] == ''
) {
	$route[0] = "principal";
}


/**
 * 
 * controllers instantiation
 * 
 */
$file = dirname(__FILE__, 2) . "/src/controllers/" . $route[0] . "Controller.php";

if (is_readable($file)){ 
	
	require $file;

	$instance = $route[0].'Controller';
	$instance = new $instance;
	
	
	if(!array_key_exists(1,$route) || $route[1] == ''){
		$route[1] = 'index';
	} 

	
	if(method_exists($instance,$route[1])){ 

		if(array_key_exists(2,$route)){
			call_user_func_array([$instance, $route[1]], array_slice($route, 2));
			die();
		}

		call_user_func([$instance, $route[1]]);
		die();
	}

}

/**
 * if controller or method does not exist call the 404 error page
 */
core\render::default('404.php');
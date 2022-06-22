<?php

/**
 * 1-Load environment configuration
 */
require_once dirname(__FILE__,2).'/config/env.php';

/**
 * 1.5-Load Errors and Warnings
 */
if(DEBUG){
    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}else{
    ini_set('display_startup_errors', 0);
    ini_set('display_errors', 0);
    error_reporting(0);    
}


/**
 * 2-Load autoloader
 */
require_once dirname(__FILE__,2).'/core/autoloader.php';

/**
 * 3-Load router
 */
require_once dirname(__FILE__,2).'/core/router.php';

?>
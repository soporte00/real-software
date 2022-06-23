<?php namespace core;

use Exception;
use core\session;

class logmaker{

 
    public static function insert($log){

        $logFile = fopen('log.txt', 'a') or die('Fail opening file');
        fwrite($logFile, $log)or die('Fail writing file');
        fclose($logFile);
    }
    
    
    public static function create($log){
        
        $log = date("d/m/Y H:i:s").'-'.$log.PHP_EOL;
        
        if(defined('CONSOLE')){            
            self::insert($log); 
            return false;
        }
        
        session::Put('LOG',session::Read('LOG')."\n".$log);
    }

}
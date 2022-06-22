<?php namespace core;

class session{

	public static function Put($a,$b){
		if(defined('CONSOLE')) return false;

		if(session_status() == 1)session_start();
		$_SESSION[ $a ] = $b;
	}

	public static function Read($a){
		if(defined('CONSOLE')) return false;

		if(session_status() == 1)session_start();
		if( isset($_SESSION[ $a ]) ){return $_SESSION[ $a ]; }else{return false;}
	}

	public static function Unset($a){
		if(defined('CONSOLE')) return false;

		if(session_status() == 1)session_start();
		if( isset($_SESSION[ $a ]) ){unset($_SESSION[ $a ]); }else{return false;}	
	}

}
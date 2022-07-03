<?php namespace core\console_src;


class console_restrict{

    use console_paths;

    public static $i;

    public static function set(){
        if(self::$i instanceof self){
            return self::$i;
        }

        self::$i = new self;
        return self::$i;
    }


    public function debug_mode($flag){

        if(!is_file($this->configFolder."env.php")) return;

        require_once $this->configFolder."env.php";

        if(!DEBUG){
           
            if($flag === '--force'){
                echo "\e[1;37;41m Using --force \e[0m\n";
            }else{
                echo "\e[1;37;41m Debugging mode is OFF, if you want to force it use --force \e[0m\n";
                die();
            }
        }
    }
}
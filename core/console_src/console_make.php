<?php namespace core\console_src;


use \core\console_src\console_files;
use \core\console_src\console_templates;
use \core\console_src\console_paths;

class console_make extends console_paths{



    public function __construct($method,$param)
    {   
        if($method == 'mvc'){

            console_files::file($this->modelFolder.$param.'Model.php',true,console_templates::model($param));
            console_files::file($this->viewFolder.$param.'/index.php',true,console_templates::view($param));
            console_files::file($this->controllerFolder.$param.'Controller.php',true,console_templates::controller($param));

        }elseif($method == 'vc'){

            console_files::file($this->viewFolder.$param.'/index.php',true,console_templates::view($param));
            console_files::file($this->controllerFolder.$param.'Controller.php',true,console_templates::controller($param));

        }elseif($method == 'model'){

            console_files::file($this->modelFolder.$param.'Model.php',true,console_templates::model($param));

        }elseif($method == 'database'){

            $prefix = 'db'.date_timestamp_get(date_create()).'_';

            console_files::file($this->databaseFolder.$prefix.$param.'.php',true,console_templates::database($param,$prefix));

        }else{
            echo "Wrong command...\n";
            echo "Try with php console make: database | mvc | vc | model : mymodule\n"; 
        }
    }    

        
}
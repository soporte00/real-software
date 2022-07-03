<?php namespace core\console_src;

use core\console_src\console_files;
use core\console_src\console_templates;

class console_init{

    use console_paths;

    public function __construct()
    {
        $this->makeHtaccess(); 
        $this->makeEnv();   
        $this->makeDefaultHTML();            
    }
    
    public function makeDefaultHTML(){

        console_files::file($this->defaultHTMLFolder.'header.php',true,'<?php // header default file ?>');
        console_files::file($this->defaultHTMLFolder.'bodystart.php',true,'<?php // bodystart default file ?>');
        console_files::file($this->defaultHTMLFolder.'bodyend.php',true,'<?php // bodyend default file ?>');
        console_files::file($this->defaultHTMLFolder.'404.php',true,console_templates::default404());
        console_files::file($this->defaultHTMLFolder.'deny.php',true,console_templates::defaultdeny());
    }

    public function makeEnv(){
        console_files::file($this->configFolder.'env.php',true,console_templates::env());
        console_files::file($this->configFolder.'dbConfig.php',true,console_templates::dbConfig());
    }




    public function makeHtaccess(){

        
        if(is_file($this->htaccessFile)){
        
            echo "the .htaccess file already exists, the condition will be checked...\n";
        
            /*
            // Bad file !!!
            // remove all lines and rewrite file
            */
            if(!console_files::checkByRow($this->htaccessFile,console_templates::htaccess())){
        
                echo "the .htaccess file is incorrect. it will be deleted and saved with the correct configuration\n";
                console_files::makeByRow($this->htaccessFile,console_templates::htaccess());
            }else{
        
                echo "the file is correct!\n";
            }
        
        
        }else{
        
            console_files::makeByRow($this->htaccessFile,console_templates::htaccess());
        }
        




    }


}
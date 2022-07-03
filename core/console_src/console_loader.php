<?php namespace core\console_src;

use core\console_src\console_files;

class console_loader{

    use console_paths;

    private $version='1.2.5';

    public function action($command){

        $param = explode(':',$command[1]);

        if($param[0] == 'version'){
            echo "\nConsole version: {$this->version}\n";
            die();
        }
        
        if(method_exists($this,$param[0])){
            $method = $param[0];

            $this->$method($param, isset($command[2])?$command[2]:null  );
            die();
        }

        echo "Wrong command...\n";
        echo "Try with php console make | database | init\n";
    }





    private function transfer($param,$flag=null){

        $file = "hostauth.json";
        
        if(!is_file($file)){
            echo "the transfer function is disabled\n";
            die();
        }

        $configFile = json_decode(file_get_contents($file));
    
        if($param[1] == 'all'){
            system('scp -P '.$configFile->port.' -r ./* '.$configFile->user.'@'.$configFile->host.':'.$configFile->endFolder);
        }elseif($param[1] == 'sync'){
            system('rsync -avz --exclude-from=\'.gitignore\' --exclude \''.$file.'\' --delete -e "ssh -p '.$configFile->port.'" ./ '.$configFile->user.'@'.$configFile->host.':'.$configFile->endFolder);
        }else{
            echo "Wrong command...\n";
            echo "Try with php console transfer: all | sync\n";
            echo "the sync option uses the .gitignore file to exclude files and folders\n";
        }
    
        die();    
    }
    




    private function make($param,$flag=null){
        echo "Make\n";

        if(
            $param[1] && (
                $param[1] == 'database' ||
                $param[1] == 'mvc' ||
                $param[1] == 'vc' ||
                $param[1] == 'model'
                )
        ){
            
            new \core\console_src\console_make($param[1],$param[2]);
            die();
        }

        echo "Wrong command...\n";
        echo "Try with php console make: database | mvc | vc | model\n";
    }



    private function database($param,$flag=null){

        echo "Database\n";


        /**
         * restrict on debug mode OFF
         */
        console_restrict::set()->debug_mode($flag);


        if(
            $param[1] && (
                $param[1] == 'install' ||
                $param[1] == 'uninstall' ||
                $param[1] == 'refresh'
                )
        ){
            
            if($param[1] == 'refresh'){
                $installer = new \core\console_src\console_dataInstaller();
                $installer->uninstall();
                $installer = new \core\console_src\console_dataInstaller();
                $installer->install();
                die();
            }


            $installer = new \core\console_src\console_dataInstaller();
            $param = $param[1];

            $installer->$param();
            die();
        }

        echo "Wrong command...\n";
        echo "Try with php console database: install | uninstall | refresh\n";
    }




    




    private function init($param,$flag=null){
        echo "Init\n";

        /**
         * restrict on debug mode OFF
         */
        console_restrict::set()->debug_mode($flag);

        new console_init();
    }








    /**
     * Remove function
     */
    private function remove($param,$flag=null){
        echo "Remove\n";

        if($param[1] == 'database'){
            
            foreach (glob($this->databaseFolder.'*') as $k) {
                
                $script = "/^[a-zA-Z.\/]+(db[0-9]+)_(".$param[2].").php$/";
                
                if(preg_match($script, $k, $match)){
                    console_files::remove($match[0]);
                    die();
                }           

            }
            
            echo "File {$param[2]} not founded\n";
            die();

        }elseif($param[1] == 'mvc'){


            /**
             * restrict on debug mode OFF
             */
            console_restrict::set()->debug_mode($flag);


            console_files::remove($this->controllerFolder.$param[2].'Controller.php');
            console_files::remove($this->modelFolder.$param[2].'Model.php');
            console_files::delDir($this->viewFolder.$param[2]);
            die();
        }

        echo "Wrong command...\n";
        echo "Try with php console remove: database | mvc\n";
    }


}
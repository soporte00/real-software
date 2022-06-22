<?php namespace core\console_src;

class console_files{


    public static function file($file,$create = false,$content=null){

        $realFile = explode('/',$file);
        array_pop($realFile);
        $folder= implode('/',$realFile);

        if(is_file($file)){
            echo "{$file} already exists !\n"; 
            return true;
        }else{
            
            if(!is_dir($folder) && $create){
                echo "the folder {$folder} doesn't exist, it wil be created!\n";   

                if(!mkdir($folder, 0777, true)){
                    echo "Could not create {$folder} folder\n";
                    return false;
                }
            } 

            if(!$document = fopen($file,'w')){
             echo "the file {$file} could not be created\n";
             return false;
            }

            if(!fwrite($document, $content)){
                echo "could not write {$file}";
                return false;
            }            
            
            fclose($document);


            return true;
        }
        
    }


    public static function delDir($folder)
    {
        if(!is_dir($folder)){
            echo "Not founded !\n"; 
            return false;
        }

        foreach(glob($folder . "/*") as $i){             
            if (is_dir($i)){
                self::delDir($i);
            }else{
                unlink($i);
            }
        }
        
        rmdir($folder);  
        echo "Deleted. \n";
    }


    public static function remove($file,$dir=false){

        $realFile = explode('/',$file);
        array_pop($realFile);
        $folder= implode('/',$realFile);

        
        if(is_file($file)) {
            unlink($file);
            echo "{$file} Deleted.\n";
        }else{
            echo "{$file} Not founded! \n";
        }

        if($dir) self::delDir($folder);

    }









    


    public static function makeByRow(string $f,array $text){
            
        $file=fopen($f, 'w') or die("could not create {$f}\n");
        
        foreach($text as $line){
            fwrite($file, $line.PHP_EOL) or die("could not write {$line}\n");
        }
    
        fclose($file);
        
        echo "{$f} file was created successfully\n";
    }
    
    






    public static function checkByRow(string $f,array $text):bool
    {
        $file = fopen($f, 'r') or die("\ncould not open the file");
    
        $status=true;
        while($line = fgets($file))
        {
    
            if(!in_array(str_replace(PHP_EOL,'',$line),$text)){
                $status = false;
            }
        }
        
        fclose($file);
        return $status;
    }






}
<?php namespace core\console_src;

class console_templates{


    public static function dbConfig(){
        $text = '<?php namespace config;
/** 
 * Data Base configuration
 * ---> when setting the host: don\'t use "localhost" instead use "127.0.0.1"
 */
class dbConfig{
    protected $config_host="127.0.0.1",
    $config_db="softwaredb",
    $config_user="root",
    $config_password="",
    $config_prefix="mydb_",
    $config_pagination=10;
}';
    return $text;
    }






    
    public static function env(){

        $text = '<?php
/**
 * ¡¡ Environment Configuration !!
 */
define("DEBUG",true);


/**
 * General configurations
 */
define("GENERAL",[
	"sitelang"=>"es_ES",
	"sitename"=>"software",
	"sitedescription"=>"Real-Software es un micro framework, diseñado para pequeñas aplicaciones.",
	"sitekeywords"=>"Real-Software, Real, Software, framework",
	"onlogin"=>"/dashboard",
	"version"=>"unstable 1.0.0",
	"supportEmail"=>"support@real-software.net"
]);

/**
 * TimeZone configuration
 */
date_default_timezone_set("America/Argentina/Tucuman");


/**
 * Site URL 
 * example: "https://myhost/myproject"
 * 
 * ----> dynamic URL: define("URL", "http://". $_SERVER["HTTP_HOST"] ."/software");
 */
define("URL","http://localhost/software");



// GLOBAL FUNCTIONS


/**
 * Project route
 */
function route(string $url = "",bool $redirect=false,float $timing=.001): string
{
    $url = URL . str_replace("//","/", "/".$url);

    if($redirect){
    
        $timing = $timing * 1000;

        echo "<script>setTimeout(()=>{document.location.href=\'".$url."\';} , {$timing});</script>"; 
        die();
    }

    return $url;
}

/**
 * connect with the assets folder
 */
function asset(string $url): string
{
    return route("/assets/".$url);
}



/**
 * Send alert message to frontEnd
 */
function mssg(string $message,string $type=null){
    echo "<script>mssg(\'{$message}\',\'{$type}\')</script>";
}';

        return $text;
    }














    public static function controller($module=''){

        /**
        * Controller content
        */
    $text = "<?php\n
use core\\render;

class {$module}Controller{

    public function index(){
        render::view('{$module}/index.php',['controllerName'=>'{$module}']);
    }
}";


            return $text;
    }
    
    











    
    public static function model($module=''){

    /**
    * Model content
    */
    $text = "<?php\n
use core\dbManager;

class {$module}Model extends dbManager{
".'
    private int $id;

    public function __construct(){
        parent::__construct();
    }


    public function get_id(){
        return $this->id;
    }

    public function set_id($id){
        $this->id = $id;
    }   

}';
    
    
        return $text;
    }




















    public static function view($module=''){

/**
* view content
*/
    $text = '<?=self::html()?>

<?=self::body()?>

<div class="padd1">

    <div class="centered-column padd4">
        <h2> <?=$controllerName?> </h2>
        <img src="<?= asset(\'img/icons/tool.svg\') ?>" width="50px">
    </div>
</div>

<?=self::end()?>';
    
        return $text;
    }



















    public static function database($table,$prefix){

    /**
    * database structure
    */
    $text = '<?php namespace database;

use core\dbCrafter;

class '.$prefix.$table.'{

    private $crafter;
    
    public function __construct()
    {        
        $this->crafter = new dbCrafter("'.$table.'");
    }
    

    public function install(){
        $this->crafter->craft(function($column){
            $column->id();
            $column->trace();
        });
    }



    public function uninstall(){
        $this->crafter->uncraft();
    }
        
        

}';
    
    
        return $text;
    }

















    public static function htaccess(){
        $text = [
            "RewriteEngine On",
            "RewriteBase /".basename(dirname(__FILE__,3))."/",
            "RewriteCond %{THE_REQUEST} /public/([^\s?]*) [NC]",
            "RewriteRule ^ %1 [L,NE,R=302]",
            "RewriteRule ^((?!public/)(.+)\.(.+))$ public/$1 [L,NC]",
            "RewriteRule ^((?!public/)(.*))$ public/index.php?url=$1 [L,QSA]"
        ];

        return $text;
    }













    public static function default404(){
        $text='<?php
    http_response_code(404);
    self::html(["title"=>"Error 404 Página no encontrada"]);
?>

<?=self::body()?>

<div class="centered-column padd4">
    <img src="<?= asset(\'img/icons/tool.svg\') ?>" width="30px">
    <h4>ERROR 404. No se encontró la página.</h4> 
    <a href="<?=route()?>" title="Volver a la página principal">Pág. principal</a>
</div>

<?=self::end()?>';

        return $text;
    }







    public static function defaultdeny(){
        $text='<?php
    http_response_code(403);
    self::html(["title"=>"Error 403 acceso restringido"]);
?>

<?=self::body()?>

<div class="centered-column padd4">
    <img src="<?= asset(\'img/icons/tool.svg\') ?>" width="30px">
    <h4>Permiso denegado.</h4> 
    <a href="<?=route()?>" title="Volver a la página principal">Pág. principal</a>
</div>

<?=self::end()?>';

        return $text;
    }
}
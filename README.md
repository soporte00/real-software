# software

Micro framework php el cual te ayudará de una forma rápida a crear una página web con los mínimos requerimientos para un buen funcionamiento.


Luego de descargar y copiar el framework, en la carpeta pública de nuestro servidor web, deberemos inicializar y crear
los archios necesarios (.htaccess, config/env.php, config/dbConfig.php) usando el siguiente comando:
#### ~$php console init

Habremos notado que se crearon los archivos correspondientes en los cuales podremos configurar nuestro framework con los datos del servidor.

### Creación de tablas

Podemos crear nuestras base de datos usando una plantilla la cual instanciaremos usando el siguiente código:

#### ~$php console make:database:nombre_de_mi_tabla

Esta base de datos se creará en la carpeta ./database con un prefijo específico ej: db1655648701_nombre_de_mi_tabla.php
La plantilla se puede modificar usando los siguientes parámetros:

            $column->string('name','100')->nullable(false);  //varchar ($nombre,$longitud default=255)
            $column->number('person_id');
            $column->tiny('status')->unsigned()->nullable(false,1);  //nullable (false, default)
            $column->json('extra');
            $column->time('last_access');   //datetime
            $column->trace();
            
 El parámetro [$column->trace()] creara las columnas created_at y created_by, con las cuales el sistema luego, manejará los datos de creación 
 de campos y podrá retornar de forma más precisa, un dato de inserción a la hora de obtener el id de la fila recien insertada.
 
 aqui podemos ver un ejemplo de la función install a la cual se le agregó datos de relleno.
 
    public function install(){
        $this->crafter->craft(function($column){
            $column->id();
            $column->string('name')->nullable(false);
            $column->string('surname');
            $column->number('person_id');
            $column->string('email')->nullable(false);
            $column->string('password')->nullable(false);
            $column->string('ip', 15)->nullable(false);
            $column->tiny('status')->unsigned()->nullable(false,1);
            $column->tiny('deleted')->unsigned()->nullable(false,0);
            $column->json('extra');
            $column->time('last_access');
            $column->trace();
        });

        $this->crafter->put([
            'name'=>'david',
            'email'=>'david@mail.com',
            'password'=>'$2y$10$d3WyBcjfwDxI987yRjlO6uyj/k.MhQYlfi.HhRIYk9mztL9Y667Ca' //password
        ],true);

        $this->crafter->put([
            'name'=>'Rodrigo',
            'email'=>'rodrigo@mail.com',
            'password'=>'$2y$10$d3WyBcjfwDxI987yRjlO6uyj/k.MhQYlfi.HhRIYk9mztL9Y667Ca' //password
        ],true);

    }
    
    
la opción booleana true al final de cada insert  ->put([data],true) hace referencia al auto relleno de las columnas creadas 
con la función trace().
    
#### Foreign

A continuación un ejemplo de como usar las claves foraneas.
    
        public function install(){
        $this->crafter->craft(function($column){
            $column->id();
            $column->idnumber('ticket');
            $column->idnumber('user_id');
            $column->idnumber('method');
            $column->float('value','10,6')->unsigned()->nullable(false,0);
            $column->float('total_value','10,6')->unsigned()->nullable(false,0);
            $column->string('note');
            $column->trace();
        });
        $this->crafter->foreign('method','payment_methods.id'); // (my column, refered_table.column )
        $this->crafter->foreign('user_id','users.id');
    }



    public function uninstall(){
        $this->crafter->dropForeign('method');
        $this->crafter->dropForeign('user_id');
        $this->crafter->uncraft();
    }
    
Para usar las claves foraneas, debemos tener en cuenta que los campos tendran que ser del tipo idnumber, para facilitar el uso del 
mismo tipo en ambas tablas.

Tambien podemos ver el uso de dropForeign('mycolumn') en el sector de uninstall, esto deberá hacerse manteniendo una posición superios 
al de eliminar la tabla, ya que de lo contrario dará un error al intentar eliminar la tabla sin antes quitar la clave foranea.

### Instalación de tablas

Una ves tengamos todas las tablas creadas y configuradas podemos dar paso a la instalación de las mismas usando el siguiente comando:
#### ~$php console database:install

para eliminarlas:
#### ~$php console database:uninstall ####

para refrescar:
#### ~$php console database:refresh  //esto creará una secuencia de eliminado y luego instalación de todas las tablas
    
## MVC
De la misma forma en la que creamos nuestra base de datos usando plantillas, tambien podemos crear nuestros modelos-vistas-controladores usando el siguiente código:
#### ~$php console make:mvc:nombre_de_mi_modulo
Esto nos creará en la carpeta ./src y luego en las respectivas subcarpetas ./src/models, ./src/controllers, ./src/views los archivos necesarios para 
nuestro código MVC.

Tambien podemos usar el parametro make:vc o make:m para crear solo vista y controlador o solamente un modelo.

Usemos el ejemplo: [ php console make:vc:principal ] para luego ver el renderizado de la vista.

## Render 
Nuestro framework cuenta con un sistema que facilita el llamado a la vista usando la función render::view(), esta función tambien se encarga de pasarle
variables a la vista mediante el segundo parametro. Veamos un ejemplo:


            use core\render;

            class principalController{

                public function index(){
                    render::view('principal/index.php',['controllerName'=>'principal']);
                }
            }
            
Para recibir estos parametros podemos ir a la carpeta de vistas y abrir el archivo ./src/views/principal/index.php ej:

            <?=self::html()?>

            <?=self::body()?>

                        <div class="centered-column padd4">
                            <h2> <?=$controllerName?> </h2>
                            <img src="<?= asset('img/icons/tool.svg') ?>" width="50px">
                        </div>

            <?=self::end()?>
           
Aquí podemos ver el uso del parámetro pasado anteriormente controllerName como si fuera una simple variable precargada. Tambien podemos darnos cuenta
del uso de la función asset() la cual nos dará un enlace directo a la carpeta asset la cual se encuentra dentro de la carpeta ./public y donde se deberá 
alojar todo el contenido el cual será llamado luego y no tendrá ninguna restricción de acceso, ej: imágenes, css, js, fonts, etc.

En el ejemplo anterior podemos ver las funciones self::html(), self::body() y self::end(), estas delimitan la hoja de vista para luego insertar una plantilla html la cual podemos configurar en la carpeta ./core/render.php.
Entre las funciones self::html() y self::body() podemos insertar código html el cual se cargará en la cabecera del documento, osea lo que corresponde
entre las etiquetas 
[header] Mis etiquetas cargadas en la vista [/header]

ejemplo:


            <?=self::html()?>
                        <link rel="stylesheet" href="<?= asset('css/custom_items.css') ?>" />
            <?=self::body()?>

                        <div class="centered-column padd4">
                            <h2> <?=$controllerName?> </h2>
                            <img src="<?= asset('img/icons/tool.svg') ?>" width="50px">
                        </div>

            <?=self::end()?>


Tambien podremos pasar parámetros a la función self::html() para modificar datos de posicionamiento en el header de la plantilla. 
Estos datos pueden ser:

		self::html([
                                    "lang" => 'en_EN',
                                    "title" => 'Mi sitio web',
                                    "type" => 'website',
                                    "description" => 'This is a small framework very light and versatile',
                                    "keywords" => 'Micro FrameWork small versatile mi sitio web',
                                    "url" => 'https://mi-url-custom/',
		])
                        
En el caso de querer agregar elementos html por defecto en todas las páginas de nuestro software podemos recurrir a la carpeta ./src/defaultHTML
la cual contiene los archivos header.php, bodystart.php, bodyend.php, 404.php, deny.php.
Aquí algunos ejemplos:

##### file: header.php

            <!-- favicon -->
            <link rel="shortcut icon" type="image/png" href="<?= asset('img/favicon.png') ?>" />

            <!-- normalize css -->
            <link rel="stylesheet" href="<?= asset('css/normalize.css') ?>" />

            <!-- structures and style -->
            <link rel="stylesheet" href="<?= asset('css/template.css') ?>" />
            <link rel="stylesheet" href="<?= asset('css/structures.css') ?>" />
            <link rel="stylesheet" href="<?= asset('css/style.css') ?>" />

            <!-- alertBox -->
            <link rel="stylesheet" href="<?= asset('css/alert.css') ?>" />

            <!-- Main Menu css -->
            <link rel="stylesheet" href="<?= asset('css/menu.css') ?>" />

            <!-- Font Awesome -->
            <link rel="stylesheet" href="<?= asset('fa/css/all.css') ?>" />
      
      
 ##### file: bodystart.php
            
            <?php use src\models\menuModel;?>

            <!-- alertBox -->
            <div id="alertBox" class="alertbox__container"></div><script src="<?= asset('js/alert.js') ?>"></script>

            <!-- Modal -->
            <div id="modalcontainer" class="modal__container modal__hide">
                        <button id='modalclose' class='modal__close' title='Cerrar'>x</button>
                        <div id="modal" class="modal"></div>
            </div><script src="<?=asset('js/modals.js')?>"></script>




            <!-- Main Menu -->
            <header class="main__header">

                <a href="<?=route()?>" class="main__header--title"><?= GENERAL['sitename'] ?></a>

                <div id='main__menu--hamburger' class='main__menu--hamburger'>
                    <div class="main__menu--hamburger-raw"></div>
                    <div class="main__menu--hamburger-raw"></div>
                    <div class="main__menu--hamburger-raw"></div>
                </div>

                <nav id='main__menu' class='main__menu'>
                    <ul id='main__ul'><?= menuModel::view() ?></ul>
                </nav>
            </header>


            <!-- Open Main content -->
            <main class='main__content'>

##### file: bodyend.php

            <!-- scroll up button -->
            <i id='scrollupbtn' class="fa-solid fa-circle-arrow-up fa-2x arrowup pointer undisplay"></i>

            <!--End Main content-->
            </main>


            <!-- Main Menu -->
            <script src="<?=asset('js/menu.js')?>"></script>
            <script src="<?=asset('js/scrollup.js')?>"></script>
            
            
 Podemos ver en estos ejemplos, cómo en el archivo bodystart.php aplicamos al final del mismo la etiqueta [main], y en el archivo bodyend.php colocamos la etiqueta de cierre [/main]. De este modo logramos que todas nuestras vistas estén contenidas dentro de estas etiquetas por defecto sin la necesidad 
de agregarlas en cada una de las vistas.


## Json Render
De la misma forma que contamos con un renderizador de vistas tambien podemos usar un sistema preestablecido para renderizar salidas del tipo json
por ejemplo:

##### Ejemplo1: 
			render::json()
				->message('No tiene permiso para usar esta API','e')
				->out();



			Resultado de la página:
			
			{
			"response":false,
			"message":[
				"No tiene permiso para usar esta API",
				"e"
				]
			}
			




##### Ejemplo2: 

			render::json()
				->message('Estos son los datos de búsqueda','e')
				->out( $MySql_example );

			
			Resultado de la página:
			
				{
				"response":[
					{"name":"David","age":35,"job":"Developer"},
					{"name":"Rodrigo","age":18,"job":"seller"}
					],
				"message":[
					"Estos son los datos de b\u00fasqueda",
					null
					]
				}


## Remove

Si quieres eliminar los archivos antes creados puedes usar los siguientes códigos:

Eliminar tabla. 
PD: recuerda desinstalar la tabla antes de eliminarla, de lo contrario deberás hacerlo 
de forma manual
##### ~$php console remove:database:my_table


Eliminar Modelo, Vista, Controlador
##### ~$php console remove:mvc:my_module

## Version de la consola

Para ver la version de la consola, podemos aplicar el comando:

##### ~$php console version
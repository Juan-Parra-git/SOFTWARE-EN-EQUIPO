<?php

//session_start();
//info base de datos
define('NOMBRE_SERVIDOR', 'localhost');
define('NOMBRE_USUARIO', 'root'); //nombre usuario de la base de datos
define('PASSWORD', '');// password de la base de datos
define('NOMBRE_DB', 'TSP');//nombre de la base de datos

//rutas de la web

define("SERVIDOR", "http://localhost/SOFTWARE-EN-EQUIPO"); 
define("RUTA_REGISTRO", SERVIDOR. "/registro");
define("RUTA_REGISTRO_CORRECTO", SERVIDOR. "/registro-correcto");
define("RUTA_LOGIN", SERVIDOR. "/login");
define("RUTA_RECUPERAR_CLAVE", SERVIDOR. "/recuperar-clave");
define("RUTA_GENERAR_URL_SECRETA", SERVIDOR. "/generar-url-secreta");
define("RUTA_RECUPERACION_CLAVE", SERVIDOR. "/recuperacion-clave");
define("RUTA_CLAVE_RECUPERADA", SERVIDOR. "/clave-recuperada");
define("RUTA_LOGOUT", SERVIDOR. "/logout");
define("RUTA_VENTA", SERVIDOR. "/venta");
define("RUTA_PERFIL",SERVIDOR."/perfil");
define("RUTA_PREVIA_VENTA", SERVIDOR."/previa-venta");
define("RUTA_NOSOTROS", SERVIDOR."/nosotros");
define("RUTA_COMPRA", SERVIDOR."/compra");
define("RUTA_CONTACTENOS", SERVIDOR."/contactenos");
define("RUTA_VISTA_ANIMAL", SERVIDOR."/vista-animal");

//Recursos

define("RUTA_CSS", SERVIDOR . "/css/");
define("DIRECTORIO_RAIZ", realpath(__DIR__)."/..");


?>
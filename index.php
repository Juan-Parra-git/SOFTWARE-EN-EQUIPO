<?php
include_once 'app/config.inc.php';
include_once 'app/Conexion.inc.php';

include_once 'app/usuario.inc.php';
include_once 'app/Animal.inc.php';

include_once 'app/RepositorioUsuario.inc.php';
include_once 'app/RepositorioAnimal.inc.php';


$compenentes_url = parse_url($_SERVER['REQUEST_URI']); //$_SERVER['SERVER_NAME'] .

$ruta = $compenentes_url['path'];

$partes_ruta = explode("/", $ruta);
$partes_ruta = array_filter($partes_ruta);
$partes_ruta = array_slice($partes_ruta, 0);

$ruta_elegida = 'vistas/404.php';
if ($partes_ruta[0] == 'SOFTWARE-EN-EQUIPO') { //DOMINIO SE ENCUENTRA EL PROYECTO
    if (count($partes_ruta) == 1) {
        $ruta_elegida = 'vistas/home.php';
    } elseif (count($partes_ruta) == 2) {
        switch ($partes_ruta[1]) {
            case 'registro':
                $ruta_elegida = 'vistas/registro.php';
                break;
            case 'login':
                $ruta_elegida = 'vistas/login.php';
                break;
            case 'generar-url-secreta':
                $ruta_elegida = 'scripts/generar-url-secreta.php';
                break;
            case 'clave-recuperada':
                $ruta_elegida = 'vistas/clave-recuperada.php';
                break;
            case 'recuperar-clave':
                $ruta_elegida = 'vistas/recuperar-clave.php';
                break;
            case 'logout':
                $ruta_elegida = 'vistas/logout.php';
                break;
            case 'venta':
                $ruta_elegida = 'vistas/venta.php';
                break;
            case 'perfil':
                $ruta_elegida = 'vistas/perfil.php';
                break;
            case 'previa-venta':
                $ruta_elegida = 'vistas/previa-venta.php';
                break;
            case 'nosotros':
                $ruta_elegida = 'vistas/nosotros.php';
                break;
            case 'compra':
                $ruta_elegida = 'vistas/compra.php';
                break;
            case 'contactenos':
                $ruta_elegida = 'vistas/contactenos.php';
                break;
            case 'vista-animal':
                $ruta_elegida = 'vistas/vista-animal.php';
                break;
        }
    } elseif (count($partes_ruta) == 3) {
        if ($partes_ruta[1] == 'registro-correcto') {
            $nombre = $partes_ruta[2];
            $ruta_elegida = 'vistas/registro-correcto.php';
        }
        if ($partes_ruta[1] == 'recuperacion-clave') {
            $url_personal = $partes_ruta[2];
            $ruta_elegida = 'vistas/recuperacion-clave.php';
        }
        if (count($partes_ruta) == 3 && $partes_ruta[1] === 'animal') {
            $id_animal = $partes_ruta[2];

            Conexion::abrir_conexion();
            $animal = RepositorioAnimal::obtener_animal_por_id(Conexion::obtener_conexion(), $id_animal);

            if ($animal) {
                include_once 'vistas/vista-animal.php'; // $animal ya disponible
            } else {
                echo "<div class='alert alert-danger text-center'>No se encontró el animal.</div>";
            }
            return;
        }
    }
    include_once $ruta_elegida;
}

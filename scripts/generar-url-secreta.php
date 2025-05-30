<?php

include_once 'app/config.inc.php';
include_once 'app/Conexion.inc.php';

include_once 'app/Usuario.inc.php';
include_once 'app/RecuperacionClave.inc.php';

include_once 'app/RepositorioUsuario.inc.php';
include_once 'app/RepositorioRecuperacionClave.inc.php';

include_once 'app/redireccion.inc.php';

function sa($longitud) {
    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numero_caracteres = strlen($caracteres);
    $string_aleatorio = '';

    for ($i = 0; $i < $longitud; $i++) { 
        $string_aleatorio .= $caracteres[rand(0, $numero_caracteres - 1)];
    }

    return $string_aleatorio;
}

if (isset($_POST['enviar_email'])) {
    $email = $_POST['email']; 

    Conexion::abrir_conexion();

    if (!RepositorioUsuario::correo_usuario_existe(Conexion::obtener_conexion(), $email)) {
        return;
    }

    $usuario = RepositorioUsuario::obtener_usuario_por_correo(Conexion::obtener_conexion(), $email);

    $nombre = $usuario->obtener_nombre();
    $string_aleatorio = sa(10);

    $url_secreta = hash('sha256', $string_aleatorio . $nombre);

    $peticion_generada = RepositorioRecuperacionClave::generar_peticion(Conexion::obtener_conexion(), $usuario->obtener_id(), $url_secreta);

    Conexion::cerrar_conexion();

    if ($peticion_generada) {
        Redireccion::redirigir(SERVIDOR);
    }

}

?>
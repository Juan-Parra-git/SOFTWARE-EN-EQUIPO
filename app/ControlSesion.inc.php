<?php

class ControlSesion {

    public static function iniciar_sesion($id_usuario, $nombre_usuario, $rol_usuario) {
        if (session_id() == '') {
            session_start();
        }
        
        $_SESSION['id_usuario'] = $id_usuario;
        $_SESSION['nombre_usuario'] = $nombre_usuario;
        $_SESSION['rol_usuario'] = $rol_usuario; // Agregar el rol del usuario
    }

    public static function cerrar_sesion() {
        if (session_id() == '') {
            session_start();
        }

        if (isset($_SESSION['id_usuario'])) {
            unset($_SESSION['id_usuario']);
        }
        if (isset($_SESSION['nombre_usuario'])) {
            unset($_SESSION['nombre_usuario']);
        }
        if (isset($_SESSION['rol_usuario'])) {
            unset($_SESSION['rol_usuario']); // Eliminar el rol del usuario
        }

        session_destroy();
    }

    public static function sesion_iniciada() {
        if (session_id() == '') {
            session_start();
        }

        if (isset($_SESSION['id_usuario']) && isset($_SESSION['nombre_usuario']) && isset($_SESSION['rol_usuario'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function obtener_rol_usuario() {
        if (session_id() == '') {
            session_start();
        }

        if (isset($_SESSION['rol_usuario'])) {
            return $_SESSION['rol_usuario'];
        } else {
            return null; // Si no hay rol definido
        }
    }

    public static function es_cliente() {
        return self::obtener_rol_usuario() === 'cliente';
    }

    public static function es_revisor_gf() {
        return self::obtener_rol_usuario() === 'revisor_gf';
    }

    public static function es_revisor_gd() {
        return self::obtener_rol_usuario() === 'revisor_gd';
    }

    public static function es_admin() {
        return self::obtener_rol_usuario() === 'admin';
    }
}

?>

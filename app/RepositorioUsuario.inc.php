<?php
include_once 'app/usuario.inc.php';

class RepositorioUsuario
{

    public static function insertar_usuario($conexion, $usuario)
    {

        $usuario_insertado = false;

        if (isset($conexion)) {
            try {
                $sql = "INSERT INTO usuarios (nombre, identificacion, correo, telefono, clave, estado_membresia, rol, estado_usuario, fecha_registro, fecha_inicio_membresia, fecha_fin_membresia, tipo_insumos)
                VALUES (:nombre, :identificacion, :correo, :telefono, :clave, 0, 'cliente', 1, NOW(), NOW(), NOW(), :tipo_insumos)";


                $sentencia = $conexion->prepare($sql);

                $nombre = $usuario->obtener_nombre();
                $identificacion = $usuario->obtener_identificacion();
                $correo = $usuario->obtener_correo();
                $telefono = $usuario->obtener_telefono();
                $clave = $usuario->obtener_clave();
                $tipo_insumos = $usuario->obtener_tipo_insumos();
                $sentencia->bindParam(':nombre', $nombre, PDO::PARAM_STR);
                $sentencia->bindParam(':identificacion', $identificacion, PDO::PARAM_STR);
                $sentencia->bindParam(':correo', $correo, PDO::PARAM_STR);
                $sentencia->bindParam(':telefono', $telefono, PDO::PARAM_STR);
                $sentencia->bindParam(':clave', $clave, PDO::PARAM_STR);
                $sentencia->bindParam(':tipo_insumos', $tipo_insumos, PDO::PARAM_STR);

                $usuario_insertado = $sentencia->execute();
            } catch (PDOException $ex) {
                throw new Exception('Error al preparar la consulta: ' . $ex->getMessage());
            }
        }
        return $usuario_insertado;
    }

    public static function obtener_usuario_por_id($conexion, $id)
{
    $usuario = null;
    if (isset($conexion)) {
        try {
            $sql = "SELECT * FROM usuarios WHERE id = :id";
            $sentencia = $conexion->prepare($sql);
            $sentencia->bindParam(':id', $id, PDO::PARAM_INT);
            $sentencia->execute();
            $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {
                $usuario = new Usuario(
                    $resultado['id'],
                    $resultado['nombre'],
                    $resultado['identificacion'],
                    $resultado['correo'],
                    $resultado['telefono'],
                    $resultado['clave'],
                    $resultado['estado_membresia'], 
                    $resultado['rol'],
                    $resultado['estado_usuario'],
                    $resultado['fecha_registro'],
                    $resultado['fecha_inicio_membresia'], 
                    $resultado['fecha_fin_membresia'],     
                    $resultado['tipo_insumos']
                );
            }
        } catch (PDOException $ex) {
            throw new Exception('Error al preparar la consulta: ' . $ex->getMessage());
        }
    }
    return $usuario;
}


    public static function obtener_usuario_por_correo($conexion, $correo)
    {
        $usuario = null;
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM usuarios WHERE correo = :correo";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':correo', $correo, PDO::PARAM_STR);
                $sentencia->execute();
                $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);

                if ($resultado) {
                    $usuario = new Usuario(
                        $resultado['id'],
                        $resultado['nombre'],
                        $resultado['identificacion'],
                        $resultado['correo'],
                        $resultado['telefono'],
                        $resultado['clave'],
                        $resultado['estado_membresia'],
                        $resultado['rol'],
                        $resultado['estado_usuario'],
                        $resultado['fecha_registro'],
                        $resultado['fecha_inicio_membresia'],
                        $resultado['fecha_fin_membresia'],
                        $resultado['tipo_insumos']
                    );
                }
            } catch (PDOException $ex) {
                throw new Exception('Error al preparar la consulta: ' . $ex->getMessage());
            }
        }
        return $usuario;
    }

    public static function nombre_usuario_existe($conexion, $nombre_usuario)
    {
        $existe = false;
        if (isset($conexion)) {
            try {
                $sql = "SELECT COUNT(*) FROM usuarios WHERE nombre = :nombre_usuario";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':nombre_usuario', $nombre_usuario, PDO::PARAM_STR);
                $sentencia->execute();
                $resultado = $sentencia->fetchColumn();

                if ($resultado > 0) {
                    $existe = true;
                }
            } catch (PDOException $ex) {
                throw new Exception('Error al preparar la consulta: ' . $ex->getMessage());
            }
        }
        return $existe;
    }

    public static function identificacion_existe($conexion, $identificacion)
    {
        $identificacion_existe = false;

        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM usuarios WHERE identificacion = :identificacion LIMIT 1";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':identificacion', $identificacion, PDO::PARAM_STR);
                $sentencia->execute();
                $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);

                if ($resultado) {
                    $identificacion_existe = true;
                }
            } catch (PDOException $ex) {
                print "¡Error!: " . $ex->getMessage() . "<br/>";
            }
        }
        return $identificacion_existe;
    }

    public static function correo_usuario_existe($conexion, $correo_usuario)
    {
        $existe = false;
        if (isset($conexion)) {
            try {
                $sql = "SELECT COUNT(*) FROM usuarios WHERE correo = :correo_usuario";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':correo_usuario', $correo_usuario, PDO::PARAM_STR);
                $sentencia->execute();
                $resultado = $sentencia->fetchColumn();

                if ($resultado > 0) {
                    $existe = true;
                }
            } catch (PDOException $ex) {
                throw new Exception('Error al preparar la consulta: ' . $ex->getMessage());
            }
        }
        return $existe;
    }

    public static function telefono_usuario_existe($conexion, $telefono_usuario)
    {
        $existe = false;
        if (isset($conexion)) {
            try {
                $sql = "SELECT COUNT(*) FROM usuarios WHERE telefono = :telefono_usuario";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':telefono_usuario', $telefono_usuario, PDO::PARAM_STR);
                $sentencia->execute();
                $resultado = $sentencia->fetchColumn();

                if ($resultado > 0) {
                    $existe = true;
                }
            } catch (PDOException $ex) {
                throw new Exception('Error al preparar la consulta: ' . $ex->getMessage());
            }
        }
        return $existe;
    }

    public static function actualizar_clave ($conexion, $id_usuario, $clave){
        $clave_actualizada = false;

        if (isset($conexion)) {
            try {
                $sql = "UPDATE usuarios SET clave = :clave WHERE id = :id_usuario";
                $sentencia = $conexion -> prepare($sql);
                $sentencia -> bindParam(':clave', $clave, PDO::PARAM_STR);
                $sentencia -> bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $clave_actualizada = $sentencia -> execute();
            } catch (PDOException $ex) {
                print "¡Error!: " . $ex->getMessage() . "<br/>";
            }
        }
        return $clave_actualizada;
    }

}

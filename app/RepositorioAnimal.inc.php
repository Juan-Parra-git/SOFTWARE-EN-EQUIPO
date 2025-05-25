<?php
include_once 'app/animal.inc.php';

class RepositorioAnimal
{

    // Función para insertar un nuevo animal en la base de datos
    public static function insertar_animal($conexion, $animal)
    {
        $animal_insertado = false;
        if (isset($conexion)) {
            try {
                $sql = "INSERT INTO animales (titulo, descripcion, categoria, raza, pureza, sexo, tipo_animal, edad, peso, precio, tipo_precio, telefono, correo, departamento, municipio, direccion, destacado, premium, imagenes, videos, sugerido, fecha_fin)
                        VALUES (:titulo, :descripcion, :categoria, :raza, :pureza, :sexo, :tipo_animal, :edad, :peso, :precio, :tipo_precio, :telefono, :correo, :departamento, :municipio, :direccion, :destacado, :premium, :imagenes, :videos, :sugerido, :fecha_fin)";

                $sentencia = $conexion->prepare($sql);

                $titulo = $animal->obtener_titulo();
                $descripcion = $animal->obtener_descripcion();
                $categoria = $animal->obtener_categoria();
                $raza = $animal->obtener_raza();
                $pureza = $animal->obtener_pureza();
                $sexo = $animal->obtener_sexo();
                $tipo_animal = $animal->obtener_tipo_animal();
                $edad = $animal->obtener_edad();
                $peso = $animal->obtener_peso();
                $precio = $animal->obtener_precio();
                $tipo_precio = $animal->obtener_tipo_precio();
                $telefono = $animal->obtener_telefono();
                $correo = $animal->obtener_correo();
                $departamento = $animal->obtener_departamento();
                $municipio = $animal->obtener_municipio();
                $direccion = $animal->obtener_direccion();
                $destacado = $animal->esta_destacado();
                $premium = $animal->es_premium();
                $imagenes_json = json_encode($animal->obtener_imagenes());
                $videos_json = json_encode($animal->obtener_videos());
                $sugerido = $animal->es_sugerido();
                $fecha_fin = $animal->obtener_fecha_fin();

                $sentencia->bindParam(':titulo', $titulo, PDO::PARAM_STR);
                $sentencia->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
                $sentencia->bindParam(':categoria', $categoria, PDO::PARAM_STR);
                $sentencia->bindParam(':raza', $raza, PDO::PARAM_STR);
                $sentencia->bindParam(':pureza', $pureza, PDO::PARAM_STR);
                $sentencia->bindParam(':sexo', $sexo, PDO::PARAM_STR);
                $sentencia->bindParam(':tipo_animal', $tipo_animal, PDO::PARAM_STR);
                $sentencia->bindParam(':edad', $edad, PDO::PARAM_STR);
                $sentencia->bindParam(':peso', $peso, PDO::PARAM_STR);
                $sentencia->bindParam(':precio', $precio, PDO::PARAM_STR);
                $sentencia->bindParam(':tipo_precio', $tipo_precio, PDO::PARAM_STR);
                $sentencia->bindParam(':telefono', $telefono, PDO::PARAM_STR);
                $sentencia->bindParam(':correo', $correo, PDO::PARAM_STR);
                $sentencia->bindParam(':departamento', $departamento, PDO::PARAM_STR);
                $sentencia->bindParam(':municipio', $municipio, PDO::PARAM_STR);
                $sentencia->bindParam(':direccion', $direccion, PDO::PARAM_STR);
                $sentencia->bindParam(':destacado', $destacado, PDO::PARAM_INT);
                $sentencia->bindParam(':premium', $premium, PDO::PARAM_INT);
                $sentencia->bindParam(':imagenes', $imagenes_json, PDO::PARAM_STR);
                $sentencia->bindParam(':videos', $videos_json, PDO::PARAM_STR);
                $sentencia->bindParam(':sugerido', $sugerido, PDO::PARAM_INT);
                $sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);



                $animal_insertado = $sentencia->execute();
            } catch (PDOException $ex) {
                throw new Exception('Error al preparar la consulta: ' . $ex->getMessage());
            }
        }
        if ($animal_insertado) {
            return $conexion->lastInsertId();
        }

        return false;
    }


    // Función para obtener un animal por su ID
    public static function obtener_animal_por_id($conexion, $id)
    {
        $animal = null;
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM animales WHERE id = :id";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':id', $id, PDO::PARAM_INT);
                $sentencia->execute();
                $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);

                if ($resultado) {
                    $animal = new Animal(
                        $resultado['id'],
                        $resultado['titulo'],
                        $resultado['descripcion'],
                        $resultado['categoria'],
                        $resultado['raza'],
                        $resultado['pureza'],
                        $resultado['sexo'],
                        $resultado['tipo_animal'],
                        $resultado['edad'],
                        $resultado['peso'],
                        $resultado['precio'],
                        $resultado['tipo_precio'],
                        $resultado['telefono'],
                        $resultado['correo'],
                        $resultado['departamento'],
                        $resultado['municipio'],
                        $resultado['direccion'],
                        $resultado['destacado'],
                        $resultado['premium'],
                        json_decode($resultado['imagenes'], true),
                        json_decode($resultado['videos'], true),
                        $resultado['sugerido'],
                        $resultado['fecha_fin']
                    );
                }
            } catch (PDOException $ex) {
                throw new Exception('Error al preparar la consulta: ' . $ex->getMessage());
            }
        }
        return $animal;
    }

    // Función para obtener todos los animales
    public static function obtener_todos_los_animales($conexion)
    {
        $animales = [];
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM animales ORDER BY id DESC"; // Más recientes primero
                $sentencia = $conexion->prepare($sql);
                $sentencia->execute();
                $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);

                foreach ($resultado as $fila) {
                    $animales[] = new Animal(
                        $fila['id'],
                        $fila['titulo'],
                        $fila['descripcion'],
                        $fila['categoria'],
                        $fila['raza'],
                        $fila['pureza'],
                        $fila['sexo'],
                        $fila['tipo_animal'],
                        $fila['edad'],
                        $fila['peso'],
                        $fila['precio'],
                        $fila['tipo_precio'],
                        $fila['telefono'],
                        $fila['correo'],
                        $fila['departamento'],
                        $fila['municipio'],
                        $fila['direccion'],
                        $fila['destacado'],
                        $fila['premium'],
                        json_decode($fila['imagenes'], true),
                        json_decode($fila['videos'], true),
                        $fila['sugerido'],
                        $fila['fecha_fin']
                    );
                }
            } catch (PDOException $ex) {
                throw new Exception('Error al preparar la consulta: ' . $ex->getMessage());
            }
        }
        return $animales;
    }

    public static function obtener_animal_sugerido($conexion)
    {
        $animales = [];
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM animales WHERE sugerido = 1 ORDER BY id DESC"; // Más recientes primero
                $sentencia = $conexion->prepare($sql);
                $sentencia->execute();
                $resultados = $sentencia->fetchAll(PDO::FETCH_ASSOC);

                foreach ($resultados as $fila) {
                    $animales[] = new Animal(
                        $fila['id'],
                        $fila['titulo'],
                        $fila['descripcion'],
                        $fila['categoria'],
                        $fila['raza'],
                        $fila['pureza'],
                        $fila['sexo'],
                        $fila['tipo_animal'],
                        $fila['edad'],
                        $fila['peso'],
                        $fila['precio'],
                        $fila['tipo_precio'],
                        $fila['telefono'],
                        $fila['correo'],
                        $fila['departamento'],
                        $fila['municipio'],
                        $fila['direccion'],
                        $fila['destacado'],
                        $fila['premium'],
                        json_decode($fila['imagenes'], true),
                        json_decode($fila['videos'], true),
                        $fila['sugerido'],
                        $fila['fecha_fin']
                    );
                }
            } catch (PDOException $ex) {
                throw new Exception('Error al preparar la consulta: ' . $ex->getMessage());
            }
        }
        return $animales;
    }

    public static function obtener_animal_premium($conexion)
    {
        $animales = [];
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM animales WHERE premium = 1 ORDER BY id DESC"; // Más recientes primero
                $sentencia = $conexion->prepare($sql);
                $sentencia->execute();
                $resultados = $sentencia->fetchAll(PDO::FETCH_ASSOC);

                foreach ($resultados as $fila) {
                    $animales[] = new Animal(
                        $fila['id'],
                        $fila['titulo'],
                        $fila['descripcion'],
                        $fila['categoria'],
                        $fila['raza'],
                        $fila['pureza'],
                        $fila['sexo'],
                        $fila['tipo_animal'],
                        $fila['edad'],
                        $fila['peso'],
                        $fila['precio'],
                        $fila['tipo_precio'],
                        $fila['telefono'],
                        $fila['correo'],
                        $fila['departamento'],
                        $fila['municipio'],
                        $fila['direccion'],
                        $fila['destacado'],
                        $fila['premium'],
                        json_decode($fila['imagenes'], true),
                        json_decode($fila['videos'], true),
                        $fila['sugerido'],
                        $fila['fecha_fin']
                    );
                }
            } catch (PDOException $ex) {
                throw new Exception('Error al preparar la consulta: ' . $ex->getMessage());
            }
        }
        return $animales;
    }

    public static function obtener_animal_destacado($conexion)
    {
        $animales = [];
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM animales WHERE destacado = 1 ORDER BY id DESC"; // Más recientes primero
                $sentencia = $conexion->prepare($sql);
                $sentencia->execute();
                $resultados = $sentencia->fetchAll(PDO::FETCH_ASSOC);

                foreach ($resultados as $fila) {
                    $animales[] = new Animal(
                        $fila['id'],
                        $fila['titulo'],
                        $fila['descripcion'],
                        $fila['categoria'],
                        $fila['raza'],
                        $fila['pureza'],
                        $fila['sexo'],
                        $fila['tipo_animal'],
                        $fila['edad'],
                        $fila['peso'],
                        $fila['precio'],
                        $fila['tipo_precio'],
                        $fila['telefono'],
                        $fila['correo'],
                        $fila['departamento'],
                        $fila['municipio'],
                        $fila['direccion'],
                        $fila['destacado'],
                        $fila['premium'],
                        json_decode($fila['imagenes'], true),
                        json_decode($fila['videos'], true),
                        $fila['sugerido'],
                        $fila['fecha_fin']
                    );
                }
            } catch (PDOException $ex) {
                throw new Exception('Error al preparar la consulta: ' . $ex->getMessage());
            }
        }
        return $animales;
    }

    public static function obtener_animales_normales($conexion)
    {
        $animales = [];
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM animales ORDER BY id DESC";
                $sentencia = $conexion->prepare($sql);
                $sentencia->execute();
                $resultados = $sentencia->fetchAll(PDO::FETCH_ASSOC);

                foreach ($resultados as $fila) {
                    $animales[] = new Animal(
                        $fila['id'],
                        $fila['titulo'],
                        $fila['descripcion'],
                        $fila['categoria'],
                        $fila['raza'],
                        $fila['pureza'],
                        $fila['sexo'],
                        $fila['tipo_animal'],
                        $fila['edad'],
                        $fila['peso'],
                        $fila['precio'],
                        $fila['tipo_precio'],
                        $fila['telefono'],
                        $fila['correo'],
                        $fila['departamento'],
                        $fila['municipio'],
                        $fila['direccion'],
                        $fila['destacado'],
                        $fila['premium'],
                        json_decode($fila['imagenes'], true),
                        json_decode($fila['videos'], true),
                        $fila['sugerido'],
                        $fila['fecha_fin']
                    );
                }
            } catch (PDOException $ex) {
                throw new Exception('Error al preparar la consulta: ' . $ex->getMessage());
            }
        }
        return $animales;
    }

    public static function eliminar_anuncios_vencidos($conexion)
    {
        if (isset($conexion)) {
            try {
                $sql = "DELETE FROM animales WHERE fecha_fin IS NOT NULL AND fecha_fin < NOW()";
                $sentencia = $conexion->prepare($sql);
                $sentencia->execute();
            } catch (PDOException $ex) {
                throw new Exception('Error al eliminar anuncios vencidos: ' . $ex->getMessage());
            }
        }
    }
}

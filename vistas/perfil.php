<!-- perfil.php -->
<?php
include_once 'app/config.inc.php';
include_once 'app/Conexion.inc.php';
include_once 'app/RepositorioUsuario.inc.php';
include_once 'app/ControlSesion.inc.php';
include_once 'app/Redireccion.inc.php';

$titulo = 'Perfil de Usuario';

if (!ControlSesion::sesion_iniciada()) {
    Redireccion::redirigir(SERVIDOR);
}

Conexion::abrir_conexion();
$usuario = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $_SESSION['id_usuario']);

if (!$usuario) {
    echo '<div class="alert alert-danger mt-4">No se pudo cargar la información del usuario.</div>';
    exit;
}

// -------------------------
// Subida de imagen de perfil
// -------------------------
$mensaje_swal = '';

if (isset($_POST['guardar_imagen']) && !empty($_FILES['archivo_subido']['tmp_name'])) {
    $directorio = DIRECTORIO_RAIZ . '/subidas/';
    $nombre_archivo = $usuario->obtener_id() . '.jpg';
    $ruta_objetivo = $directorio . $nombre_archivo;
    $tipo_imagen = strtolower(pathinfo($_FILES['archivo_subido']['name'], PATHINFO_EXTENSION));
    $es_valida = true;

    if (!getimagesize($_FILES['archivo_subido']['tmp_name'])) {
        $mensaje_swal = "Swal.fire({icon: 'error', title: 'Archivo inválido', text: 'El archivo no es una imagen válida.'});";
        $es_valida = false;
    }

    if ($_FILES['archivo_subido']['size'] > 1000000) {
        $mensaje_swal = "Swal.fire({icon: 'error', title: 'Archivo demasiado grande', text: 'La imagen debe pesar menos de 1MB.'});";
        $es_valida = false;
    }

    if (!in_array($tipo_imagen, ['jpg', 'jpeg', 'png'])) {
        $mensaje_swal = "Swal.fire({icon: 'error', title: 'Tipo de archivo no permitido', text: 'Solo se permiten imágenes JPG, JPEG o PNG.'});";
        $es_valida = false;
    }

    if ($es_valida) {
        if (move_uploaded_file($_FILES['archivo_subido']['tmp_name'], $ruta_objetivo)) {
            $mensaje_swal = "Swal.fire({icon: 'success', title: '¡Éxito!', text: 'Imagen actualizada correctamente.'});";
        } else {
            $mensaje_swal = "Swal.fire({icon: 'error', title: 'Error al subir', text: 'Hubo un problema al guardar la imagen.'});";
        }
    }
}

include_once 'plantillas/documento-apertura.inc.php';
include_once 'plantillas/navbar.inc.php';
?>
<br>
<br>
<br>
<div class="container mt-4 mb-4 perfil">
    <div class="row justify-content-center">
        <!-- Foto de perfil -->
        <div class="col-md-4">
            <div class="card shadow-sm p-4 text-center h-100">
                <h4 class="text-success fw-bold mb-3">Tu Foto de Perfil</h4>
                <?php
                $ruta_imagen = SERVIDOR . '/subidas/' . $usuario->obtener_id() . '.jpg';
                if (file_exists(DIRECTORIO_RAIZ . '/subidas/' . $usuario->obtener_id() . '.jpg')) {
                    echo '<img src="' . $ruta_imagen . '" class="img-fluid rounded-circle mb-3 border border-2 border-success mx-auto d-block" style="max-width: 180px;">';
                } else {
                    echo '<img src="' . SERVIDOR . '/subidas/Logo.jpg" class="img-fluid rounded-circle mb-3 border border-2 border-secondary mx-auto d-block" style="max-width: 180px;">';
                }
                ?>
                <form method="post" enctype="multipart/form-data">
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        <label for="archivo_subido" class="btn btn-outline-success mb-0">Subir Imagen</label>
                        <button type="submit" name="guardar_imagen" class="btn btn-success">Guardar</button>
                    </div>
                    <input type="file" name="archivo_subido" id="archivo_subido" class="form-control" style="display: none;">
                    <span id="nombre-archivo" class="text-muted d-block"></span>
                </form>
            </div>
        </div>

        <!-- Datos del usuario -->
        <div class="col-md-6">
            <div class="card tarjeta-registro animar-entrada">
                <div class="encabezado-degradado text-white">
                    <h4 class="mb-0">Información del Usuario</h4>
                </div>
                <div class="fondo-registro">
                    <p><strong>Nombre:</strong> <?php echo $usuario->obtener_nombre(); ?></p>
                    <p><strong>Identificación:</strong> <?php echo $usuario->obtener_identificacion(); ?></p>
                    <p><strong>Correo:</strong> <?php echo $usuario->obtener_correo(); ?></p>
                    <p><strong>Teléfono:</strong> <?php echo $usuario->obtener_telefono(); ?></p>
                    <p><strong>Fecha de registro:</strong> <?php echo $usuario->obtener_fecha_registro(); ?></p>
                    <p><strong>Rol:</strong> <?php echo $usuario->obtener_rol(); ?></p>
                    <p><strong>Tipo de insumos:</strong> <?php echo $usuario->obtener_tipo_insumos(); ?></p>
                    <p><strong>Membresía activa:</strong> 
                        <?php echo $usuario->obtener_estado_membresia() ? '<span class="text-success">Sí</span>' : '<span class="text-danger">No</span>'; ?>
                    </p>
                    <a href="editar_perfil.php" class="btn btn-outline-success mt-3">Editar Perfil</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mensaje emergente SweetAlert2 -->
<?php if (!empty($mensaje_swal)): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            <?php echo $mensaje_swal; ?>
        });
    </script>
<?php endif; ?>

<script>
    document.getElementById('archivo_subido').addEventListener('change', function () {
        const archivo = this.files[0];
        const nombreArchivoSpan = document.getElementById('nombre-archivo');
        nombreArchivoSpan.textContent = archivo ? 'Archivo seleccionado: ' + archivo.name : '';
    });

    document.querySelector('label[for="archivo_subido"]').addEventListener('click', function () {
        document.getElementById('archivo_subido').click();
    });
</script>

<?php include_once 'plantillas/documento-cierre.inc.php'; ?>

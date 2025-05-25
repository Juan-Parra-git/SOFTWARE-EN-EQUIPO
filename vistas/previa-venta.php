<?php
include_once 'app/config.inc.php';
include_once 'app/ControlSesion.inc.php';

$titulo = 'Vista previa de tu publicación';

if (!ControlSesion::sesion_iniciada()) {
    header("Location: " . RUTA_LOGIN);
    exit();
}

// Recuperar datos del formulario anterior con POST
$datos = $_POST;


// Incluir el encabezado y navbar
include_once 'plantillas/documento-apertura.inc.php';
include_once 'plantillas/navbar.inc.php';
?>
<br><br>
<div class="container my-5 animate-fade-in">
    <div class="card tarjeta-registro">
        <div class="encabezado-degradado text-white">
            <h3 class="mb-0"><i class="fas fa-eye"></i> Vista Previa del Registro</h3>
            <p class="subtitulo-registro">Revisa cuidadosamente antes de publicar</p>
        </div>
        <div class="fondo-registro">
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <h5><i class="fas fa-heading text-success"></i> Título</h5>
                    <p class="lead"><?= htmlspecialchars($datos['titulo'] ?? '') ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <h5><i class="fas fa-cow text-success"></i> Categoría</h5>
                    <p class="lead"><?= htmlspecialchars($datos['categoria'] ?? '') ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <h5><i class="fas fa-dna text-success"></i> Raza / Pureza</h5>
                    <p class="lead"><?= htmlspecialchars($datos['raza'] ?? '') ?> (<?= htmlspecialchars($datos['pureza'] ?? '') ?>)</p>
                </div>
                <div class="col-md-6 mb-3">
                    <h5><i class="fas fa-venus-mars text-success"></i> Sexo</h5>
                    <p class="lead"><?= htmlspecialchars($datos['sexo'] ?? '') ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <h5><i class="fas fa-hourglass-half text-success"></i> Edad</h5>
                    <p class="lead"><?= htmlspecialchars($datos['edad'] ?? '') ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <h5><i class="fas fa-weight text-success"></i> Peso</h5>
                    <p class="lead"><?= htmlspecialchars($datos['peso'] ?? '') ?> kg</p>
                </div>
                <div class="col-md-6 mb-3">
                    <h5><i class="fas fa-dollar-sign text-success"></i> Precio</h5>
                    <p class="lead"><?= htmlspecialchars($datos['precio'] ?? '') ?> <?= htmlspecialchars($datos['tipo_precio'] ?? '') ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <h5><i class="fas fa-phone text-success"></i> Teléfono</h5>
                    <p class="lead"><?= htmlspecialchars($datos['telefono'] ?? '') ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <h5><i class="fas fa-envelope text-success"></i> Correo</h5>
                    <p class="lead"><?= htmlspecialchars($datos['correo'] ?? '') ?></p>
                </div>
                <div class="col-md-12 mb-3">
                    <h5><i class="fas fa-map-marker-alt text-success"></i> Ubicación</h5>
                    <p class="lead"><?= htmlspecialchars($datos['departamento'] ?? '') ?>, <?= htmlspecialchars($datos['municipio'] ?? '') ?> - <?= htmlspecialchars($datos['direccion'] ?? '') ?></p>
                </div>
                <div class="col-md-12 mb-3">
                    <h5><i class="fas fa-align-left text-success"></i> Descripción</h5>
                    <p><?= nl2br(htmlspecialchars($datos['descripcion'] ?? '')) ?></p>
                </div>
            </div>

            <div class="text-center">
                <form action="<?php echo RUTA_VENTA; ?>" method="POST" enctype="multipart/form-data">
                    <?php foreach ($datos as $clave => $valor): ?>
                        <input type="hidden" name="<?= htmlspecialchars($clave) ?>" value="<?= htmlspecialchars($valor) ?>">
                    <?php endforeach; ?>

                    <div class="col-md-12 mb-3">
                        <h5><i class="fas fa-images text-success"></i> Imágenes cargadas</h5>
                        <div class="row">
                            <?php
                            if (!empty($_FILES['fotos']['tmp_name'][0])) {
                                foreach ($_FILES['fotos']['tmp_name'] as $index => $tmpName) {
                                    if ($_FILES['fotos']['error'][$index] === UPLOAD_ERR_OK) {
                                        $data = base64_encode(file_get_contents($tmpName));
                                        $mime = mime_content_type($tmpName);
                                        echo '<div class="col-12 col-sm-6 col-md-4 mb-3">';
                                        echo '<img src="data:' . $mime . ';base64,' . $data . '" class="img-fluid img-thumbnail img-preview" alt="Vista previa">';
                                        echo '</div>';
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>

                    <?php
                    $plan_seleccionado = $datos['plan'] ?? '';

                    // Solo mostrar el video si el plan lo permite
                    if ($plan_seleccionado !== 'personal1') {
                        echo '<div class="col-md-12 mb-3">';
                        echo '<h5><i class="fas fa-video text-success"></i> Video cargado</h5>';

                        if (!empty($_FILES['videos']['tmp_name'][0])) {
                            foreach ($_FILES['videos']['tmp_name'] as $index => $tmpName) {
                                if ($_FILES['videos']['error'][$index] === UPLOAD_ERR_OK && !empty($tmpName)) {
                                    $data = base64_encode(file_get_contents($tmpName));
                                    $mime = mime_content_type($tmpName);
                                    echo '<video controls class="w-100" style="max-height: 400px;">';
                                    echo '<source src="data:' . $mime . ';base64,' . $data . '" type="' . $mime . '">';
                                    echo 'Tu navegador no soporta videos.';
                                    echo '</video>';
                                }
                            }
                        }

                        echo '</div>';
                    }

                    ?>

                    <?php
                    // Volver a enviar archivos ocultamente (se reenvían en $_FILES si se vuelve a cargar desde JS, pero esto es opcional extra)
                    foreach ($_FILES['fotos']['tmp_name'] as $index => $tmp) {
                        if (!empty($tmp) && $_FILES['fotos']['error'][$index] === UPLOAD_ERR_OK) {
                            echo '<input type="hidden" name="fotos_tmp[]" value="' . base64_encode(file_get_contents($tmp)) . '">';
                            echo '<input type="hidden" name="fotos_type[]" value="' . mime_content_type($tmp) . '">';
                        }
                    }
                    foreach ($_FILES['videos']['tmp_name'] as $index => $tmp) {
                        if (!empty($tmp) && $_FILES['videos']['error'][$index] === UPLOAD_ERR_OK) {
                            echo '<input type="hidden" name="videos_tmp[]" value="' . base64_encode(file_get_contents($tmp)) . '">';
                            echo '<input type="hidden" name="videos_type[]" value="' . mime_content_type($tmp) . '">';
                        }
                    }

                    ?>
                    <a href="javascript:history.back()" class="btn btn-outline-success mx-2">
                        <i class="fas fa-arrow-left"></i> Editar
                    </a>
                    <button type="submit" name="publicar" class="btn btn-success mx-2">
                        <i class="fas fa-check-circle"></i> Confirmar y Publicar
                    </button>

                </form>

            </div>
        </div>
    </div>
</div>

<?php include_once 'plantillas/documento-cierre.inc.php'; ?>
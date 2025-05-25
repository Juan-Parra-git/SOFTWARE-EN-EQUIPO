<?php
include_once 'app/config.inc.php';
include_once 'plantillas/documento-apertura.inc.php';
include_once 'plantillas/navbar.inc.php';

if (!isset($animal) || !$animal instanceof Animal) {
    echo "<div class='alert alert-danger text-center'>No se encontró la publicación.</div>";
    include_once 'plantillas/documento-cierre.inc.php';
    return;
}

function ruta_web($ruta_absoluta)
{
    $ruta_absoluta = str_replace('\\', '/', $ruta_absoluta);
    $pos = strpos($ruta_absoluta, '/usuarios/');
    if ($pos === false) {
        $pos = strpos($ruta_absoluta, 'usuarios/');
    }
    return $pos !== false ? '/' . ltrim(substr($ruta_absoluta, $pos), '/') : $ruta_absoluta;
}

function obtener_mime_video($ext)
{
    $ext = strtolower($ext);
    $mimes = ['mp4' => 'video/mp4', 'webm' => 'video/webm', 'ogg' => 'video/ogg'];
    return $mimes[$ext] ?? 'video/mp4';
}

function json_decode_recursivo($json, $depth = 3)
{
    $decoded = $json;
    while (is_string($decoded) && $depth-- > 0) {
        $decoded = json_decode($decoded, true);
    }
    return $decoded;
}

$imagenes_raw = json_decode_recursivo($animal->obtener_imagenes()) ?? [];
$videos_raw = json_decode_recursivo($animal->obtener_videos()) ?? [];

$imagenes = [];
$videos = [];

foreach ($imagenes_raw as $media_url) {
    $ext = strtolower(pathinfo($media_url, PATHINFO_EXTENSION));
    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
        $imagenes[] = $media_url;
    } elseif (in_array($ext, ['mp4', 'webm', 'ogg'])) {
        $videos[] = $media_url;
    }
}

foreach ($videos_raw as $media_url) {
    if (!in_array($media_url, $videos)) {
        $videos[] = $media_url;
    }
}

$precio_formateado = number_format($animal->obtener_precio(), 0, ',', '.') . ' COP';

?>
<br><br>
<div class="container my-5">
    <div class="row">
        <!-- Contenido principal (80%) -->
        <div class="col-lg-8 animate-fade-in">
            <div class="card tarjeta-animal p-4">
                <div class="row g-0">
                    <div class="col-md-5 mb-4">
                        <!-- Título sección imágenes -->
                        <?php if (!empty($imagenes)): ?>
                            <h4 class="section-title mb-3">
                                <i class="bi bi-images me-2"></i>Imágenes
                            </h4>
                            <div id="carouselImagenes" class="carousel slide mb-4" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <?php foreach ($imagenes as $k => $img_url): ?>
                                        <div class="carousel-item<?= $k === 0 ? ' active' : '' ?>">
                                            <img src="<?= SERVIDOR . ruta_web($img_url) ?>" class="d-block w-100 rounded" style="object-fit:cover; min-height:360px; max-height:480px;">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselImagenes" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselImagenes" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Siguiente</span>
                                </button>
                                <div class="carousel-indicators mt-3">
                                    <?php foreach ($imagenes as $k => $_): ?>
                                        <button type="button" data-bs-target="#carouselImagenes" data-bs-slide-to="<?= $k ?>" class="<?= $k === 0 ? 'active' : '' ?>" aria-current="<?= $k === 0 ? 'true' : 'false' ?>" aria-label="Slide <?= $k + 1 ?>"></button>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Título sección videos -->
                        <?php if (!empty($videos)): ?>
                            <h4 class="section-title mb-3">
                                <i class="bi bi-camera-video-fill me-2"></i>Videos
                            </h4>
                            <div id="carouselVideos" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <?php foreach ($videos as $k => $video_url): ?>
                                        <div class="carousel-item<?= $k === 0 ? ' active' : '' ?>">
                                            <video class="d-block w-100 rounded" style="object-fit:cover; min-height:360px; max-height:480px;" controls>
                                                <source src="<?= SERVIDOR . ruta_web($video_url) ?>" type="<?= obtener_mime_video(pathinfo($video_url, PATHINFO_EXTENSION)) ?>">
                                            </video>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselVideos" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselVideos" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Siguiente</span>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-7 d-flex flex-column justify-content-between ps-md-4">
                        <h2 class="main-title mb-4"><?= htmlspecialchars($animal->obtener_titulo()) ?></h2>

                        <div class="precio-animal precio-vista-animal">
                            <i class="bi bi-currency-dollar me-3"></i><strong><?= $precio_formateado ?></strong>
                        </div>

                        <!-- Sección Descripción -->
                        <?php if ($animal->obtener_descripcion()): ?>
                            <section class="mb-5">
                                <h4 class="section-title">
                                    <i class="bi bi-card-text me-3"></i>Descripción
                                </h4>
                                <p class="section-content"><?= nl2br(htmlspecialchars($animal->obtener_descripcion())) ?></p>
                            </section>
                        <?php endif; ?>

                        <!-- Sección Datos adicionales -->
                        <section class="mb-5">
                            <h4 class="section-title mb-4">
                                <i class="bi bi-info-circle-fill me-3"></i>Detalles
                            </h4>
                            <ul class="list-group list-group-flush fs-4">
                                <li class="list-group-item">
                                    <i class="bi bi-tags-fill me-3 text-success"></i><strong>Categoría:</strong> <?= htmlspecialchars($animal->obtener_categoria()) ?>
                                </li>
                                <li class="list-group-item">
                                    <i class="bi bi-award-fill me-3 text-warning"></i><strong>Raza:</strong> <?= htmlspecialchars($animal->obtener_raza()) ?>
                                </li>
                                <li class="list-group-item">
                                    <i class="bi bi-patch-check-fill me-3 text-primary"></i><strong>Pureza:</strong> <?= htmlspecialchars($animal->obtener_pureza()) ?>
                                </li>
                                <li class="list-group-item">
                                    <i class="bi bi-gender-ambiguous me-3 text-danger"></i><strong>Sexo:</strong> <?= htmlspecialchars($animal->obtener_sexo()) ?>
                                </li>
                                <li class="list-group-item">
                                    <i class="bi bi-hourglass-split me-3 text-info"></i><strong>Edad:</strong> <?= htmlspecialchars($animal->obtener_edad()) ?>
                                </li>
                            </ul>
                        </section>

                        <!-- Sección Ubicación y Contacto -->
                        <section>
                            <h4 class="section-title mb-4">
                                <i class="bi bi-geo-alt-fill me-3"></i>Ubicación & Contacto
                            </h4>
                            <p class="section-content fs-5 mb-3">
                                <i class="bi bi-building me-2" title="Municipio"></i> <?= htmlspecialchars($animal->obtener_municipio()) ?><br>
                                <i class="bi bi-flag-fill me-2" title="Departamento"></i> <?= htmlspecialchars($animal->obtener_departamento()) ?><br>
                                <i class="bi bi-geo me-2" title="Dirección"></i> <?= htmlspecialchars($animal->obtener_direccion()) ?>
                            </p>
                            <p class="section-content fs-5">
                                <i class="bi bi-telephone-fill me-2 text-success"></i><strong>Teléfono:</strong> <?= htmlspecialchars($animal->obtener_telefono()) ?><br>
                                <i class="bi bi-envelope-fill me-2 text-primary"></i><strong>Correo:</strong> <?= htmlspecialchars($animal->obtener_correo()) ?>
                            </p>
                        </section>
                    </div>
                </div>
            </div>
        </div>

        <!-- Espacio publicidad (20%) -->
        <div class="col-lg-4">
            <div class="seccion-explora text-center p-4">
                <h4 class="titulo-seccion-explora mb-3">Publicidad</h4>
                <p class="subtitulo-seccion-explora">Espacio disponible para banners o promociones.</p>
            </div>
        </div>
    </div>
</div>

<?php include_once 'plantillas/documento-cierre.inc.php'; ?>
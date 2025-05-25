<?php
include_once 'app/ControlSesion.inc.php';
include_once 'app/Conexion.inc.php';
include_once 'app/Redireccion.inc.php';
include_once 'app/config.inc.php';
include_once 'app/EscritorAnimal.inc.php';

include_once 'plantillas/navbar.inc.php';
include_once 'plantillas/documento-apertura.inc.php';
?>

<div class="container-fluid text-center mt-5 pt-5">
    <!-- Sección de bienvenida -->
    <section class="bienvenida-section text-center py-5">
        <div class="container">
            <!-- Logotipo -->
            <img
                src="<?php echo SERVIDOR . '/img/Logo.jpg'; ?>"
                alt="Logo Ganaderos"
                class="mb-4"
                style="max-width: 150px;">

            <!-- Título principal (a todo el ancho) -->
            <div class="row">
                <div class="col-12">
                    <h1 class="display-4 font-weight-bold text-success">Bienvenido</h1>
                </div>
            </div>
            <!-- Texto y botones centrados -->
            <div class="row mt-5">
                <div class="col-12 col-md-6 offset-md-3 text-center">
                    <p class="lead text-dark">
                        La plataforma para <span class="text-success fw-bold">comprar</span> y <span class="text-danger fw-bold">vender</span> ganado de forma segura y confiable.
                    </p>
                    <a href="<?php echo RUTA_COMPRA; ?>" class="btn btn-danger btn-lg mx-2">Comprar</a>
                    <a href="<?php echo RUTA_VENTA; ?>" class="btn btn-outline-success btn-lg mx-2">Vender</a>
                </div>
            </div>
        </div>
    </section>

<?php
Conexion::abrir_conexion();
RepositorioAnimal::eliminar_anuncios_vencidos(Conexion::obtener_conexion());

EscritorAnimal::escribir_carrusel_sugeridos();
?>


<!-- Interacción: Banner de confianza -->
<div class="container my-4">
    <div class="alert alert-success shadow-sm text-center" style="font-size:1.15rem;">
        <i class="fas fa-shield-alt me-2"></i>
        <strong>¡Compra y vende con total seguridad!</strong> Todos los usuarios y publicaciones son verificados por nuestro equipo.
    </div>
</div>

<?php
echo '<hr class="my-5" style="border-top: 2px solid #28a745;">';
?>

<!-- Sección informativa para compradores y vendedores -->
<div class="container my-5">
    <div class="row align-items-center">
        <div class="col-md-6 mb-4 mb-md-0">
            <div class="p-4 bg-light rounded shadow-sm h-100">
                <h3 class="text-success mb-3"><i class="fas fa-shopping-cart me-2"></i>¿Quieres Comprar?</h3>
                <p class="mb-2">
                    Explora cientos de publicaciones de ganado de calidad, compara precios y contacta directamente con los vendedores de todo el país. 
                </p>
                <a href="<?php echo RUTA_COMPRA; ?>" class="btn btn-danger btn-lg mt-2">Ver publicaciones</a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="p-4 bg-light rounded shadow-sm h-100">
                <h3 class="text-danger mb-3"><i class="fas fa-bullhorn me-2"></i>¿Quieres Vender?</h3>
                <p class="mb-2">
                    Publica tu ganado en minutos, llega a miles de compradores potenciales y gestiona tus anuncios de forma sencilla y segura.
                </p>
                <a href="<?php echo RUTA_VENTA; ?>" class="btn btn-outline-success btn-lg mt-2">Publicar ahora</a>
            </div>
        </div>
    </div>
</div>

<?php
echo '<hr class="my-5" style="border-top: 2px solid #28a745;">';
EscritorAnimal::escribir_carrusel_premium();
?>

<!-- Interacción: Llamado a la acción para soporte -->
<div class="container my-4">
    <div class="alert alert-warning shadow-sm text-center" style="font-size:1.1rem;">
        <i class="fas fa-headset me-2"></i>
        ¿Tienes dudas? <a href="<?php echo RUTA_CONTACTENOS; ?>" class="fw-bold text-success">Contáctanos aquí</a> y recibe atención personalizada.
    </div>
</div>

<?php
echo '<hr class="my-5" style="border-top: 2px solid #28a745;">';
EscritorAnimal::escribir_carrusel_destacados();
?>

<!-- Interacción: Banner de sostenibilidad -->
<div class="container my-4">
    <div class="bg-success bg-opacity-10 border border-success rounded shadow-sm p-3 text-center">
        <i class="fas fa-leaf text-success me-2"></i>
        <span class="fw-bold text-success">Comprometidos con la ganadería sostenible y el bienestar animal.</span>
    </div>
</div>

<?php
echo '<hr class="my-5" style="border-top: 2px solid #28a745;">';
EscritorAnimal::escribir_carrusel_ultimas_publicaciones();
?>


<?php
include_once 'plantillas/documento-cierre.inc.php';
?>

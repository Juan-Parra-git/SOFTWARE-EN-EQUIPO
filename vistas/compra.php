<?php
include_once 'app/ControlSesion.inc.php';
include_once 'app/Conexion.inc.php';
include_once 'app/Redireccion.inc.php';
include_once 'app/config.inc.php';
include_once 'app/EscritorAnimal.inc.php';


include_once 'plantillas/documento-apertura.inc.php';
include_once 'plantillas/navbar.inc.php';
?>
<br><br>    
<div class="container my-5 seccion-explora">
    <h1 class="text-center text-success mb-4 fw-bold titulo-seccion-explora">
        Explora cada una de nuestras secciones y encuentra el ganado que buscas
    </h1>
    <p class="lead text-center subtitulo-seccion-explora">
        Descubre una amplia variedad de ganado disponible para compra. Navega por las diferentes categorías y encuentra fácilmente los animales que se adaptan a tus necesidades.
    </p>
</div>


<?php
Conexion::abrir_conexion();
RepositorioAnimal::eliminar_anuncios_vencidos(Conexion::obtener_conexion());

EscritorAnimal::escribir_carrusel_sugeridos();
echo '<hr class="my-5" style="border-top: 2px solid #28a745;">';
EscritorAnimal::escribir_carrusel_premium();
echo '<hr class="my-5" style="border-top: 2px solid #28a745;">';
EscritorAnimal::escribir_carrusel_destacados();

EscritorAnimal::escribir_carrusel_ultimas_publicaciones();
?>

<?php
include_once 'plantillas/documento-cierre.inc.php';
?>





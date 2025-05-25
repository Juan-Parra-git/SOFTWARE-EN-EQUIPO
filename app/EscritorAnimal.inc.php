<?php
include_once 'Conexion.inc.php';
include_once 'RepositorioAnimal.inc.php';
include_once 'Animal.inc.php';

class EscritorAnimal
{
    // Carrusel de sugeridos
    public static function escribir_carrusel_sugeridos()
    {
        Conexion::abrir_conexion();
        $animales = RepositorioAnimal::obtener_animal_sugerido(Conexion::obtener_conexion());
        self::escribir_carrusel($animales, 'carousel-sugeridos', 'Publicaciones Sugeridas');
    }

    // Carrusel de premium
    public static function escribir_carrusel_premium()
    {
        Conexion::abrir_conexion();
        $animales = RepositorioAnimal::obtener_animal_premium(Conexion::obtener_conexion());
        self::escribir_carrusel($animales, 'carousel-premium', 'Publicaciones Premium');
    }

    // Carrusel de destacados
    public static function escribir_carrusel_destacados()
    {
        Conexion::abrir_conexion();
        $animales = RepositorioAnimal::obtener_animal_destacado(Conexion::obtener_conexion());
        self::escribir_carrusel($animales, 'carousel-destacados', 'Publicaciones Destacadas');
    }

    public static function escribir_carrusel_ultimas_publicaciones()
    {
        Conexion::abrir_conexion();
        $animales = RepositorioAnimal::obtener_animales_normales(Conexion::obtener_conexion());
        self::escribir_carrusel($animales, 'carousel-ultimas-publicaciones', 'Últimas Publicaciones');
    }

    // Carrusel genérico
    private static function escribir_carrusel($animales, $carousel_id, $titulo)
    {
        if ($animales instanceof Animal) {
            $animales = [$animales];
        }
        if (empty($animales)) {
            echo "<div class='alert alert-info text-center'>No hay publicaciones para mostrar.</div>";
            return;
        }
        // Renderiza todas las tarjetas en un solo slide, el JS se encarga de agruparlas
?>
        <div class="container my-5">
            <h2 class="text-center text-success mb-4"><?= htmlspecialchars($titulo) ?></h2>
            <div id="<?= htmlspecialchars($carousel_id) ?>" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators"></div>
                <div class="carousel-inner"></div>
                <div class="carousel-controls-below d-flex justify-content-between mt-3 px-3" style="display:none;">
                    <button class="boton-carrusel" type="button" data-bs-target="#<?= htmlspecialchars($carousel_id) ?>" data-bs-slide="prev">
                        ⬅ Anterior
                    </button>
                    <button class="boton-carrusel" type="button" data-bs-target="#<?= htmlspecialchars($carousel_id) ?>" data-bs-slide="next">
                        Siguiente ➡
                    </button>
                </div>
                <div class="d-none" id="<?= htmlspecialchars($carousel_id) ?>-all-cards">
                    <?php foreach ($animales as $animal): ?>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 d-flex align-items-stretch">
                            <?php self::escribir_tarjeta_animal($animal, false); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <script>
            (function() {
                function getChunkSize() {
                    if (window.innerWidth >= 1200) return 4; // xl+
                    if (window.innerWidth >= 992) return 4; // lg
                    if (window.innerWidth >= 768) return 3; // md
                    if (window.innerWidth >= 576) return 2; // sm
                    return 1; // xs
                }

                function chunkArray(arr, size) {
                    var results = [];
                    for (var i = 0; i < arr.length; i += size) {
                        results.push(arr.slice(i, i + size));
                    }
                    return results;
                }

                function renderCarousel() {
                    var carouselId = "<?= htmlspecialchars($carousel_id) ?>";
                    var allCards = document.querySelectorAll("#" + carouselId + "-all-cards > div");
                    var chunkSize = getChunkSize();
                    var chunks = chunkArray(Array.from(allCards), chunkSize);

                    var carouselInner = document.querySelector("#" + carouselId + " .carousel-inner");
                    var indicators = document.querySelector("#" + carouselId + " .carousel-indicators");
                    var controls = document.querySelector("#" + carouselId + " .carousel-controls-below");

                    carouselInner.innerHTML = "";
                    indicators.innerHTML = "";

                    chunks.forEach(function(chunk, i) {
                        var item = document.createElement("div");
                        item.className = "carousel-item" + (i === 0 ? " active" : "");
                        var row = document.createElement("div");
                        row.className = "row justify-content-center";
                        chunk.forEach(function(card) {
                            row.appendChild(card.cloneNode(true));
                        });
                        item.appendChild(row);
                        carouselInner.appendChild(item);

                        var indicator = document.createElement("button");
                        indicator.type = "button";
                        indicator.setAttribute("data-bs-target", "#" + carouselId);
                        indicator.setAttribute("data-bs-slide-to", i);
                        indicator.setAttribute("aria-label", "Slide " + (i + 1));
                        if (i === 0) {
                            indicator.className = "active";
                            indicator.setAttribute("aria-current", "true");
                        }
                        indicators.appendChild(indicator);
                    });

                    // Mostrar controles solo si hay más de un slide
                    controls.style.display = (chunks.length > 1) ? "flex" : "none";
                    indicators.style.display = (chunks.length > 1) ? "block" : "none";
                }
                window.addEventListener("resize", renderCarousel);
                window.addEventListener("DOMContentLoaded", renderCarousel);
            })();
        </script>
    <?php
    }

    // Tarjeta individual de animal
    private static function escribir_tarjeta_animal($animal, $wrapCol = true)
    {
        $imagenes = json_decode($animal->obtener_imagenes(), true);
        $img = (!empty($imagenes) && is_array($imagenes)) ? $imagenes[0] : 'img/default-animal.jpg';
        $url = SERVIDOR . '/animal/' . $animal->obtener_id();
        $precio_formateado = '$ ' . number_format($animal->obtener_precio(), 0, ',', '.') . ' COP';

        if ($wrapCol) {
            echo '<div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 d-flex align-items-stretch">';
        }
    ?>
        <div class="card tarjeta-animal shadow-sm animar-entrada" style="width: 100%; min-height: 420px; max-height: 420px; display: flex; flex-direction: column;">
            <img src="<?= htmlspecialchars($img) ?>" class="card-img-top tarjeta-animal-img" alt="Imagen de la publicación" style="height: 220px; object-fit: cover; background: #fff;">
            <div class="card-body d-flex flex-column justify-content-between tarjeta-animal-body" style="flex: 1 1 auto;">
                <h5 class="card-title tarjeta-animal-title"><?= htmlspecialchars($animal->obtener_titulo()) ?></h5>
                <ul class="list-unstyled mb-3">
                    <li><i class="fas fa-tag text-success me-2"></i><strong>Categoría:</strong> <?= htmlspecialchars($animal->obtener_categoria()) ?></li>
                    <li><i class="fas fa-cow text-success me-2"></i><strong>Raza:</strong> <?= htmlspecialchars($animal->obtener_raza()) ?></li>
                </ul>
                <span class="precio-animal mb-2"><?= $precio_formateado ?></span>
                <a href="<?= htmlspecialchars($url) ?>" class="btn boton-carrusel btn-xs tarjeta-animal-btn mt-2 px-3 py-1" style="font-size:0.85rem;">Ver publicación</a>

            </div>
        </div>
<?php
        if ($wrapCol) {
            echo '</div>';
        }
    }

    public static function mostrar_vista_animal($id_animal)
    {
        $animal = RepositorioAnimal::obtener_animal_por_id(Conexion::obtener_conexion(), $id_animal);
        include_once __DIR__ . '/../vistas/vista-animal.php';

    }
}
?>
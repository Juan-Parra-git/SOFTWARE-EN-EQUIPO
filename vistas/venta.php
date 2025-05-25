<?php


include_once 'app/config.inc.php';
include_once 'app/Conexion.inc.php';
include_once 'app/ControlSesion.inc.php';
include_once 'app/animal.inc.php';
include_once 'app/usuario.inc.php';
include_once 'app/RepositorioAnimal.inc.php';

$titulo = 'Ingreso de ganado';

// Asegurarse de que el usuario esté autenticado
if (!ControlSesion::sesion_iniciada()) {
    header("Location: " . RUTA_LOGIN);
    exit();
}

$mensaje_exito = false;
$mensaje_id = '';

if (isset($_SESSION['publicacion_exitosa']) && $_SESSION['publicacion_exitosa']) {
    $mensaje_exito = true;
    $mensaje_id = $_SESSION['publicacion_id'];

    // Limpiar para que no se repita al recargar otra vez
    unset($_SESSION['publicacion_exitosa']);
    unset($_SESSION['publicacion_id']);
}



if (isset($_POST['publicar'])) {
    Conexion::abrir_conexion();
    $conexion = Conexion::obtener_conexion();
    // Obtener el plan seleccionado
    $plan_seleccionado = $_POST['plan'] ?? 'personal1'; // fallback por si se pierde
    $duraciones = [
        'personal1' => '+2 months',
        'personal2' => '+4 months',
        'personal3' => '+4 months',
        'ganaderos1' => '+4 months',
        'ganaderos2' => '+4 months',
    ];

    $duracion = $duraciones[$plan_seleccionado] ?? '+4 months'; // fallback adicional
    $hoy = new DateTime();
    $hoy->modify($duracion);
    $fecha_fin = $hoy->format('Y-m-d H:i:s');





    $usuario_id = $_SESSION['id_usuario'];
    $nombre_usuario = $_SESSION['nombre_usuario'];
    $publicacion_id = uniqid('venta_', true);


    // Datos del animal enviados desde el formulario
    $titulo_animal = isset($_POST['titulo']) ? $_POST['titulo'] : '';
    $descripcion_animal = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';
    $categoria_animal = isset($_POST['categoria']) ? $_POST['categoria'] : '';
    $raza_animal = isset($_POST['raza']) ? $_POST['raza'] : '';
    $pureza_animal = isset($_POST['pureza']) ? $_POST['pureza'] : '';
    $sexo_animal = isset($_POST['sexo']) ? $_POST['sexo'] : '';
    $tipo_animal = isset($_POST['tipo_animal']) ? $_POST['tipo_animal'] : '';
    $edad_animal = isset($_POST['edad']) ? $_POST['edad'] : '';
    $peso_animal = isset($_POST['peso']) ? floatval($_POST['peso']) : 0;
    $precio_animal = isset($_POST['precio']) ? floatval($_POST['precio']) : 0;
    $tipo_precio_animal = isset($_POST['tipo_precio']) ? $_POST['tipo_precio'] : '';
    $telefono_animal = isset($_POST['telefono']) ? $_POST['telefono'] : '';
    $correo_animal = isset($_POST['correo']) ? $_POST['correo'] : '';
    $departamento_animal = isset($_POST['departamento']) ? $_POST['departamento'] : '';
    $municipio_animal = isset($_POST['municipio']) ? $_POST['municipio'] : '';
    $direccion_animal = isset($_POST['direccion']) ? $_POST['direccion'] : '';
    $destacado_animal = (isset($_POST['destacado']) && $_POST['destacado'] === 'on') ? 1 : 0;
    $premium_animal = (isset($_POST['premium']) && $_POST['premium'] === 'on') ? 1 : 0;
    $imagenes_guardadas = [];
    $videos_guardados = [];
    $sugerido = (isset($_POST['sugerido']) && $_POST['sugerido'] === 'on') ? 1 : 0;
    $fecha_fin = $fecha_fin;

    // Rutas
    $directorio_base = "usuarios/";
    $ruta_base = $directorio_base . "$nombre_usuario/$publicacion_id/";


    // Crear carpeta si no existe
    if (!file_exists($ruta_base)) {
        mkdir($ruta_base, 0777, true);
        chmod($ruta_base, 0777);
    }

    $upload_dir = $_SERVER['DOCUMENT_ROOT'] . "/Ganandez/" . $ruta_base;
    $upload_dir_relativo = $ruta_base;

    $imagenes_guardadas = [];
    $videos_guardados = [];

    if (!empty($_POST['fotos_tmp']) && !empty($_POST['fotos_type'])) {
        foreach ($_POST['fotos_tmp'] as $i => $base64) {
            $data = base64_decode($base64);
            $tipo = $_POST['fotos_type'][$i];
            $ext = explode('/', $tipo)[1];
            $nombre_archivo = uniqid('img_', true) . '.' . $ext;
            $ruta_final = $upload_dir . $nombre_archivo;
            $ruta_relativa = $upload_dir_relativo . $nombre_archivo;
            file_put_contents($ruta_final, $data);
            $imagenes_guardadas[] = $ruta_relativa;
        }
    }

    if (!empty($_POST['videos_tmp']) && !empty($_POST['videos_type'])) {
        foreach ($_POST['videos_tmp'] as $i => $base64) {
            $data = base64_decode($base64);
            $tipo = $_POST['videos_type'][$i];
            $ext = explode('/', $tipo)[1];
            $nombre_archivo = uniqid('vid_', true) . '.' . $ext;
            $ruta_final = $upload_dir . $nombre_archivo;
            $ruta_relativa = $upload_dir_relativo . $nombre_archivo;
            file_put_contents($ruta_final, $data);
            $videos_guardados[] = $ruta_relativa;
        }
    }

    function es_valido_mime($file_tmp, $tipos_validos)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file_tmp);
        finfo_close($finfo);
        return in_array($mime, $tipos_validos);
    }

    // Subir imágenes
    if (isset($_FILES['fotos']) && !empty($_FILES['fotos']['name'][0])) {
        foreach ($_FILES['fotos']['tmp_name'] as $index => $tmpPath) {
            if ($_FILES['fotos']['error'][$index] === UPLOAD_ERR_OK && es_valido_mime($tmpPath, $mime_imagenes)) {
                $ext = strtolower(pathinfo($_FILES['fotos']['name'][$index], PATHINFO_EXTENSION));
                $nombre_archivo = uniqid('img_', true) . '.' . $ext;
                $ruta_final = $upload_dir . $nombre_archivo;
                $ruta_relativa = $upload_dir_relativo . $nombre_archivo;

                if (move_uploaded_file($tmpPath, $ruta_final)) {
                    $imagenes_guardadas[] = $ruta_relativa;
                }
            }
        }
    }

    // Subir videos
    if (isset($_FILES['videos']) && !empty($_FILES['videos']['name'][0])) {
        foreach ($_FILES['videos']['tmp_name'] as $index => $tmpPath) {
            if ($_FILES['videos']['error'][$index] === UPLOAD_ERR_OK && es_valido_mime($tmpPath, $mime_videos)) {
                $ext = strtolower(pathinfo($_FILES['videos']['name'][$index], PATHINFO_EXTENSION));
                $nombre_archivo = uniqid('vid_', true) . '.' . $ext;
                $ruta_final = $upload_dir . $nombre_archivo;
                $ruta_relativa = $upload_dir_relativo . $nombre_archivo;

                if (move_uploaded_file($tmpPath, $ruta_final)) {
                    $videos_guardados[] = $ruta_relativa;
                }
            }
        }
    }

    // Convertir rutas a JSON (para que RepositorioAnimal las inserte)
    $imagenes_json = json_encode($imagenes_guardadas);
    $videos_json = json_encode($videos_guardados);



    // Crear el objeto Animal (agrega los campos al constructor y a la tabla)
    $animal = new Animal(
        null,
        $titulo_animal,
        $descripcion_animal,
        $categoria_animal,
        $raza_animal,
        $pureza_animal,
        $sexo_animal,
        $tipo_animal,
        $edad_animal,
        $peso_animal,
        $precio_animal,
        $tipo_precio_animal,
        $telefono_animal,
        $correo_animal,
        $departamento_animal,
        $municipio_animal,
        $direccion_animal,
        $destacado_animal,
        $premium_animal,
        $imagenes_json,
        $videos_json,
        $sugerido,
        $fecha_fin
    );

    // Ahora sí, inserta el animal
    $conexion->beginTransaction();
    try {
        $animal_insertado = RepositorioAnimal::insertar_animal($conexion, $animal);
        if (!$animal_insertado) {
            throw new Exception('No se pudo insertar el animal.');
        }
        $conexion->commit();
        $conexion->lastInsertId();
        // Guardar mensaje en sesión para mostrarlo tras redirección
        $_SESSION['publicacion_exitosa'] = true;
        $_SESSION['publicacion_id'] = $animal_insertado;

        // Redirigir para evitar reenvío del formulario
        header("Location: " . RUTA_VENTA);
        exit();
    } catch (Exception $e) {
        $conexion->rollBack();
        echo "Error en la transacción: " . $e->getMessage();
    }
}

// Incluir el encabezado y el navbar
include_once 'plantillas/documento-apertura.inc.php';
include_once 'plantillas/navbar.inc.php';

?>

<br><br><br>
<div class="container text-center mt-4 mb-4">
    <h2 class="main-title">
        <i class="fas fa-cow text-success me-2"></i>
        Selecciona tu plan Ganadero
        <i class="fas fa-tractor text-danger ms-2"></i>
    </h2>
    <p class="main-subtitle">Elige el plan que mejor se adapte a tus necesidades personales o de producción</p>
</div>
<?php if ($mensaje_exito): ?>
    <div class="alert alert-success text-center animate-fade-in">
        <strong>¡Tu publicación fue realizada con éxito!</strong><br>
        ID de publicación: <code><?= htmlspecialchars($mensaje_id) ?></code>
    </div>

<?php endif; ?>


<div class="container mt-5">
    <div class="card tarjeta-registro animate-fade-in">
        <!-- Planes personales -->
        <details class="mb-4 border p-3 bg-white shadow-sm rounded">
            <summary class="form-label text-success h5 mb-3">Planes personales</summary>
            <div class="contenido-animado">
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-4 mb-4">
                        <label class="plan-opcion w-100">
                            <input type="radio" name="plan" value="personal1" required>
                            <img src="img/1.png" alt="Plan 1" class="imagen-plan">
                        </label>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 mb-4">
                        <label class="plan-opcion w-100">
                            <input type="radio" name="plan" value="personal2">
                            <img src="img/2.png" alt="Plan 2" class="imagen-plan">
                        </label>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 mb-4">
                        <label class="plan-opcion w-100">
                            <input type="radio" name="plan" value="personal3">
                            <img src="img/3.png" alt="Plan 3" class="imagen-plan">
                        </label>
                    </div>
                </div>
            </div>
        </details>

        <!-- Planes ganadería -->
        <details class="mb-4 border p-3 bg-white shadow-sm rounded">
            <summary class="form-label text-success h5 mb-3">Planes para ganadería</summary>
            <div class="contenido-animado">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="plan-opcion w-100">
                            <input type="radio" name="plan" value="ganaderos1">
                            <img src="img/4.png" alt="Ganadería 1" class="imagen-plan">
                        </label>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="plan-opcion w-100">
                            <input type="radio" name="plan" value="ganaderos2">
                            <img src="img/5.png" alt="Ganadería 2" class="imagen-plan">
                        </label>
                    </div>
                </div>
            </div>
        </details>
    </div>

    <!-- Formulario de publicación -->
    <form method="post" action="<?php echo RUTA_PREVIA_VENTA; ?>" id="formulario-publicacion" style="display: none;" enctype="multipart/form-data">
        <?php include_once 'plantillas/form_nuevo_registro_compra_vacio.inc.php'; ?>
        <input type="hidden" name="plan" id="input-plan-seleccionado">
        <div class="text-center mt-4">


            <button type="submit" class="btn btn-danger btn-lg btn-custom animate-fade-in" name="publicar">
                <i class="fas fa-upload me-2"></i> Publicar
            </button>
        </div>

        <!-- JS dinámico -->
        <script>
            const razasPorCategoria = {
                carne: ["Aberdeen Angus", "Limousine", "Bosmara", "Brahman", "Gyr", "Guzerat", "Nelore", "Big master", "Nelore pintado", "Brangus", "Simbrah", "Red sindhi", "Branford", "Herford", "Otros"],
                leche: ["Holstein", "Jersey", "Pardo Suizo", "Gyr Lechero", "Girolando (cruce entre Gyr y Holstein)", "Ayrshire", "Jerhol", "Gusolando", "Bramolando", "Otros"],
                doble: ["Simmental", "Romosinuano", "Blanco Orejinegro (BON)", "Sanmartinero", "Normando", "Girolando", "Pardo suizo", "Otros"]
            };

            document.getElementById('categoria')?.addEventListener('change', function() {
                const categoria = this.value;
                const razaSelect = document.getElementById('raza');
                razaSelect.innerHTML = '<option value="">Selecciona una raza</option>';

                if (razasPorCategoria[categoria]) {
                    razasPorCategoria[categoria].forEach(raza => {
                        const option = document.createElement('option');
                        option.value = raza.toLowerCase();
                        option.textContent = raza;
                        razaSelect.appendChild(option);
                    });
                }
            });

            const planRadios = document.querySelectorAll('input[name="plan"]');
            const formulario = document.getElementById('formulario-publicacion');
            const campoVideo = document.getElementById('campo-video');
            const inputFotos = document.getElementById('fotos');
            const ayudaFotos = document.getElementById('ayuda-fotos');
            let maxArchivos = 5; // Por defecto

            // Referencias a los tres checkboxes
            const destacadoCheckbox = document.getElementById('destacado');
            const premiumCheckbox = document.getElementById('premium');
            const sugeridoCheckbox = document.getElementById('sugerido');

            if (campoVideo) campoVideo.style.display = 'none';

            // Maneja el cambio de plan
            planRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    formulario.style.display = 'block';
                    document.getElementById('input-plan-seleccionado').value = this.value;

                    

                    if (this.value === 'personal1') {
                        // Plan personal1: No acceso a ninguno de los campos
                        destacadoCheckbox.disabled = true;
                        premiumCheckbox.disabled = true;
                        sugeridoCheckbox.disabled = true;

                        // Limpiar valores
                        destacadoCheckbox.checked = false;
                        premiumCheckbox.checked = false;
                        sugeridoCheckbox.checked = false;

                        campoVideo.style.display = 'none';
                        maxArchivos = 1;
                    } else if (this.value === 'personal2' || this.value === 'ganaderos1') {
                        // Planes personal2 y ganaderos2: Solo acceso a DESTACADOS
                        destacadoCheckbox.disabled = false;
                        premiumCheckbox.disabled = true;
                        sugeridoCheckbox.disabled = true;

                        // Limpiar valores
                        premiumCheckbox.checked = false;
                        sugeridoCheckbox.checked = false;
                        campoVideo.style.display = 'block';
                        maxArchivos = 5;
                    } else if (this.value === 'personal3' || this.value === 'ganaderos2') {
                        // Planes personal3 y ganaderos3: Acceso a los tres campos
                        destacadoCheckbox.disabled = false;
                        premiumCheckbox.disabled = false;
                        sugeridoCheckbox.disabled = false;

                        campoVideo.style.display = 'block';
                        maxArchivos = 5;
                    }

                    // Limpiar selección previa de fotos
                    if (inputFotos) {
                        inputFotos.value = '';
                    }

                    if (ayudaFotos) {
                        ayudaFotos.textContent = `Puedes subir hasta ${maxArchivos} imagen${maxArchivos === 1 ? '' : 'es'} (formatos: JPG, PNG, GIF).`;
                    }

                    formulario.scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });


            // Vista previa de imágenes
            function mostrarVistaPrevia(input) {
                const preview = document.getElementById('preview-fotos');
                const archivos = input.files;

                preview.innerHTML = ""; // Limpiar miniaturas previas

                if (archivos.length > maxArchivos) {
                    ayudaFotos.textContent = `Máximo ${maxArchivos} imagen${maxArchivos === 1 ? '' : 'es'} permitida${maxArchivos === 1 ? '' : 's'}.`;
                    input.value = "";
                    return;
                }

                const tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif'];
                let validos = true;

                for (let i = 0; i < archivos.length; i++) {
                    const archivo = archivos[i];

                    if (!tiposPermitidos.includes(archivo.type)) {
                        ayudaFotos.textContent = `Formato no permitido: ${archivo.name}`;
                        input.value = "";
                        preview.innerHTML = "";
                        validos = false;
                        break;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.maxWidth = "200px";
                        img.style.maxHeight = "200px";
                        img.classList.add("img-thumbnail", "me-2", "mb-2");
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(archivo);
                }

                if (validos) {
                    ayudaFotos.textContent = `${archivos.length} archivo${archivos.length > 1 ? 's' : ''} válido${archivos.length > 1 ? 's' : ''} seleccionado${archivos.length > 1 ? 's' : ''}.`;
                }
            }


            function mostrarVistaPreviaVideo(input) {
                const maxVideos = 1;
                const preview = document.getElementById('preview-video');
                const ayuda = document.getElementById('ayuda-video');
                const archivos = input.files;

                preview.innerHTML = "";

                if (archivos.length > maxVideos) {
                    ayuda.textContent = `Solo puedes subir ${maxVideos} video.`;
                    input.value = "";
                    return;
                }

                const tiposPermitidos = ['video/mp4', 'video/avi', 'video/quicktime'];
                const archivo = archivos[0];

                if (!tiposPermitidos.includes(archivo.type)) {
                    ayuda.textContent = `Formato no permitido: ${archivo.name}`;
                    input.value = "";
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const video = document.createElement('video');
                    video.src = e.target.result;
                    video.controls = true;
                    video.style.maxWidth = "100%";
                    video.style.maxHeight = "320px";
                    preview.appendChild(video);
                    ayuda.textContent = "Video válido cargado correctamente.";
                };
                reader.readAsDataURL(archivo);
            }

            document.getElementById('precio').addEventListener('input', function(e) {
                let input = e.target;
                let value = input.value.replace(/\D/g, ''); // Elimina todo lo que no sea número
                if (!value) {
                    input.value = '';
                    return;
                }
                // Convierte a número y aplica formato solo en la vista
                let formatted = '$' + parseInt(value, 10).toLocaleString('es-CO');
                input.value = formatted;
            });

            document.getElementById('formulario-publicacion').addEventListener('submit', function(e) {
                const precioInput = document.getElementById('precio');
                if (precioInput) {
                    // Limpiar el valor de precio antes de enviarlo
                    precioInput.value = precioInput.value.replace(/[^\d]/g, ''); // Eliminar caracteres no numéricos
                }
            });
        </script>

        <?php include_once 'plantillas/documento-cierre.inc.php'; ?>
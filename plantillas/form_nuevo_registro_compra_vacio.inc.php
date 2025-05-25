<div class="card-body fondo-registro">
    <!-- Planes personales -->
    <div class="card-header encabezado-degradado text-white text-center">
        <h3 class="mb-0">Publicar animal</h3>
        <p class="subtitulo-registro">Completa la información para publicar tu ejemplar</p>
    </div>
    <div class="card-body fondo-registro">
        <!-- Título -->
        <div class="mb-3">
            <label for="titulo" class="form-label text-success">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" required placeholder="Ej. Novillo de engorde">
            <small class="form-text text-muted">Escribe un título claro y específico para tu anuncio, como "Novillo de engorde".</small>
        </div>

        <!-- Descripción -->
        <div class="mb-3">
            <label for="descripcion" class="form-label text-success">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required placeholder="Describe tu animal..."></textarea>
            <small class="form-text text-muted">Describe detalladamente el animal para que los compradores potenciales tengan toda la información necesaria.</small>
        </div>

        <!-- Categoría -->
        <div class="mb-3">
            <label for="categoria" class="form-label text-success">Categoría</label>
            <select class="form-select" id="categoria" name="categoria" required>
                <option value="">Selecciona una categoría</option>
                <option value="carne">Carne</option>
                <option value="leche">Leche</option>
                <option value="doble">Doble propósito</option>
            </select>
            <small class="form-text text-muted">Elige la categoría que mejor describa tu animal (Carne, Leche, o Doble propósito).</small>
        </div>

        <!-- Raza -->
        <div class="mb-3">
            <label for="raza" class="form-label text-success">Raza</label>
            <select class="form-select" id="raza" name="raza" required>
                <option value="">Selecciona una raza</option>
            </select>
            <small class="form-text text-muted">Selecciona la raza del animal (si no está en la lista, puedes añadirla en la descripción).</small>
        </div>

        <!-- Pureza (radio) -->
        <div class="mb-3">
            <label class="form-label text-success">Pureza</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="pureza" id="puro" value="puro" required>
                <label class="form-check-label" for="puro">Puro</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="pureza" id="cruce" value="cruce">
                <label class="form-check-label" for="cruce">Cruce</label>
            </div>
            <small class="form-text text-muted">Selecciona si tu animal es puro o de cruce de razas.</small>
        </div>

        <!-- Sexo (radio) -->
        <div class="mb-3">
            <label class="form-label text-success">Sexo</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="sexo" id="macho" value="macho" required>
                <label class="form-check-label" for="macho">Macho</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="sexo" id="hembra" value="hembra">
                <label class="form-check-label" for="hembra">Hembra</label>
            </div>
            <small class="form-text text-muted">Selecciona el sexo del animal (Macho o Hembra).</small>
        </div>

        <!-- Tipo de animal (radio) -->
        <div class="mb-3">
            <label class="form-label text-success">Tipo de animal</label><br>
            <?php
            $tipos_animales = ['novillo', 'novilla', 'ternero', 'ternera', 'toro', 'vaca'];
            foreach ($tipos_animales as $tipo) {
                echo '
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="tipo_animal" id="' . $tipo . '" value="' . $tipo . '" required>
                <label class="form-check-label" for="' . $tipo . '">' . ucfirst($tipo) . '</label>
            </div>';
            }
            ?>
            <small class="form-text text-muted">Selecciona el tipo de animal que estás vendiendo (Novillo, Ternero, etc.).</small>
        </div>

        <!-- Edad -->
        <div class="mb-3">
            <label for="edad" class="form-label text-success">Edad</label>
            <select class="form-select" id="edad" name="edad" required>
                <option value="">Selecciona la edad</option>
                <optgroup label="Meses">
                    <?php for ($i = 1; $i <= 12; $i++) {
                        $texto = $i . ' ' . ($i == 1 ? 'mes' : 'meses');
                        echo "<option value='{$texto}'>{$texto}</option>";
                    } ?>
                </optgroup>
                <optgroup label="Años">
                    <?php for ($i = 1; $i <= 6; $i++) {
                        $texto = $i . ' ' . ($i == 1 ? 'año' : 'años');
                        echo "<option value='{$texto}'>{$texto}</option>";
                    } ?>
                    <option value="Más de 6 años">Más de 6 años</option>
                </optgroup>
            </select>
            <small class="form-text text-muted">Indica la edad del animal en meses o años.</small>
        </div>

        <!-- Peso -->
        <div class="mb-3">
            <label for="peso" class="form-label text-success">Peso (kg)</label>
            <input type="number" class="form-control" id="peso" name="peso" required placeholder="Ej. 450">
            <small class="form-text text-muted">Introduce el peso del animal en kilogramos.</small>
        </div>

        <!-- Precio -->
        <div class="mb-3">
            <label for="precio" class="form-label text-success">Precio (COP)</label>
            <input type="text" class="form-control" id="precio" name="precio" required placeholder="Ej. 1200000">
            <small class="form-text text-muted">Indica el precio en COP para tu animal.</small>
        </div>

        <!-- Tipo de precio (radio) -->
        <div class="mb-3">
            <label class="form-label text-success">Tipo de precio</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="tipo_precio" id="precio_peso" value="peso" required>
                <label class="form-check-label" for="precio_peso">Por peso</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="tipo_precio" id="precio_animal" value="animal">
                <label class="form-check-label" for="precio_animal">Por animal</label>
            </div>
            <small class="form-text text-muted">Selecciona si el precio es por peso o por animal.</small>
        </div>
        <!-- Campo para subir imágenes -->
        <div class="mb-3">
            <label for="fotos" class="form-label text-success">Fotos del animal</label>
            <input
                type="file"
                class="form-control"
                id="fotos"
                name="fotos[]"
                accept="image/jpeg,image/png,image/gif"
                multiple
                onchange="mostrarVistaPrevia(this)">
            <small id="ayuda-fotos" class="form-text text-muted">Puedes subir hasta 10 imágenes (JPG, PNG, GIF).</small>
            <div id="preview-fotos" class="mt-3 d-flex flex-wrap gap-2"></div>
        </div>


        <!-- Campo para subir videos -->
        <div class="mb-3" id="campo-video">
            <label for="videos" class="form-label text-success">Video del animal</label>
            <input
                type="file"
                class="form-control"
                id="videos"
                name="videos[]"
                accept="video/mp4,video/avi,video/quicktime"
                onchange="mostrarVistaPreviaVideo(this)">
            <small id="ayuda-video" class="form-text text-muted">Solo se permite subir un video (MP4, AVI, MOV).</small>
            <div id="preview-video" class="mt-3"></div>
        </div>


        <!-- Contacto -->
        <div class="mb-3">
            <label for="telefono" class="form-label text-success">Teléfono</label>
            <input type="tel" class="form-control" id="telefono" name="telefono" required>
            <small class="form-text text-muted">Escribe tu número de teléfono para que los compradores puedan contactarte.</small>
        </div>
        <div class="mb-3">
            <label for="correo" class="form-label text-success">Correo electrónico</label>
            <input type="email" class="form-control" id="correo" name="correo" required>
            <small class="form-text text-muted">Introduce tu correo electrónico para que podamos enviarte notificaciones.</small>
        </div>


        <!-- Ubicación -->
        <div class="mb-3">
            <label for="departamento" class="form-label text-success">Departamento</label>
            <input type="text" class="form-control" id="departamento" name="departamento" required>
            <small class="form-text text-muted">Indica el departamento donde se encuentra el animal.</small>
        </div>
        <div class="mb-3">
            <label for="municipio" class="form-label text-success">Municipio</label>
            <input type="text" class="form-control" id="municipio" name="municipio" required>
            <small class="form-text text-muted">Escribe el municipio donde resides o donde se encuentra el animal.</small>
        </div>
        <div class="mb-3">
            <label for="direccion" class="form-label text-success">Dirección</label>
            <input type="text" class="form-control" id="direccion" name="direccion" required>
            <small class="form-text text-muted">Introduce la dirección exacta donde el animal se encuentra.</small>
        </div>
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="destacado" name="destacado">
            <label class="form-check-label" for="destacado">¿Deseas que tu publicación esté en <strong>DESTACADOS</strong>?</label>
        </div>

        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="premium" name="premium">
            <label class="form-check-label" for="premium">¿Deseas que tu publicación esté en <strong>PREMIUM</strong>?</label>
        </div>

        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="sugerido" name="sugerido">
            <label class="form-check-label" for="su">¿Deseas que tu publicación esté en <strong>SUGERIDOS</strong>?</label>
        </div>

        <!-- Términos -->
        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" id="terminos" name="terminos" required>
            <label class="form-check-label text-success" for="terminos">
                Acepto los <a href="#" class="text-danger fw-bold">términos y condiciones</a>
            </label>
            <small class="form-text text-muted">Es importante que leas y aceptes los términos y condiciones para continuar.</small>
        </div>
    </div>
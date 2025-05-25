<?php
include_once 'app/ControlSesion.inc.php';
include_once 'plantillas/documento-apertura.inc.php';
include_once 'app/config.inc.php';
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom fixed-top shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2 text-light fw-bold" href="<?php echo SERVIDOR; ?>">
            <img src="<?php echo SERVIDOR . '/img/Logo.jpg'; ?>" alt="Ganaderos" height="60" class="d-inline-block align-text-top">
            <span class="fs-4">Gananado</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarGanaderos" aria-controls="navbarGanaderos" aria-expanded="false" aria-label="Menú">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarGanaderos">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="<?php echo SERVIDOR; ?>">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo RUTA_CONTACTENOS; ?>">Contactenos</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo RUTA_NOSOTROS; ?>">Nosotros</a></li>

                <?php if (!ControlSesion::sesion_iniciada()) { ?>
                    <!-- No ha iniciado sesión -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarSesion" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Forma parte de nosotros
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarSesion">
                            <li><a class="dropdown-item" href="<?php echo RUTA_LOGIN; ?>">Iniciar sesión</a></li>
                            <li><a class="dropdown-item" href="<?php echo RUTA_REGISTRO; ?>">Registrarse</a></li>
                        </ul>
                    </li>
                <?php } else { ?>
                    <!-- Usuario autenticado -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarUsuario" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Mi cuenta
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUsuario">
                            <li><a class="dropdown-item" href="<?php echo RUTA_PERFIL; ?>">Perfil</a></li>
                            <li><a class="dropdown-item" href="<?php echo RUTA_LOGOUT; ?>">Cerrar sesión</a></li>
                        </ul>
                    </li>
                <?php } ?>
            </ul>

            <a href="<?php echo RUTA_COMPRA; ?>" class="nav-item btn btn-danger btn-lg mx-2">Comprar</a>
            <a href="<?php echo RUTA_VENTA; ?>" class="nav-item btn btn-danger btn-lg">Vender</a>
        </div>
    </div>
</nav>

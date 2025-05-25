CREATE DATABASE TSP
    DEFAULT CHARACTER SET utf8;

USE TSP;

CREATE TABLE usuarios (
    id INT NOT NULL UNIQUE AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    identificacion VARCHAR(50) NOT NULL,
    correo VARCHAR(50) NOT NULL,
    telefono VARCHAR(50) NOT NULL,
    clave VARCHAR(255) NOT NULL,
    estado_membresia TINYINT DEFAULT 0 NOT NULL,
    rol VARCHAR(20) DEFAULT 'cliente' NOT NULL,
    estado_usuario TINYINT DEFAULT 1 NOT NULL,
    fecha_registro DATETIME NOT NULL,
    fecha_inicio_membresia DATETIME NOT NULL,
    fecha_fin_membresia DATETIME NOT NULL,
    tipo_insumos VARCHAR(50) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE recuperar_clave (
    id INT NOT NULL UNIQUE AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    url_secreta VARCHAR(255) NOT NULL,
    correo VARCHAR(255) NOT NULL UNIQUE,
    fecha DATETIME NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE animales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255),
    descripcion TEXT,
    categoria VARCHAR(50),
    raza VARCHAR(50),
    pureza VARCHAR(50),
    sexo VARCHAR(50),
    tipo_animal VARCHAR(50),
    edad VARCHAR(50),
    peso DECIMAL(50,2),
    precio DECIMAL(20,2),
    tipo_precio VARCHAR(10),
    telefono VARCHAR(20),
    correo VARCHAR(100),
    departamento VARCHAR(100),
    municipio VARCHAR(100),
    direccion VARCHAR(255),
    destacado BOOLEAN,
    premium BOOLEAN,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    imagenes TEXT,
    videos TEXT,
    sugerido BOOLEAN,
    fecha_fin TIMESTAMP
);




<?php
include('conecta.php');
// Comprobar si la tabla de supermercado existe, y si no existe la crea
$comprobar_tabla = "SHOW TABLES LIKE 'libros' ";
$comprobar = $conexion->query($comprobar_tabla) or die("Error al comprobar la tabla incidencias: " . $conexion->error);

if ($comprobar->num_rows <= 0) {
    $sql = "
    CREATE TABLE libros (    
    id INT AUTO_INCREMENT PRIMARY KEY, 
    nombre VARCHAR(50) NOT NULL, 
    autor VARCHAR(50) NOT NULL,
    publicacion INT(4) NOT NULL,
    isbn VARCHAR(13) NOT NULL,
    sinopsis VARCHAR(100) NOT NULL,
    n_disponibles INT(5) NOT NULL,
    n_totales INT(10) NOT NULL); 
    
    CREATE TABLE lectores (    
    id INT AUTO_INCREMENT PRIMARY KEY, 
    lector VARCHAR(50) NOT NULL, 
    dni VARCHAR(9) UNIQUE NOT NULL,
    estado BOOLEAN default TRUE NOT NULL,
    n_prestado INT(10) NOT NULL);

    CREATE TABLE prestamos (
    id_lector INT,
    id_libro INT,
    PRIMARY KEY (id_lector, id_libro),
    FOREIGN KEY (id_lector) REFERENCES lectores(id) ON DELETE CASCADE,
    FOREIGN KEY (id_libro) REFERENCES libros(id) ON DELETE CASCADE);

    INSERT INTO libros (nombre, autor, publicacion, isbn, sinopsis, n_disponibles, n_totales) VALUES 
    ('Harry Potter','J.K.Rowlig',2000,'123456789','Un niño que descubre que puede hacer magia en una escuela con muchos raritos y aventuras',20,50),
    ('Señor de los Anillos','J.R.R.Tolkien',1914,'987654321','Las aventuras de un hobbit en busca del anillo en compañía de otros raros',30,60);
    
    INSERT INTO lectores (lector, dni, estado, n_prestado) VALUES 
    ('Hugo Utrilla','50235099M',TRUE,0),
    ('Eduardo Rubio','29351647P', TRUE,0);
    ";

    if ($conexion->multi_query($sql)) {
        while ($conexion->next_result()) {;
        }
        echo "Tabla creada";
    } else {
        echo "Error: {$conexion->error}";
    }
    // Cierre a la base de datos
    $conexion->close();
}

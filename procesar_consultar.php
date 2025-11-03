<?php
include('conecta.php');

// Código PHP para procesar el formulario de registrar
if (isset($_POST['consultar'])) {
    // Obtener los datos del formulario con caracteres especiales:
    $lector = $_POST['lector'];

    // Verificar que no tenga libros prestados

    // Imprimimos todos los libros prestados
    $libros_prestados = "SELECT id_lector, (SELECT nombre from libros WHERE libros.id = prestamos.id_libro) AS libro_nombre FROM prestamos WHERE id_lector=$lector";
    $comprobar = $conexion->query($libros_prestados);

    if ($comprobar->num_rows > 0) {
        echo "<ul>";
        while ($tupla = $comprobar->fetch_assoc()) {

            $titulo = $tupla['libro_nombre'];
            echo "<li>$titulo</li>";
        }
        echo "</ul>";
    } else {
        echo "Ponte a leer ctm";
    }
}

$conexion->close();

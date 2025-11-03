<?php
include('conecta.php');
if (isset($_POST['prestamo'])) {
    list($id_lector, $id_libro) = explode('-', $_POST['prestamo']);

    // Ahora puedes usar $id_lector y $id_libro para eliminar el préstamo
    $eliminar = "DELETE FROM prestamos WHERE id_lector = $id_lector AND id_libro = $id_libro";
    if ($conexion->query($eliminar)) {
        echo "Préstamo devuelto correctamente.";

        // Actualizar n_prestado del lector
        $conexion->query("UPDATE lectores SET n_prestado = n_prestado - 1 WHERE id = $id_lector");

        // Actualizar n_disponible del libro
        $conexion->query("UPDATE libros SET n_disponibles = n_disponibles + 1 WHERE id = $id_libro");
    } else {
        echo "Error al devolver el préstamo: " . $conexion->error;
    }
}

$conexion->close();
